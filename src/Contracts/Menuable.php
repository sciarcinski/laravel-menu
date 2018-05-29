<?php

namespace Sciarcinski\LaravelMenu\Contracts;

interface Menuable
{
    /**
     * @return array
     */
    public function get();
    
    /**
     * @param $title
     * @return \Sciarcinski\LaravelMenu\Item
     */
    public function add($title);

    /**
     * @return void
     */
    public function items();

    /**
     * @return string
     */
    public function defaultUrl();

    /**
     * @return string
     */
    public function activeItemClassName();

    /**
     * @return string
     */
    public function activeLinkClassName();

    /**
     * @param array|null $items
     * @return string
     */
    public function render($items = null);

    /**
     * @param string $parentUrl
     * @param string $parentTitle
     * @return string
     */
    public function breadcrumb($parentUrl = null, $parentTitle = null);
}
