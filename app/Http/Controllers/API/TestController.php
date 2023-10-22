<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::all();
        return $messages;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = Message::query()->with('author')->where('id', $id)->get();
        return $message;
    }

}
