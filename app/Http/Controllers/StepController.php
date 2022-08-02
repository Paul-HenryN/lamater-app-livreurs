<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StepResource;


class StepController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  int $reportId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $reportId)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'files' => 'nullable'
        ]);

        $step = Step::create([
            'name' => $request->name,
            'description' => $request->description,
            'report_id' => $reportId,
        ]);

        if($request->hasfile('files')) {
            $fileAdders = $step->addMultipleMediaFromRequest(['files'])
                            ->each(function ($fileAdder) {
                                $fileAdder->toMediaCollection('stepFiles');
                            });
        }

        return response()->json(new StepResource($step));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $stepId
     * @param  int  $reportId
     * @return \Illuminate\Http\Response
     */
    public function show($reportId, $stepId)
    {
        $step = Step::where("report_id", $reportId)->find($stepId);

        if(! $step)
            return response(['error' => true, 'message' => "Step #$stepId not found in report #$reportId"], 404);

        return response()->json(new StepResource($step));
    }
    
    /**
     * Update the specified resource.
     *
     * @param  int  $reportId
     * @param  int  $stepId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $reportId, $stepId)
    {
        $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'files' => 'nullable',
        ]);

        $step = Step::where("report_id", $reportId)->find($stepId);
        
        if(! $step)
            return response(['error' => true, 'message' => "Step #$stepId not found in report #$reportId"], 404);

        if($request->has('name'))
            $step->name = $request->name;
        if($request->has('description'))
            $step->description = $request->description;
        if($request->hasfile('files')) {
            $fileAdders = $step->addMultipleMediaFromRequest(['files'])
                            ->each(function ($fileAdder) {
                                $fileAdder->toMediaCollection('stepFiles');
                            });
        }

        $step->save();
        
        return response()->json(new StepResource($step));
    }

    /**
     * Delete the specified resource.
     *
     * @param  int  $stepId
     * @param  int  $reportId
     * @return \Illuminate\Http\Response
     */
    public function destroy($reportId, $stepId)
    {
        $step = Step::where("report_id", $reportId)->find($stepId);

        if(! $step)
            return response(['error' => true, 'message' => "Step #$stepId not found in report #$reportId"], 404);

        $step->delete();
    }

        /**
     * Delete the specified resource.
     *
     * @param  int  $stepId
     * @param  int  $reportId
     * @param  int  $fileId
     * @return \Illuminate\Http\Response
     */
    public function destroyFile($reportId, $stepId, $fileId)
    {
        $report = Report::find($reportId);
        if(! $report)
            return response(['error' => true, 'message' => "Report #$reportId not found"], 404);

        $step = Step::where("report_id", $reportId)->find($stepId);
        if(! $step)
            return response(['error' => true, 'message' => "Step #$stepId not found in report #$reportId"], 404);

        $file = $step->getMedia('stepFiles')->find($fileId);
        if(! $file)
            return response(['error' => true, 'message' => "File #$fileId not found for step #$stepId of report #$reportId"], 404);

        $file->delete();
    }
}
