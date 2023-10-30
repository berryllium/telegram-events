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

        $report = ucfirst($data['type']);
        $class = "\\App\\Actions\\Report\\{$report}Report";
        if(class_exists($class)) {
            return (new $class)->handle($data);
        }
        return back()->with('error', __('webapp.error'));
    }
}
