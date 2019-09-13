<?php

namespace Sciarcinski\LaravelMenu;

use Sciarcinski\LaravelMenu\Services\Menu as Service;

class Item
{
    /** @var Service */
    protected $service;

    /** @var Item|null */
    protected $parent;

    /** @var string */
    protected $title;

    /** @var string */
    protected $route;

    /** @var string */
    protected $action;

    /** @var array */
    protected $parameters = [];

    /** @var string */
    protected $url;

    /** @var array */
    protected $children = [];

    /** @var array */
    protected $itemAttributes = [];

    /** @var array */
    protected $linkAttributes = [];

    /** @var string */
    protected $before;

    /** @var string */
    protected $after;

    /** @var bool */
    protected $active = false;

    /** @var array */
    protected $activateRoutes = [];

    /** @var array */
    protected $activatePaths = [];

    /** @var array */
    protected $notActivateRoutes = [];

    /** @var array */
    protected $notActivatePaths = [];

    /**
     * @param Service $service
     * @param Item|null $parent
     * @param bool child
     */
    public function __construct(Service $service, $parent = null)
    {
        $this->service = $service;
        $this->parent = $parent;
        $this->setItemAttributes($service->getItemAttributes());
        $this->setLinkAttributes($service->getLinkAttributes());
    }

    /**
     * @return Service
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setItemId($id)
    {
        $this->itemAttributes['id'] = $id;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setItemClass($class)
    {
        if (! array_key_exists('class', $this->itemAttributes)) {
            $this->itemAttributes['class'] = [];
        }

        $this->itemAttributes['class'] = array_unique(array_merge($this->itemAttributes['class'], explode(' ', $class)));

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setLinkClass($class)
    {
        if (! array_key_exists('class', $this->linkAttributes)) {
            $this->linkAttributes['class'] = [];
        }

        $this->linkAttributes['class'] = array_unique(array_merge($this->linkAttributes['class'], explode(' ', $class)));

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setItemAttributes(array $attributes)
    {
        $this->itemAttributes = array_merge($this->itemAttributes, $attributes);

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setLinkAttributes(array $attributes)
    {
        $this->linkAttributes = array_merge($this->linkAttributes, $attributes);

        return $this;
    }

    /**
     * @return $this|null
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return array
     */
    public function getActivateRoutes()
    {
        return $this->activateRoutes;
    }

    /**
     * @return array
     */
    public function getActivatePaths()
    {
        return $this->activatePaths;
    }

    /**
     * @return array
     */
    public function getNotActivateRoutes()
    {
        return $this->notActivateRoutes;
    }

    /**
     * @return array
     */
    public function getNotActivatePaths()
    {
        return $this->notActivatePaths;
    }

    /**
     * @return string
     */
    public function getItemAttributes()
    {
        return $this->getAttributes($this->itemAttributes);
    }

    /**
     * @return string
     */
    public function getLinkAttributes()
    {
        return $this->getAttributes($this->linkAttributes);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    protected function getAttributes(array $attributes)
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $html .= $key . '="' . $value . '" ';
        }

        return $html;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return (is_null($this->url) || empty($this->url)) ? $this->service->defaultUrl() : $this->url;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @return string
     */
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function add($title)
    {
        $this->children[] = $item = new static($this->service, $this);
        $item->title($title);

        return $item;
    }

    /**
     * @return $this
     */
    public function activate()
    {
        $this->active = true;
        $this->setItemClass($this->service()->activeItemClassName());
        $this->setLinkClass($this->service()->activeLinkClassName());

        if ($this->parent()) {
            $this->parent()->activate();
        }

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param string $route
     * @param array $parameters
     *
     * @return $this
     */
    public function route($route, array $parameters = [])
    {
        $this->route = $route;
        $this->parameters = $parameters;
        $this->url(route($this->route, $parameters));

        return $this;
    }

    /**
     * @param string $action
     * @param array $parameters
     *
     * @return $this
     */
    public function action($action, array $parameters = [])
    {
        $this->action = $action;
        $this->parameters = $parameters;
        $this->url(action($this->action, $parameters));

        return $this;
    }

    /**
     * @param string $before
     *
     * @return $this
     */
    public function before($before)
    {
        $this->before = $before;

        return $this;
    }

    /**
     * @param string $after
     *
     * @return $this
     */
    public function after($after)
    {
        $this->after = $after;

        return $this;
    }

    /**
     * @param mixed $routes
     *
     * @return $this
     */
    public function activateForRoutes($routes)
    {
        $items = is_array($routes) ? $routes : func_get_args();

        $this->activateRoutes = array_merge($this->activateRoutes, $items);
        $this->removeItems($items, $this->notActivateRoutes);

        return $this;
    }

    /**
     * @param mixed $paths
     *
     * @return $this
     */
    public function activateForPaths($paths)
    {
        $items = is_array($paths) ? $paths : func_get_args();

        $this->activatePaths = array_merge($this->activatePaths, $items);
        $this->removeItems($items, $this->notActivatePaths);

        return $this;
    }

    /**
     * @param mixed $routes
     *
     * @return $this
     */
    public function notActivateForRoutes($routes)
    {
        $items = is_array($routes) ? $routes : func_get_args();

        $this->notActivateRoutes = array_merge($this->notActivateRoutes, $items);
        $this->removeItems($items, $this->activateRoutes);

        return $this;
    }

    /**
     * @param mixed $paths
     *
     * @return $this
     */
    public function notActivateForPaths($paths)
    {
        $items = is_array($paths) ? $paths : func_get_args();

        $this->notActivatePaths = array_merge($this->notActivatePaths, $items);
        $this->removeItems($items, $this->activatePaths);

        return $this;
    }

    /**
     * @return bool
     */
    public function isNotActivateEmpty()
    {
        return empty($this->notActivateRoutes) && empty($this->notActivatePaths);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return ! empty($this->children);
    }

    /**
     * @return bool
     */
    public function hasActive()
    {
        return $this->active;
    }

    /**
     * @param array $remove
     * @param array $items
     */
    protected function removeItems(array $remove, array &$items)
    {
        foreach ($items as $key => $item) {
            if (in_array($item, $remove)) {
                unset($items[$key]);
            }
        }
    }
}
