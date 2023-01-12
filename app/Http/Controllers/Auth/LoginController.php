<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TfaMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showLoginForm(Request $request)
    {
        // If the request comes from the Home or Pricing page, and the user has selected a plan
        if (($request->server('HTTP_REFERER') == route('pricing') || $request->server('HTTP_REFERER') == route('home').'/') && $request->input('plan') > 1) {
            $request->session()->put('plan_redirect', ['id' => $request->input('plan'), 'interval' => $request->input('interval')]);
        }

        if ($request->session()->get('email')) {
            $request->session()->keep(['email', 'remember']);

            return view('auth/tfa');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where($this->username(), '=', $request->input($this->username()))->first();

        // If the user exists, and has two-factor authentication enabled
        if (config('settings.login_tfa') && $user && $user->tfa) {
            // If the user credentials are valid
            if (auth()->validate($this->credentials($request))) {
                try {
                    Mail::to($user->email)->locale($user->locale)->send(new TfaMail($this->resetTfaCode($user)));
                } catch(\Exception $e) {
                    return redirect()->route('login')->with('error', $e->getMessage());
                }

                $request->session()->flash($this->username(), $request->input($this->username()));
                $request->session()->flash('remember', $request->boolean('remember'));

                return view('auth/tfa');
            }
        } else {
            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                return $this->sendLoginResponse($request);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function validateTfaCode(Request $request)
    {
        $request->session()->keep(['email', 'remember']);

        $user = User::where($this->username(), '=', $request->session()->get('email'))->first();

        // If the user exists, and has two-factor authentication enabled
        if (config('settings.login_tfa') && $user && $user->tfa) {
            $request->validate([
                'code' => ['required', 'integer',
                    function ($attribute, $value, $fail) use ($user) {
                        if ($value != $user->tfa_code) {
                            $fail(__("The security code is incorrect."));
                        }
                    },
                    function ($attribute, $value, $fail) use ($user) {
                        if ($user->tfa_code_created_at->lt(Carbon::now()->subMinutes(30))) {
                            $fail(__("The security code is expired."));
                        }
                    }
                ]
            ]);

            try {
                auth()->login($user, $request->session()->get('remember'));

                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                $this->resetTfaCode($user);

                $request->session()->forget(['email', 'remember']);

                return $this->sendLoginResponse($request);
            } catch (\Exception $e) {
                return redirect()->route('login')->with('error', $e->getMessage());
            }
        }

        return redirect()->route('login');
    }

    /**
     * Resends the two-factor authentication code to the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function resendTfaCode(Request $request)
    {
        $request->session()->keep(['email', 'remember']);

        $user = User::where($this->username(), '=', $request->session()->get('email'))->first();

        // If the user exists, and has two-factor authentication enabled
        if (config('settings.login_tfa') && $user && $user->tfa) {
            try {
                Mail::to($user->email)->locale($user->locale)->send(new TfaMail($this->resetTfaCode($user)));
            } catch(\Exception $e) {
                return redirect()->route('login')->with('error', $e->getMessage());
            }

            return back()->with('success', __('A new security code has been sent to your email address.'));
        }

        return redirect()->route('login');
    }

    /**
     * Resets the user's two-factor authentication code.
     *
     * @param User $user
     * @return int|mixed
     * @throws \Exception
     */
    private function resetTfaCode(User $user)
    {
        $user->tfa_code = random_int(100000, 999999);
        $user->tfa_code_created_at = Carbon::now();
        $user->save();

        return $user->tfa_code;
    }
}
