<?php

namespace App\View\Components;

use App\Models\Dictionary;
use App\Models\Field;
use App\Models\Place;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Tag extends Component
{
    private $field;
    private $place;
    private $counter;

    /**
     * Create a new component instance.
     */
    public function __construct(int $field, int $place, int $counter) {
        $this->field = Field::find($field);
        $this->place = Place::find($place);
        $this->counter = $counter;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        /** @var Collection $tags */
        $tags = $this->field->dictionary->dictionary_values;
        $tag_sets = [];
        foreach ($tags as $tag) {
            $set = explode(':', $tag->value, 2);
            $tag_sets[trim($set[1])] = [
                'value' => trim($set[0]),
                'type' => 'common',
            ];
        }

        if($this->place->tag_set) {
            $place_tag_set = Dictionary::find($this->place->tag_set);
            foreach ($place_tag_set->dictionary_values as $tag) {
                $set = explode(':', $tag->value, 2);
                $tag_sets[trim($set[1])] = [
                    'value' => trim($set[0]),
                    'type' => 'shop',
                ];
            }
        }


        return view('components.tag', [
            'field' => $this->field,
            'tag_sets' => $tag_sets,
            'k' => $this->counter
        ]);
    }
}
