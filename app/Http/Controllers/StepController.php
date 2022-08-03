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
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'files' => 'nullable'
        ]);

        $report = Report::find($reportId);
        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

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
        $report = Report::find($reportId);
        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

        $step = Step::where("report_id", $reportId)->find($stepId);
        if(! $step)
            return $this->throwNotFoundError("Step #$stepId not found in report #$reportId");

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

        $report = Report::find($reportId);
        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

        $step = Step::where("report_id", $reportId)->find($stepId);
        if(! $step)
            return $this->throwNotFoundError("Step #$stepId not found in report #$reportId");

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
        $report = Report::find($reportId);
        if(! $report)
            return $this->throwNotFoundError("Report #$reportId not found");

        $step = Step::where("report_id", $reportId)->find($stepId);
        if(! $step)
            return $this->throwNotFoundError("Step #$stepId not found in report #$reportId");

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
            return $this->throwNotFoundError("Report #$reportId not found");

        $step = Step::where("report_id", $reportId)->find($stepId);
        if(! $step)
            return $this->throwNotFoundError("Step #$stepId not found in report #$reportId");

        $file = $step->getMedia('stepFiles')->find($fileId);
        if(! $file)
            return $this->throwNotFoundError("File #$fileId not found for step #$stepId of report #$reportId");

        $file->delete();
    } 
}
