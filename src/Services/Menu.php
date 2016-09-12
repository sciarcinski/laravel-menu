<?php

namespace Sciarcinski\LaravelMenu\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Sciarcinski\LaravelMenu\Item;
use Sciarcinski\LaravelMenu\MenuInterface;
use Sciarcinski\LaravelMenu\Builder;

abstract class Menu implements MenuInterface
{
    protected $items = [];
    
    /** @var Model */
    protected $model;
    
    /** @var Request */
    protected $request;

    /**
     * @param null|Model $model
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
     * @return string
     */
    public function defaultUrl()
    {
        return 'javascript:;';
    }
    
    /**
     * @return string
     */
    public function iconParentLeft()
    {
        return 'fa-angle-double-right';
    }
    
    /**
     * @return string
     */
    public function iconParentRight()
    {
        return 'fa fa-angle-left';
    }
    
    /**
     * @return string
     */
    public function iconChildLeft()
    {
        return 'fa-circle-o';
    }
    
    /**
     * @param $icon
     * @param $type
     * @return string
     */
    public function getIcon($icon, $type)
    {
        switch ($type) {
            case 'left':
                return '<i class="fa '.$icon.'"></i>';
            
            case 'right':
                return '<span class="pull-right-container"><i class="fa '.$icon.' pull-right"></i></span>';
        }
    }
    
    /**
     * @return bool
     */
    public function hasModel()
    {
        return $this->model instanceof Model;
    }
}
