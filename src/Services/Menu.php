<?php

namespace Sciarcinski\LaravelMenu\Services;

use Illuminate\Http\Request;
use Sciarcinski\LaravelMenu\Item;
use Sciarcinski\LaravelMenu\MenuInterface;
use Sciarcinski\LaravelMenu\Builder;

abstract class Menu implements MenuInterface
{
    protected $items = [];
    
    /** @var mixed */
    protected $model;
    
    /** @var Request */
    protected $request;
    
    public $default_url = 'javascript:;';
    
    public $icon_parent_left = 'fa-angle-double-right';
    
    public $icon_parent_right = 'fa-angle-left';
    
    public $icon_child_left = '';

    public $tree_class = 'nav-second-level nav';

    /**
     * @param mixed $model
     * @param Request $request
     */
    public function __construct($model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Get items
     *
     * @return array
     */
    public function get()
    {
        return $this->items;
    }
    
    /**
     * Add item
     *
     * @param $title
     * @param $route
     * @param $icon_left
     * @param $class
     * @return Builder
     */
    public function add($title, $route = null, $icon_left = null, $class = null)
    {
        $item = new Item($this);
        $this->items[] = $item;
        
        return $item->add($title, $route, $icon_left, $class);
    }
    
    /**
     * @param $icon
     *
     * @return string
     */
    public function getIconLeft($icon)
    {
        return '<i class="fa '.$icon.'"></i>';
    }
    
    /**
     * @param $icon
     *
     * @return string
     */
    public function getIconRight($icon)
    {
        return '<span class="pull-right-container"><i class="fa '.$icon.' pull-right"></i></span>';
    }
    
    /**
     * @return bool
     */
    public function hasModel()
    {
        return !is_null($this->model);
    }
}
