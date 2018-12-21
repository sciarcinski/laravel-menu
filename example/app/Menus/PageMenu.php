<?php

namespace App\Menus;

use Sciarcinski\LaravelMenu\Services\Menu;

class PageMenu extends Menu
{
    /** @var array */
    protected $itemAttributes = [
        'class' => ['app-menu__item'],
    ];

    /** @var array */
    protected $linkAttributes = [
        'class' => ['app-menu__item'],
    ];

    /**
     * Route::get('page/{slug}', 'PageController@show')->name('page.show');
     */
    public function items()
    {
        $this->add('Home')->route('page.show', 'home');

        $about = $this->add('About')->route('page.show', 'about');
        $about->add('Level 2.1')->route('page.show', 'level-2.1');
        $about->add('Level 2.2')->route('page.show', 'level-2.2');
        $about->add('Level 2.3')->route('page.show', 'level-2.3');

        $about_level_3 = $about->add('Level 3')->route('page.show', 'level-3');
        $about_level_3->add('Level 3.1')->route('page.show', 'level-3.1');
        $about_level_3->add('Level 3.2')->route('page.show', 'level-3.2');

        $this->add('Contact')->route('page.show', 'contact');
    }

    /**
     * @return string
     */
    public function itemChildrenClassName()
    {
        return 'treeview';
    }

    /**
     * @return string
     */
    public function childrenClassName()
    {
        return 'treeview-menu';
    }

    /**
     * @param Item $item
     * @return string
     */
    public function linkTitile(Item $item)
    {
        $before = $item->getBefore();

        if (!empty($before)) {
            $before = '<i class="app-menu__icon ' . $before . '"></i>';
        }

        $after = $item->getAfter();

        if ($item->hasChildren()) {
            $after .= ' <i class="treeview-indicator fa fa-angle-right"></i>';
        }

        return $before . '<span class="app-menu__label">' . $item->getTitle() . '</span>' . $after;
    }
}
