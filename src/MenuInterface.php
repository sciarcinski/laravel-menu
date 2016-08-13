<?php

namespace Sciarcinski\LaravelMenu;

interface MenuInterface
{
    public function get();
    
    public function add($title, $route, $icon_left, $class);
    
    public function items();
    
    public function defaultUrl();
    
    public function iconParentLeft();
    
    public function iconParentRight();
    
    public function iconChildLeft();
    
    public function getIcon($icon, $type);
}
