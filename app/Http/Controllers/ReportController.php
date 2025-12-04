<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("app.reports.index");
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
    public function show(Report $report)
    {
        $currentUser = auth()->user();
        try {
            if (empty($currentUser) || ! $report->canBeViewedBy($currentUser)) {
                abort(403);
            }
        } catch (\Throwable $e) {
            \Log::error('Report show authorization failed: '.$e->getMessage(), ['report_id' => $report->id]);
            abort(403);
        }

        return view("app.reports.show", compact("report"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        $currentUser = auth()->user();
        try {
            if (empty($currentUser) || ! $report->canBeEditedBy($currentUser)) {
                abort(403);
            }
        } catch (\Throwable $e) {
            \Log::error('Report edit authorization failed: '.$e->getMessage(), ['report_id' => $report->id]);
            abort(403);
        }

        return view('app.reports.edit', [
            'report' => $report
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
