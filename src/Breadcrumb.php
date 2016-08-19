<?php

namespace Sciarcinski\LaravelMenu;

use Sciarcinski\LaravelMenu\Item;

class Breadcrumb
{
    protected $parent_url;
    protected $parent_title;
    protected $breadcrumb = [];

    /**
     * @param $parent_url
     * @param $parent_title
     */
    public function __construct($parent_url, $parent_title)
    {
        $this->parent_url = $parent_url;
        $this->parent_title = $parent_title;
    }
    
    /**
     * @param $menu_items
     * @return string
     */
    public function render($menu_items)
    {
        if (is_array($menu_items)) {
            $item = array_first($menu_items, function ($key, $item) {
                return $item->hasActive();
            });
            
            $this->add($item);
            $this->children($item);
        }
        
        return $this->getHtml();
    }
    
    /**
     * @return string
     */
    protected function getHtml()
    {
        $html = $this->getItemList($this->parent_url, $this->parent_title);
        
        foreach ($this->breadcrumb as $breadcrumb) {
            if ($this->parent_url !== $breadcrumb->url) {
                $html .= $this->getItemList($breadcrumb->url, $breadcrumb->title);
            }
        }
        
        return $html;
    }
    
    /**
     * @param $url
     * @param $title
     * @return string
     */
    protected function getItemList($url, $title)
    {
        if (!is_null($url) && !is_null($title)) {
            return '<li><a href="'.$url.'">'.$title.'</a></li>';
        }
    }

    /**
     * Add item the breadcrumb
     * 
     * @param Item $item
     */
    protected function add($item)
    {
        if ($item instanceof Item) {
            $this->breadcrumb[] = (object)[
                'url' => $item->getUrl(),
                'title' => $item->getTitle(),
            ];
        }
    }
    
    /**
     * Find active item in children
     * 
     * @param Item $item
     */
    protected function children($item)
    {
        if ($item instanceof Item && $item->hasChildren()) {
            $this->render($item->getChildren());
        }
    }
}
