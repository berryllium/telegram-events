<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModeratorPlaceController extends Controller
{
    public function index() {
        return view('user.moderator_places');
    }

    public function asignPlaces() {

    }
}
