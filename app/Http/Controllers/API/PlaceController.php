<?php

namespace App\Http\Controllers\API;

use App\DTO\MessageDTO;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageSchedule;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function info(Place $place)
    {
        return response()->json([
            'place' => $place
                ->load('place_files')
                ->load('sliders.slides')
                ->toArray()
        ]);
    }

    public function messages(Place $place, Request $request)
    {
        $query = MessageSchedule::query()
            ->where('status', 'success')
            ->whereHas('message', fn($q) => $q->where('place_id', $place->id))
        ;

        $limit = $request->get('limit') ?? 10;
        if($request->get('by') && $request->get('order')) {
            $query->orderBy($request->get('by'), $request->get('order'));
        } else {
            $query->orderBy('id', 'desc');
        }

        $query->offset($request->get('offset') ?? 0);
        $query->with('message');
        $query->with('message.message_files');
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

    public function message(Place $place, MessageSchedule $messageSchedule)
    {
        $message = $messageSchedule->with('message')->with('message.message_files')->get();
        if(!$message) abort(404);
        return response()->json([
            'message' => new MessageDTO($messageSchedule)
        ]);
    }
}
