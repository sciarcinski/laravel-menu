<?php

namespace Sciarcinski\LaravelMenu\Services;

use Sciarcinski\LaravelMenu\Contracts\Menuable as MenuableContract;
use Sciarcinski\LaravelMenu\Breadcrumb;
use Sciarcinski\LaravelMenu\Item;

abstract class Menu implements MenuableContract
{
    /** @var array */
    protected $items = [];
    
    /** @var array */
    protected $itemAttributes = [];
    
    /** @var array */
    protected $linkAttributes = [];
    
    /** @var mixed */
    protected $model;
    
    /**
     * @param mixed $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->items;
    }
    
    /**
     * @return array
     */
    public function getItemAttributes()
    {
        return $this->itemAttributes;
    }
    
    /**
     * @return array
     */
    public function getLinkAttributes()
    {
        return $this->linkAttributes;
    }

    /**
     * @param string $title
     * @return Item
     */
    public function add($title)
    {
        $this->items[] = $item = new Item($this);        
        return $item->title($title);
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
    public function activeItemClassName()
    {
        return 'active';
    }

    /**
     * @return string
     */
    public function activeLinkClassName()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasModel()
    {
        return !is_null($this->model);
    }

    /**
     * @param array|null $items
     * @return string
     */
    public function render($items = null)
    {
        $html = '';

        /* @var $item Item */
        foreach (is_null($items) ? $this->get() : $items as $item) {
            $html .= '<li '.$item->getItemAttributes().'>';
            $html .= $this->link($item);

            if ($item->hasChildren()) {
                $html .= $this->children($item);
            }

            $html .= '</li>';
        }

        return $html;
    }

    /**
     * @param Item $item
     * @return string
     */
    protected function link(Item $item)
    {
        $html = '<a href="'.$item->getUrl().'" '.$item->getLinkAttributes().'>';
        $html .= $item->getBefore().'<span>'.$item->getTitle().'</span>'.$item->getAfter();
        $html .= '</a>';

        return $html;
    }

    /**
     * @param Item $item
     * @return string
     */
    protected function children(Item $item)
    {
        $html = '<ul>';
        $html .= $this->render($item->getChildren());
        $html .= '</ul>';

        return $html;
    }

    /**
     * @param string $parentUrl
     * @param string $parentTitle
     * @return string
     */
    public function breadcrumb($parentUrl = null, $parentTitle = null)
    {
        $breadcrumb = new Breadcrumb($parentUrl, $parentTitle);
        
        return $breadcrumb->render($this->get());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
