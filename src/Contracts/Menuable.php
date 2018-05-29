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
     * @return array
     */
    public function itemAttributes();

    /**
     * @return array
     */
    public function linkAttributes();

    /**
     * @param array|null $items
     * @return string
     */
    public function render($items = null);

    /**
     * @param string $parentTitle
     * @param string $parentUrl
     * @return string
     */
    public function breadcrumb($parentTitle = null, $parentUrl = null);
}
