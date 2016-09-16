<?php

namespace Sciarcinski\LaravelMenu;

use Sciarcinski\LaravelMenu\Item;

class Builder
{
    /** @var Item */
    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }
    
    /**
     * @param $title
     * @param $route
     * @param $icon_left
     * @param $class
     * @return $this
     */
    public function add($title, $route = null, $icon_left = null, $class = null)
    {
        $child = new Item($this->item->service(), true);
        
        $this->item->addChild($child);
        
        return $child->add($title, $route, $icon_left, $class);
    }
    
    /**
     * @param $title
     * @return $this
     */
    public function title($title)
    {
        $this->item->setTitle($title);

        return $this;
    }

    /**
     * @param $route
     * @param $parameters
     *
     * @return $this
     */
    public function route($route, $parameters = [])
    {
        $this->item->setRoute($route, $parameters);
        
        return $this;
    }
    
    /**
     * @param $action
     * @param $parameters
     *
     * @return $this
     */
    public function action($action, $parameters = [])
    {
        $this->item->setAction($action, $parameters);
        
        return $this;
    }
    
    /**
     * @param $url
     * @return $this
     */
    public function url($url)
    {
        $this->item->setUrl($url);
    }

    /**
     * @param $class
     * @return $this
     */
    public function classAdd($class)
    {
        $this->item->addClass($class);

        return $this;
    }
    
    /**
     * Remove class
     *
     * @param $class
     */
    public function classRemove($class)
    {
        $this->item->removeClass($class);

        return $this;
    }

    /**
     * @param $icon
     * @return $this
     */
    public function iconLeft($icon)
    {
        $this->item->icon_left = $icon;

        return $this;
    }
    
    /**
     * @param $icon
     * @return $this
     */
    public function iconRight($icon)
    {
        $this->item->icon_right = $icon;

        return $this;
    }
    
    /**
     * @param $routes
     * @return $this
     */
    public function activeIsRoute($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();
        
        $this->item->active_is_route = $routes;
        
        return $this;
    }
    
    /**
     * @param $request
     * @return $this
     */
    public function activeIsRequest($request)
    {
        $request = is_array($request) ? $request : func_get_args();
        
        $this->item->active_is_request = $request;
        
        return $this;
    }
    
    /**
     * @param $routes
     * @return $this
     */
    public function notActiveIsRoute($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();
        
        $this->item->not_active_is_route = $routes;
        
        return $this;
    }
    
    /**
     * @param $request
     * @return $this
     */
    public function notActiveIsRequest($request)
    {
        $request = is_array($request) ? $request : func_get_args();
        
        $this->item->not_active_is_request = $request;
        
        return $this;
    }
}
