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
            if (!$item->itChild()) {
                $active = [];
            }
            
            if ($this->isActive($item)) {
                $this->active_parent = $active;
                $item->setActive(true);
            }
            
            if ($item->hasChildren()) {
                $active[$key] = $key;
                $this->detect($item->getChildren(), $active);
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
                $this->detectParent($item->getChildren(), $parent);
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
        if ($this->isRouteActive($item)) {
            return true;
        }
        
        if ($this->isRequestActive($item)) {
            return true;
        }
        
        if (is_null($item->active_if_route) && is_null($item->active_if_request)) {
            return false;
        }
        
        return false;
    }

    /**
     * @param Item $item
     * 
     * @return bool
     */
    protected function isRouteActive(Item $item)
    {
        if ($this->route->getName() === $item->getRoute()) {
            return true;
        }
        
        if (is_array($item->active_if_route) && in_array($item->getRoute(), $item->active_if_route)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param Item $item
     * 
     * @return bool
     */
    protected function isRequestActive(Item $item)
    {
        if (! is_array($item->active_if_request)) {
            return false;
        }
        
        foreach ($item->active_if_request as $pattern) {
            if (Str::is($pattern, urldecode($this->request->path()))) {
                return true;
            }
        }
        
        return false;
    }
}
