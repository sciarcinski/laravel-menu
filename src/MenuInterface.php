<?php

namespace Sciarcinski\LaravelMenu;

interface MenuInterface
{
    public function get();
    
    /**
     * @param $title
     * @param $route
     * @param $icon_left
     * @param $class
     *
     * @return \Sciarcinski\LaravelMenu\Builder
     */
    public function add($title, $route, $icon_left, $class);
    
    public function items();
    
    public function getIconLeft($icon);
    
    public function getIconRight($icon);
    
    public function hasModel();
}
