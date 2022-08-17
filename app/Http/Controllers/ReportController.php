<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    /**
     * A listing of the specified resource.
     */
    public function index()
    {
        $reports = Report::all();

        return response()->json($reports);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
        ]);

        $newReport = Report::create([
            'name' => $request->name,
        ]);

        if($request->has('description')) {
            $newReport->description = $request->description;
            $newReport->save();
        }

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

        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

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
        $request->validate([
            'name' => 'nullable|max:255',
            'description' => 'nullable|max:255',
        ]);

        $report = Report::with('steps')->find($reportId);
        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

        if($request->has('name'))
            $report->name = $request->name;
        if($request->has('description'))
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

        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

        $report->delete();
    }
}
