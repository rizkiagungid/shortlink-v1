<?php


namespace App\Http\View\Composers;

use App\Models\Link;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserStatsComposer
{
    /**
     * @var
     */
    private $linksCount;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$this->linksCount) {
                $this->linksCount = Link::where('user_id', $user->id)->count();
            }

            $view->with('linksCount', $this->linksCount);
        }
    }
}