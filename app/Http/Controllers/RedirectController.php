<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Http\Requests\ValidateLinkPasswordRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use GeoIp2\Database\Reader as GeoIP;
use Illuminate\Support\Facades\DB;
use WhichBrowser\Parser as UserAgent;

class RedirectController extends Controller
{
    /**
     * Handle the Redirect.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        // Get the local host
        $local = parse_url(config('app.url'))['host'];

        // Get the request host
        $remote = $request->getHost();

        $link = null;

        // Get the remote domain
        $domain = Domain::where('name', '=', $remote)->first();

        // If the domain exists
        if ($domain) {
            // Get the link
            $link = Link::where([['alias', '=', $id], ['domain_id', '=', $domain->id]])->first();
        }

        // If the link exists
        if ($link) {
            // If the link or the user is disabled
            if ($link->disabled || ($link->user_id != 0 && $link->user->trashed())) {
                return view('redirect.disabled', ['link' => $link]);
            }

            // If the link contains banned words
            $bannedWords = preg_split('/\n|\r/', config('settings.bad_words'), -1, PREG_SPLIT_NO_EMPTY);

            foreach($bannedWords as $word) {
                // Search for the word in string
                if(strpos(mb_strtolower($link->url), mb_strtolower($word)) !== false) {
                    return view('redirect.banned', ['link' => $link]);
                }
            }

            $referrer = parse_url($request->server('HTTP_REFERER'), PHP_URL_HOST) ?? null;

            // If the link is password protected, but no validation has been done
            if ($link->password && $request->session()->get('verified_link') != $link->id) {
                // Cache the referrer
                $request->session()->put('referrer' . $link->id, $referrer);
            } elseif($link->password && $request->session()->get('verified_link') == $link->id) {
                // Retrieve the cached referrer
                $referrer = $request->session()->get('referrer' . $link->id);

                // If there's no additional consent required
                if (count($link->pixels) == 0) {
                    // Clear the cached referrer
                    $request->session()->forget('referrer' . $link->id);
                }
            }

            if (array_key_exists(1, $request->segments())) {
                if ($link->password && $request->session()->get('verified_link') != $link->id) {
                    return view('redirect.password', ['link' => $link]);
                }

                return view('redirect.preview', ['link' => $link]);
            }

            // If the URL is from a Guest User
            if ($link->user_id == 0) {
                // Increase the total click count
                Link::where('id', $link->id)->increment('clicks', 1);

                return redirect()->to($this->urlParamsForward($link->url), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            // If the link has expired
            if(Carbon::now()->greaterThan($link->ends_at) && $link->ends_at) {
                // If the link has an expiration url
                if ($link->expiration_url) {
                    return redirect()->to($link->expiration_url, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }

                return view('redirect.expired', ['link' => $link]);
            }

            // If the link expiration clicks exceeded
            if ($link->expiration_clicks && $link->clicks >= $link->expiration_clicks) {
                // If the link has an expiration url
                if ($link->expiration_url) {
                    return redirect()->to($link->expiration_url, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }

                return view('redirect.expired', ['link' => $link]);
            }

            // If the link is password protected
            if ($link->password && $request->session()->get('verified_link') != $link->id) {
                return view('redirect.password', ['link' => $link]);
            }

            // If the link requires consent
            if (count($link->pixels) > 0) {
                // If the user did not previously visit the consent page
                if (! $request->hasCookie('consent' . $link->id)) {
                    // Cache the referrer
                    session(['referrer' . $link->id => $referrer]);

                    return view('redirect.consent', ['link' => $link]);
                } else {
                    // Retrieve the cached referrer
                    $referrer = $request->session()->get('referrer' . $link->id);

                    $request->session()->forget('referrer' . $link->id);
                }
            }

            $ua = new UserAgent(getallheaders());

            // If the UA is a BOT
            if ($ua->device->type == 'bot') {
                return redirect()->to($this->urlParamsForward($link->url), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            // Get the user's geolocation
            try {
                $geoip = (new GeoIP(storage_path('app/geoip/GeoLite2-City.mmdb')))->city($request->ip());

                $countryCode = $geoip->country->isoCode;
                $country = $geoip->country->isoCode . ':' . $geoip->country->name;
                $city = $geoip->country->isoCode . ':' . $geoip->city->name . (isset($geoip->mostSpecificSubdivision->isoCode) ? ', ' . $geoip->mostSpecificSubdivision->isoCode : '');
            } catch (\Exception $e) {
                $countryCode = $country = $city = null;
            }

            $now = Carbon::now();

            $date = $now->format('Y-m-d');
            $time = $now->format('H');

            // Add the country
            $data['country'] = $country;

            // Add the city
            $data['city'] = $city;

            // Add the browser
            $data['browser'] = $ua->browser->name ?? null;

            // Add the OS
            $data['platform'] = $ua->os->name ?? null;

            // Add the device
            $data['device'] = $ua->device->type ?? null;

            // Add the language
            $data['language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;

            // Add the click
            $data['clicks'] = $date;

            // Add the click by hour
            $data['clicks_hours'] = $time;

            // Add the referrer
            $data['referrer'] = $referrer;

            foreach ($data as $name => $value) {
                $values[] = "({$link->id}, '{$name}', " . DB::connection()->getPdo()->quote(mb_substr($value, 0, 255)) . ", '{$date}')";
            }

            $values = implode(', ', $values);

            // Stats
            DB::statement("INSERT INTO `stats` (`link_id`, `name`, `value`, `date`) VALUES {$values} ON DUPLICATE KEY UPDATE `count` = `count` + 1;");

            // Increase the total click count
            Link::where('id', $link->id)->increment('clicks', 1);

            // The default URL to redirect to
            $url = $link->url;

            // If the target type is Geographic
            if ($link->target_type == 1 && $link->country_target !== null) {
                // Redirect the user based on his location
                if ($link->country_target) {
                    foreach ($link->country_target as $country) {
                        if ($countryCode == $country->key) {
                            $url = $country->value;
                        }
                    }
                }
            }

            // If the target type is Platform
            if ($link->target_type == 2 && $link->platform_target !== null) {
                // Redirect the user based on the platform he is on
                if ($link->platform_target) {
                    foreach ($link->platform_target as $platform) {
                        if ($data['platform'] == $platform->key) {
                            $url = $platform->value;
                        }
                    }
                }
            }

            // If the target type is Language
            if ($link->target_type == 3 && $link->language_target !== null) {
                // Redirect the user based on the language he is on
                if ($link->language_target) {
                    foreach ($link->language_target as $language) {
                        if ($data['language'] == $language->key) {
                            $url = $language->value;
                        }
                    }
                }
            }

            // If rotation targeting is enabled
            if ($link->target_type == 4 && $link->rotation_target !== null) {
                $totalRotations = count($link->rotation_target);

                $last_rotation = 0;
                // If there are links in the rotation
                // And the total available links is higher than the last rotation id
                if ($totalRotations > 0 && $totalRotations > $link->last_rotation) {
                    // Increase the last id
                    $last_rotation = $link->last_rotation + 1;
                }

                // Update the last rotation id
                Link::where('id', $link->id)->update(['last_rotation' => $last_rotation]);
            }

            // If the target type is Link Rotation
            if ($link->target_type == 4 && $link->rotation_target !== null) {
                if (isset($link->rotation_target[$link->last_rotation])) {
                    $url = $link->rotation_target[$link->last_rotation]->value;
                }
            }

            // If the link has pixel tracking
            if (count($link->pixels) > 0) {
                // If the user approved consent
                if($request->cookie('consent' . $link->id) == 1) {
                    return view('redirect.redirect', ['link' => $link, 'url' => $url]);
                }
            }

            return redirect()->to($this->urlParamsForward($url), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        // If the request comes from a remote source
        if ($local != $remote) {
            // Get the remote domain
            $domain = Domain::where('name', '=', $remote)->first();

            // If the domain exists
            if ($domain) {
                // If the domain has a 404 page defined
                if ($domain->not_found_page) {
                    return redirect()->to($domain->not_found_page, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }
            }
        }

        abort(404);
    }

    /**
     * Validate the link's password
     *
     * @param ValidateLinkPasswordRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(ValidateLinkPasswordRequest $request, $id)
    {
        session()->flash('verified_link', $id);
        return redirect()->back();
    }

    /**
     * Validate the link's password
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateConsent(Request $request, $id)
    {
        return redirect()->back()->withCookie('consent' . $id, $request->input('consent') ? 1 : 0, (60 * 24 * 30))->with('verified_link', $id);
    }

    /**
     * Format an URL to append additional parameters
     *
     * @param $url
     * @return string
     */
    private function urlParamsForward($url)
    {
        $forwardParams = request()->all();

        // If additional parameters are present
        if ($forwardParams) {
            $urlParts = parse_url($url);

            // Explode the original parameters
            parse_str($urlParts['query'] ?? '', $originalParams);

            // Override and merge the original parameters with the new ones
            $parsedParams = array_merge($originalParams, $forwardParams);

            // Build the URL
            $url = $urlParts['scheme'] . '://' . $urlParts['host'] . ($urlParts['path'] ?? '/') . '?' . http_build_query($parsedParams);

            return $url;
        }

        return $url;
    }
}