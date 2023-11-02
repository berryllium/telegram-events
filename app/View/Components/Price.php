<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Price extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private readonly string $isRequired,
        private readonly string $defaultType
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $showPrice = true;
        $showPriceTo = true;
        $showFrom = true;
        $showTo = true;

        if($this->defaultType == 'min') {
            $showPriceTo = false;
            $showTo = false;
        } else if($this->defaultType == 'exact') {
            $showPriceTo = false;
            $showFrom = false;
            $showTo = false;
        } else if($this->defaultType == 'free' || $this->defaultType == 'no') {
            $showPrice = false;
            $showPriceTo = false;
            $showFrom = false;
            $showTo = false;
        }

        return view('components.price', [
            'isRequired' => $this->isRequired,
            'defaultType' => $this->defaultType,
            'showPrice' => $showPrice,
            'showPriceTo' => $showPriceTo,
            'showFrom' => $showFrom,
            'showTo' => $showTo,
        ]);
    }
}
