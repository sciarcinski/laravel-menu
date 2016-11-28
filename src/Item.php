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
    
    protected $action;
    
    protected $parameters;
    
    protected $url;
    
    protected $icon_left;
    
    protected $icon_right;
    
    protected $class = [];
    
    protected $children;
    
    protected $active = false;
    
    protected $it_child;
    
    protected $active_is_route;
    
    protected $active_is_request;
    
    protected $not_active_is_route;
    
    protected $not_active_is_request;
    
    /**
     * @param Service $service
     * @param bool $it_child
     */
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
        $this->title = $title;
        $this->icon_left = $icon_left;
        
        $this->setRoute($route);
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
     * @param $route
     * @param $parameters
     */
    public function setRoute($route, $parameters = [])
    {
        $this->route = $route;
        $this->parameters = $parameters;
        
        if (!is_null($this->route) && !empty($this->route)) {
            $this->url = route($this->route, $parameters);
        }
    }
    
    /**
     * @param $action
     * @param $parameters
     */
    public function setAction($action, $parameters = [])
    {
        $this->action = $action;
        $this->parameters = $parameters;
        
        if (!is_null($this->action) && !empty($this->action)) {
            $this->url = action($this->action, $parameters);
        }
    }
    
    /**
     * Set active
     *
     * @param $bool
     */
    public function setActive(bool $bool)
    {
        $this->active = $bool;
        
        $this->active ?
            $this->addClass('active') :
            $this->removeClass('active');
    }
    
    /**
     * @return string
     */
    public function getIconLeft()
    {
        $default = $this->it_child ?
                $this->service->icon_child_left :
                $this->service->icon_parent_left;
        
        return $this->getIcon($this->icon_left, $default, 'left');
    }
    
    /**
     * @return string
     */
    public function getIconRight()
    {
        if ($this->hasChildren()) {
            return $this->getIcon($this->icon_right, $this->service->icon_parent_right, 'right');
        }
    }
    
    /**
     * @param $icon
     * @param $default
     * @param $type
     *
     * @return string|null
     */
    protected function getIcon($icon, $default, $type)
    {
        $method = 'getIcon' . ucfirst($type);
        
        if (!is_null($icon) && !empty($icon)) {
            return $this->service->$method($icon, $type);
        }
        
        if (!is_null($default) && !empty($default)) {
            return $this->service->$method($default, $type);
        }
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
        return (is_null($this->url) || empty($this->url)) ? $this->service->default_url : $this->url;
    }
    
    /**
     * Has children
     *
     * @return bool
     */
    public function hasChildren()
    {
        return (!is_null($this->children) && !empty($this->children));
    }
    
    /**
     * Has item active
     *
     * @return bool
     */
    public function hasActive()
    {
        return $this->active;
    }
    
    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    
    /**
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
