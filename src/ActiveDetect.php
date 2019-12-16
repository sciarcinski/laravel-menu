<?php

namespace Sciarcinski\LaravelMenu;

use Illuminate\Http\Request;

class ActiveDetect
{
    /** @var Request */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $items
     */
    public function items(array $items)
    {
        /* @var $item Item */
        foreach ($items as $item) {
            if ($this->isActive($item)) {
                $item->activate();
            }

            if ($item->hasChildren()) {
                $this->items($item->getChildren());
            }
        }
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function isActive(Item $item)
    {
        if ($this->checkNotActive($item)) {
            return false;
        }

        if ($this->checkActive($item)) {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function checkActive(Item $item)
    {
        if (
            $this->checkActiveRoute($item) ||
            $this->checkRoute($item->getActivateRoutes()) ||
            $this->checkPaths($item->getActivatePaths())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function checkActiveRoute(Item $item)
    {
        $route = $this->request->route()->getName() === $item->getRoute();

        if ($route) {
            return empty(array_diff($this->request->route()->parameters(), $item->getParameters()));
        }

        return false;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    protected function checkNotActive(Item $item)
    {
        if ($item->isNotActivateEmpty()) {
            return false;
        }

        return $this->checkRoute($item->getNotActivateRoutes()) || $this->checkPaths($item->getNotActivatePaths());
    }

    /**
     * @param array $items
     *
     * @return bool
     */
    protected function checkRoute(array $items)
    {
        return in_array($this->request->route()->getName(), $items);
    }

    /**
     * @param array $items
     *
     * @return bool
     */
    protected function checkPaths(array $items)
    {
        foreach ($items as $item) {
            if ($this->request->is($item)) {
                return true;
            }
        }

        return false;
    }
}
