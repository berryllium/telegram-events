<?php

namespace App\Http\Controllers\API;

use App\DTO\MessageDTO;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function info(Place $place)
    {
        return response()->json([
            'place' => $place->toArray()
        ]);
    }

    public function messages(Place $place, Request $request)
    {
        $query = $place->messages();
        $limit = $request->get('limit') ?? 10;
        if($request->get('by') && $request->get('order')) {
            $query->orderBy($request->get('by'), $request->get('order'));
        } else {
            $query->orderBy('id', 'desc');
        }

        $query->offset($request->get('offset') ?? 0);
        $query->with('message_files');
        $pagination = $query->paginate($limit);

        if(!$pagination->count()) {
            abort(404);
        }

        return response()->json([
            'messages' => array_map(fn($message) => new MessageDTO($message), $pagination->items()),
            'pagination' => [
                'lastPage' => $pagination->lastPage(),
                'total' => $pagination->total()
            ]
        ]);
    }

    public function message(Place $place, Message $message)
    {
        $message = $message->load('message_files');
        if(!$message) abort(404);
        return response()->json([
            'message' => new MessageDTO($message)
        ]);
    }
}
