<?php

namespace Sciarcinski\LaravelMenu\Generators;

use Illuminate\Console\GeneratorCommand;

class MenuMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'menu:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Menu service class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Menus';

    /**
     * The model class to be used by menu.
     *
     * @var string
     */
    protected $model;

    /**
     * Menu export filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/menu.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return 'App\\Menus';
    }
}
