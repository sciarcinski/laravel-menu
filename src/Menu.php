<?php

namespace Sciarcinski\LaravelMenu;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Sciarcinski\LaravelMenu\Contracts\Menuable as MenuableContract;

class Menu
{
    /** @var Request */
    protected $request;

    /** @var MenuableContract */
    protected $current;

    /** @var mixed */
    protected $model;

    /** @var array */
    protected $instance = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $model
     *
     * @return $this
     */
    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param $name
     *
     * @return MenuableContract
     */
    public function get($name)
    {
        $menu = '\\App\\Menus\\' . Str::studly($name);

        if ($this->hasInstance($menu)) {
            $this->setInstance(
                $this->getInstance($menu)
            );
        } else {
            $this->loadInstance($menu, $this->getModelAndForget());
        }

        return $this->current;
    }

    /**
     * @return mixed
     */
    protected function getModelAndForget()
    {
        if (is_null($this->model)) {
            return;
        }

        $model = clone $this->model;
        $this->model = null;

        return $model;
    }

    /**
     * @param string $menu
     *
     * @return bool
     */
    protected function hasInstance($menu)
    {
        return Arr::has($this->instance, $menu);
    }

    /**
     * @param string $menu
     *
     * @return MenuableContract|null
     */
    protected function getInstance($menu)
    {
        return $this->hasInstance($menu) ? $this->instance[$menu] : null;
    }

    /**
     * @param MenuableContract $menu
     *
     * @return $this
     */
    protected function setInstance(MenuableContract $menu)
    {
        $this->current = $menu;
        $this->instance[get_class($menu)] = $menu;

        return $this;
    }

    /**
     * @param string $name
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @throws Exception
     */
    protected function loadInstance($name, $model)
    {
        try {
            $menu = new $name($model);
            $menu->items();

            $detect = new ActiveDetect($this->request);
            $detect->items($menu->get());

            $this->setInstance($menu);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
