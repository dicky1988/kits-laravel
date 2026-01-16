<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleSwitchButton extends Component
{
    public int|string $userId;
    public bool $isActive;
    public int|string $isValue;
    public int|string $isRoute;
    public string $isTitle;
    /**
     * Create a new component instance.
     */

    public function __construct($userId, $isActive = true, $isValue, $isRoute, $isTitle)
    {
        $this->userId   = $userId;
        $this->isActive = (bool) $isActive;
        $this->isValue   = $isValue;
        $this->isRoute   = $isRoute;
        $this->isTitle   = $isTitle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toggle-switch-button');
    }
}
