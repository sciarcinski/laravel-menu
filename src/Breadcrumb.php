<?php

namespace Sciarcinski\LaravelMenu;

class Breadcrumb
{
    /** @var array */
    protected $parent = [];

    /** @var array */
    protected $items = [];

    /** @var array */
    protected $breadcrumb = [];

    /**
     * @return array
     */
    public function get()
    {
        if (!empty($this->parent)) {
            $this->addItem($this->parent['title'], $this->parent['url']);
        }

        $this->findItems($this->items);
        $this->activeLastElement();

        return $this->breadcrumb;
    }

    /**
     * @param string $title
     * @param string $url
     * @return $this
     */
    public function parent($title, $url)
    {
        if (!is_null($title) && !is_null($url)) {
            $this->parent = [
                'title' => $title,
                'url' => $url
            ];
        }
        
        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function items(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $items = $this->get();

        $html = '';

        foreach ($items as $item) {
            $html .='<li class="breadcrumb-item">';

            if ($item['active']) {
                $html .= $item['title'];
            } else {
                $html .= '<a href="'.$item['url'].'">'.$item['title'].'</a>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    /**
     * @param array $items
     */
    protected function findItems(array $items)
    {
        /* @var $item Item */
        foreach ($items as $item) {
            if ($item->isActive()) {
                $this->addItem($item->getTitle(), $item->getUrl());
                $this->findItems($item->getChildren());
                break;
            }
        }
    }

    /**
     * @param string $title
     * @param string $url
     */
    protected function addItem($title, $url)
    {
        $this->breadcrumb[] = [
            'title' => $title,
            'url' => $url,
            'active' => false
        ];
    }

    /**
     * @return void
     */
    protected function activeLastElement()
    {
        $min = empty($this->parent) ? 0 : 1;
        $count = count($this->breadcrumb);

        if ($count <= $min) {
            return;
        }

        $this->breadcrumb[$count - 1]['active'] = true;
    }
}
