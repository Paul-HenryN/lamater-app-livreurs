<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newReport = Report::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $newReport = Report::with('steps')->find($newReport->id);

        return response()->json($newReport);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $reportId
     * @return \Illuminate\Http\Response
     */
    public function show($reportId)
    {
        $report = Report::with('steps')->find($reportId);

        return response()->json($report);
    }

    /**
     * Update the specified resource.
     *
     * @param  int  $reportId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $reportId)
    {
        $report = Report::with('steps')->find($reportId);

        if($request->has('name'))
            $report->name = $request->name;
        elseif($request->has('description'))
            $report->description = $request->description;

        $report->save();

        return response()->json($report);
    }

    /**
     * Delete the specified resource.
     *
     * @param  int  $reportId
     * @return \Illuminate\Http\Response
     */
    public function destroy($reportId)
    {
        $report = Report::find($reportId);

        $report->delete();
    }
}
