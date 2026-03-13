<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatsCard extends Component
{
    public $icon;
    public $iconBg;
    public $iconColor;
    public $title;
    public $value;
    public $trend;
    public $trendIcon;
    public $trendColor;
    public $subtext;

    public function __construct(
        $icon = 'fas fa-users',
        $iconBg = 'bg-blue-100',
        $iconColor = 'text-blue-600',
        $title = 'Total',
        $value = '0',
        $trend = null,
        $trendIcon = 'arrow-up',
        $trendColor = 'text-green-600',
        $subtext = null
    ) {
        $this->icon = $icon;
        $this->iconBg = $iconBg;
        $this->iconColor = $iconColor;
        $this->title = $title;
        $this->value = $value;
        $this->trend = $trend;
        $this->trendIcon = $trendIcon;
        $this->trendColor = $trendColor;
        $this->subtext = $subtext;
    }

    public function render()
    {
        return view('components.stats-card');
    }
}