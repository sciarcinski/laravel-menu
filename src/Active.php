<?php

namespace Sciarcinski\LaravelMenu;

use Illuminate\Support\Facades\Route;

class Active
{
    protected $active_parent;
    protected $route_name;
    
    public function __construct()
    {
        $this->route_name = Route::getFacadeRoot()->current()->getName();
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
            if ($item->isRouteActive($this->route_name)) {
                $this->active_parent = $active;
                $item->active(true);
            }
            
            if ($item->hasChildren()) {
                $active[] = $key;
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
            $item->active(true);

            if (!empty($parent)) {
                $this->detectParent($item->getChildren(), $parent);
            }
        }
    }
}
