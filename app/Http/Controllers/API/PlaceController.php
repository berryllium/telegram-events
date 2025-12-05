<?php

namespace App\Http\Controllers\API;

use App\DTO\MessageDTO;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Channel;
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
            ->where('status', 'success');

        // If place is_channel, get messages from channels associated with this place
        if ($place->is_channel) {
            $channelIds = Channel::where('type', 'site')->where('tg_id', $place->id)->pluck('id')->toArray();
            if (empty($channelIds)) {
                abort(404);
            }
            $query->whereHas('channels', fn($q) => $q->whereIn('channels.id', $channelIds));
        } else {
            // Otherwise get messages from this place
            $query->whereHas('message', fn($q) => $q->where('place_id', $place->id));
        }

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
        // Verify that the message belongs to either the place or one of its channels (if is_channel)
        if ($place->is_channel) {
            $channelIds = $place->channels()->pluck('channels.id')->toArray();
            if (empty($channelIds) || !in_array($messageSchedule->channel_id, $channelIds)) {
                abort(404);
            }
        } else {
            if ($messageSchedule->message->place_id !== $place->id) {
                abort(404);
            }
        }

        $message = $messageSchedule->with('message')->with('message.message_files')->get();
        if(!$message) abort(404);
        return response()->json([
            'message' => new MessageDTO($messageSchedule)
        ]);
    }

    public function services(Place $place)
    {
        return response()->json([
            'services' => $place->services,
        ]);
    }
}
