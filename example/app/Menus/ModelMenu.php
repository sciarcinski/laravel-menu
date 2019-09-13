<?php

namespace App\Menus;

use Sciarcinski\LaravelMenu\Services\Menu;

class ModelMenu extends Menu
{
    /**
     * Menu items.
     */
    public function items()
    {
        if ($this->hasModel()) {
            $this->add('Model show')->route('model.show', $this->model);
            $this->add('Model edit')->route('model.edit', $this->model);
        }
    }
}
