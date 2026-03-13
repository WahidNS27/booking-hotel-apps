<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActivityItem extends Component
{
    public $icon;
    public $iconBg;
    public $iconColor;
    public $title;
    public $time;

    public function __construct(
        $icon = 'fas fa-user-plus',
        $iconBg = 'bg-blue-100',
        $iconColor = 'text-blue-600',
        $title = 'Aktivitas',
        $time = 'Baru saja'
    ) {
        $this->icon = $icon;
        $this->iconBg = $iconBg;
        $this->iconColor = $iconColor;
        $this->title = $title;
        $this->time = $time;
    }

    public function render()
    {
        return view('components.activity-item');
    }
}