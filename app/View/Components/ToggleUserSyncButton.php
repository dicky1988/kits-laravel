<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleUserSyncButton extends Component
{
    public int|string $userId;
    public bool $isActive;
    /**
     * Create a new component instance.
     */

    public function __construct($userId, $isActive = true)
    {
        $this->userId   = $userId;
        $this->isActive = (bool) $isActive;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toggle-user-sync-button');
    }
}
