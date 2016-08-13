<?php

namespace Sciarcinski\LaravelMenu;

use Sciarcinski\LaravelMenu\Services\Menu as MenuService;

class Menu
{
    /** @var MenuService */
    protected $service;

    /**
     * Get
     * 
     * @param $menu
     * 
     * @return $this
     */
    public function get($menu)
    {
        $this->getMenu($menu);
        $this->detectActive();
        
        return $this;
    }
    
    public function render($items = null)
    {
        $items = is_null($items) ? $this->service->get() : $items;
        
        $html = '';
        
        foreach ($items as $item) {
            $html .= '<li class="'.$item->getClass().'">';
            $html .= '<a href="'.$item->getUrl().'">'.$item->getIconLeft().'<span>'.$item->getTitle().'</span>'.$item->getIconRight().'</a>';
            
            if ($item->hasChildren()) {
                $html .= '<ul class="treeview-menu">';
                $html .= $this->render($item->getChildren());
                $html .= '</ul>';
            }
            
            $html .= '</li>';
        }

        return $html;
    }
    
    /**
     * @param $menu
     * @return MenuService
     */
    protected function getMenu($menu)
    {
        $menu = '\\App\\Menus\\'.studly_case($menu);
        
        $this->service = new $menu();
        $this->service->items();
        
        return $this->service;
    }

    /**
     * Get items
     * 
     * @param $items
     * @return array
     */
    protected function detectActive()
    {
        $active = new Active();
        $active->detect($this->service->get());
        $active->detectParent($this->service->get());
    }
}
