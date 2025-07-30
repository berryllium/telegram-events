<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DetailedTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(protected $url, protected $payload)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.detailed-table', ['url' => $this->url, 'payload' => $this->payload]);
    }
}
