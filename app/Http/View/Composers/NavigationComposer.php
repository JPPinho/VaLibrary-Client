<?php


namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NavigationComposer
{
    public function compose(View $view)
    {
        $rawMenu = config('navigation', []);

        $filteredMenu = $this->filterMenu($rawMenu);
        $view->with('mainNav', $filteredMenu);
    }

    /**
     * Recursively filters a menu array based on the authenticated user's role.
     *
     * @param array $menu
     * @return array
     */
    private function filterMenu(array $menu): array
    {
        return collect($menu)->filter(function ($item) {

            return !isset($item['role']) || empty($item['role']) || (Auth::check() && Auth::user()->role === $item['role']);
        })->map(function ($item) {
            if (!empty($item['children'])) {
                $item['children'] = $this->filterMenu($item['children']);
            }
            return $item;
        })->all();
    }
}
