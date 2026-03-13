<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusBadge extends Component
{
    public $status;
    public $text;

    public function __construct($status = 'success', $text = 'Selesai')
    {
        $this->status = $status;
        $this->text = $text;
    }

    public function render()
    {
        return view('components.status-badge');
    }
}