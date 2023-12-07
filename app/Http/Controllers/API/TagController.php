<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Place;

class TagController extends Controller
{
    public function getPlaceTagSets(Place $place) {
        if($place->tag) {
            foreach ($place->tag->dictionary_values as $tag) {
                $set = explode(':', $tag->value, 2);
                $tag_sets[trim($set[1])] = [
                    'value' => trim($set[0]),
                    'type' => 'shop',
                ];
            }
        }
        return response()->json($tag_sets ?? []);
    }
}
