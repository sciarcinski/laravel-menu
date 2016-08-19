<?php

namespace Sciarcinski\LaravelMenu;

use Illuminate\Foundation\Application;
use Sciarcinski\LaravelMenu\Services\Menu as MenuService;

class Menu
{
    /** @var MenuService */
    protected $service;
    
    /** @var Application */
    protected $app;
    
    protected $instance = [];

    /**
     * @param Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    /**
     * @param $menu
     */
    protected function getInstance($menu)
    {
        $this->service = $this->instance[$menu];
    }

    /**
     * Get
     * 
     * @param $menu
     * 
     * @return $this
     */
    public function get($menu)
    {
        $menu = '\\App\\Menus\\'.studly_case($menu);
        
        if ($this->hasInstance($menu)) {
            $this->getInstance($menu);
        } else {
            $this->getMenu($menu);
            $this->detectActive();
            $this->pullInstance($menu);
        }
        
        return $this;
    }
    
    /**
     * @param $items
     * @return string
     */
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
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * @param $parent
     * @return string
     */
    public function breadcrumb($parent_url = null, $parent_title = null)
    {
        $breadcrumb = new Breadcrumb($parent_url, $parent_title);
        
        return $breadcrumb->render($this->service->get());
    }

    /**
     * @param $menu
     * @return MenuService
     */
    protected function getMenu($menu)
    {
        $this->service = new $menu();
        $this->service->items();
        
        return $this->service;
    }
    
    /**
     * Detect active
     */
    protected function detectActive()
    {
        $active = new Active();
        $active->detect($this->service->get());
        $active->detectParent($this->service->get());
    }
    
    /**
     * @param $menu
     * @return bool
     */
    protected function hasInstance($menu)
    {
        return array_has($this->instance, $menu);
    }
    
    /**
     * @param $menu
     */
    protected function pullInstance($menu)
    {
        $this->instance[$menu] = $this->service;
    }
}
