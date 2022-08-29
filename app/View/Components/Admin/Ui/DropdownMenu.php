<?php

namespace App\View\Components\Admin\Ui;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownMenu extends Component
{

    public $name;
    public $icon;
    public $menus;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name,$icon,$menus)
    {
        $this->name=$name;
        $this->icon=$icon;
        $this->menus=\Helper::convertJson($menus);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.admin.ui.dropdown-menu');
    }
}
