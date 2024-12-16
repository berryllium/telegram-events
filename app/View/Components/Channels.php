<?php

namespace App\View\Components;

use App\Models\Channel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Channels extends Component
{
    private $channels;
    private $name;
    private $label;
    /**
     * Create a new component instance.
     * @param Channel[] $channels
     */
    public function __construct(Collection $channels, ?string $name, ?string $label) {
        $this->channels = $channels;
        $this->name = $name ?? 'channels';
        $this->label = $label ?? __('webapp.channels');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.channels', [
            'channels' => $this->channels,
            'name' => $this->name,
            'label' => $this->label,
        ]);
    }
}
