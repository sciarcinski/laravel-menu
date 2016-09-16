<?php

namespace Sciarcinski\LaravelMenu;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sciarcinski\LaravelMenu\Item;

class Active
{
    protected $active_parent;
    
    /** @var \Illuminate\Routing\Route */
    protected $route;
    
    /** @var \Illuminate\Http\Request */
    protected $request;

    public function __construct()
    {
        $this->route = Route::getFacadeRoot()->current();
        $this->request = Route::getFacadeRoot()->getCurrentRequest();
    }

    /**
     * Detect active
     *
     * @param $items
     * @param $active
     */
    public function detect($items, $active = [])
    {
        foreach ($items as $key => $item) {
            if (!$item->it_child) {
                $active = [];
            }
            
            if ($this->isActive($item)) {
                $this->active_parent = $active;
                $item->setActive(true);
            }
            
            if ($item->hasChildren()) {
                $active[$key] = $key;
                $this->detect($item->children, $active);
            }
        }
    }
    
    /**
     * Detect active parent
     *
     * @param $items
     * @param $parent
     */
    public function detectParent($items, $parent = null)
    {
        if (!empty($this->active_parent)) {
            $parent = is_null($parent) ? $this->active_parent : $parent;

            $item = array_get($items, array_shift($parent));
            $item->setActive(true);

            if (!empty($parent)) {
                $this->detectParent($item->children, $parent);
            }
        }
    }
    
    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function isActive(Item $item)
    {
        if ($this->notIsRouteActive($item) || $this->notIsRequestActive($item)) {
            return false;
        }
        
        if ($this->isRouteActive($item) || $this->isRequestActive($item)) {
            return true;
        }
        
        if (is_null($item->active_is_route) && is_null($item->active_is_request)) {
            return false;
        }
        
        return false;
    }
    
    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function notIsRouteActive(Item $item)
    {
        if (is_null($item->not_active_is_route)) {
            return false;
        }
        
        return $this->routeInArray($this->route->getName(), $item->not_active_is_route);
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function isRouteActive(Item $item)
    {
        if ($this->route->getName() === $item->route) {
            return true;
        }
        
        if ($this->routeInArray($item->route, $item->active_is_route)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param $item_route
     * @param $routes
     *
     * @return bool
     */
    protected function routeInArray($item_route, $routes)
    {
        return (is_array($routes) && in_array($item_route, $routes));
    }
    
    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function notIsRequestActive(Item $item)
    {
        if (is_null($item->not_active_is_request)) {
            return false;
        }
        
        return $this->requestIs($item->not_active_is_request) ? false : true;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function isRequestActive(Item $item)
    {
        if (! is_array($item->active_is_request)) {
            return false;
        }
        
        return $this->requestIs($item->active_is_request);
    }
    
    /**
     * @param $requests
     *
     * @return bool
     */
    protected function requestIs($requests)
    {
        foreach ($requests as $pattern) {
            if (Str::is($pattern, urldecode($this->request->path()))) {
                return true;
            }
        }
        
        return false;
    }
}
