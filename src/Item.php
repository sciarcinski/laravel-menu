<?php

namespace Sciarcinski\LaravelMenu;

use Sciarcinski\LaravelMenu\Item;
use Sciarcinski\LaravelMenu\Services\Menu as Service;
use Sciarcinski\LaravelMenu\Builder;

class Item
{
    /** @var Service */
    protected $service;

    protected $title;
    protected $route;
    protected $url;
    protected $icon_left;
    protected $icon_right;
    protected $class = [];
    protected $children;
    protected $active = false;
    protected $it_child;

    public function __construct(Service $service, $it_child = false)
    {
        $this->service = $service;
        $this->it_child = $it_child;
    }
    
    /**
     * @return Service
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @param $title
     * @param $route
     * @param $icon_left
     * @param $class
     * @return Builder
     */
    public function add($title, $route = null, $icon_left = null, $class = null)
    {
        $this->setTitle($title);
        $this->setRoute($route);
        $this->setIconLeft($icon_left);
        $this->addClass($class);
        
        return new Builder($this);
    }
    
    /**
     * @param Item $item
     */
    public function addChild(Item $item)
    {
        $this->children[] = $item;
    }

    /**
     * @param $class
     */
    protected function addClass($class)
    {
        if (!is_null($class) && !in_array($class, $this->class)) {
            $this->class[] = $class;
        }
    }
    
    /**
     * Remove class
     * 
     * @param $class
     */
    public function removeClass($class)
    {
        if ($key = array_search($class, $this->class) !== false) {
            unset($this->class[$key]);
        }
    }
    
    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * @param $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
        
        if (!is_null($this->route) && !empty($this->route)) {
            $this->setUrl(route($this->route));
        }
    }
    
    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param $icon
     */
    public function setIconLeft($icon)
    {
        $this->icon_left = $icon;
    }
    
    /**
     * @param $icon
     */
    public function setIconRight($icon)
    {
        $this->icon_right = $icon;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * @return string
     */
    public function getIconLeft()
    {
        $default = $this->it_child ?
                $this->service->iconChildLeft() :
                $this->service->iconParentLeft();
        
        return $this->getIcon($this->icon_left, $default, 'left');
    }
    
    /**
     * @return string
     */
    public function getIconRight()
    {
        if ($this->hasChildren()) {
            return $this->getIcon($this->icon_right, $this->service->iconParentRight(), 'right');
        }
    }
    
    /**
     * @param $icon
     * @param $default
     * @param $class
     * @return string|null
     */
    protected function getIcon($icon, $default, $type)
    {
        if (!is_null($icon) && !empty($icon)) {
            return $this->service->getIcon($icon, $type);
        }
        
        if (!is_null($default) && !empty($default)) {
            return $this->service->getIcon($default, $type);
        }
    }

    /**
     * @return array|null
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @return string
     */
    public function getClass()
    {
        return implode(' ', array_filter($this->class));
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return (is_null($this->url) || empty($this->url)) ? $this->service->defaultUrl() : $this->url;
    }

    /**
     * Is route
     * 
     * @param $route
     * 
     * @return bool
     */
    public function isRoute($route)
    {
        return $this->route === $route;
    }
    
    /**
     * Set active
     * 
     * @param $bool
     */
    public function active(bool $bool)
    {
        $this->active = $bool;
        $this->addClass('active');
    }
    
    /**
     * Has children
     * 
     * @return bool
     */
    public function hasChildren()
    {
        return (!is_null($this->children) || !empty($this->children));
    }
}
