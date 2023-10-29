<?php

namespace App\Http\Controllers;

use App\Actions\Report\AuthorReport;
use App\Actions\Report\PlaceReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        return view('report.index');
    }

    public function process(Request $request) {
        $data = $request->validate([
            'from' => 'required',
            'to' => 'required',
            'type' => 'required',
        ]);

        if($data['type'] == 'author') {
            return (new AuthorReport)->handle($data);
        } elseif($data['type'] == 'place') {
            return (new PlaceReport)->handle($data);
        }
        return back()->with('error', __('webapp.error'));
    }
}
