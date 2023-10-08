<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageScheduleController extends Controller
{
    public function __construct() {
        $this->authorizeResource(MessageSchedule::class, 'message_schedule');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Message $message)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageSchedule $messageSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageSchedule $messageSchedule)
    {
        return view('schedule.edit', ['schedule' => $messageSchedule]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageSchedule $messageSchedule)
    {
        $data = $request->validate([
            'status' => 'in:' . implode(',', array_keys(MessageSchedule::$statuses)),
            'sending_date' => ''
        ]);
        $data['sending_date'] = $data['sending_date'] ? Carbon::parse($data['sending_date']) : now();
        $messageSchedule->update($data);
        return redirect(route('message.edit', $messageSchedule->message))->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageSchedule $messageSchedule)
    {
        $messageSchedule->delete();
        return back()->with('success', __('webapp.record_deleted'));
    }
}
