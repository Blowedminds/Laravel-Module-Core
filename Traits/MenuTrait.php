<?php


namespace App\Modules\Core\Traits;


use App\Modules\Core\Menu;

trait MenuTrait
{
    public function getMenus($locale)
    {
        $language_slug = $locale;

        $menus = Menu::whereHas('roles', function ($q) {
            $q->where('slug', 'guest');
        })
            ->orderBy('weight', 'DESC')
            ->get()
            ->map(function ($menu) use ($language_slug) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name[$language_slug] ?? '',
                    'tooltip' => $menu->tooltip[$language_slug] ?? '',
                    'url' => $menu->url,
                    'weight' => $menu->weight,
                    'parent' => $menu->parent,
                    'children' => []
                ];
            })->toArray();

        for ($i = 0, $count = count($menus); $i < $count; $i++) {

            $menu = array_pop($menus);

            $placed = false;

            foreach ($menus as $key => $target) {
                if ($this->recurseMenus($menus[$key], $menu)) {
                    $placed = true;
                    break;
                }
            }

            if (!$placed) {
                array_unshift($menus, $menu);
            }
        }

        usort($menus, function ($a, $b) {
            return $a['weight'] - $b['weight'];
        });

        return $menus;
    }

    private function recurseMenus(&$target, &$menu)
    {
        if ($menu['parent'] === $target['id']) {
            $target['children'][] = $menu;
            return true;
        }

        foreach ($target['children'] as $key => $child) {
            if ($this->recurseMenus($target['children'][$key], $menu)) {
                return true;
            };
        }

        return false;
    }
}
