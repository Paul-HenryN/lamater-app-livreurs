<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ReportResource;

class ReportController extends Controller
{
    /**
     * A listing of the specified resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function index(Request $request)
    {
        $sortBy = ($request->query('sort_by')) ? $request->query('sort_by') : 'id';
        $sortDir = ($request->query('sort_dir')) ? $request->query('sort_dir') : 'asc';
        $perPage = ($request->query('per_page')) ? $request->query('per_page') : 5;
        $status = $request->query('status');

        $reports = Report::with('steps')->orderBy($sortBy, $sortDir);

        if($status != null)
            $reports = $reports->where('status', $status);

        $reports = $reports->paginate($perPage);

        //Replace reports with reportResources
        for ($i=0; $i < count($reports); $i++) { 
                $reports[$i] = new ReportResource($reports[$i]);
            }

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
            'status' => 'nullable|boolean',
        ]);

        $report = Report::with('steps')->find($reportId);
        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

        if($request->has('name'))
            $report->name = $request->name;
        if($request->has('description'))
            $report->description = $request->description;
        if($request->has('status'))
            $report->status = $request->status;
            
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
