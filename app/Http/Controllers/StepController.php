<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;
use Illuminate\Support\Facades\Storage;


class StepController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  int $reportId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $step = Step::create([
            'name' => $request->name,
            'description' => $request->description,
            'report_id' => $reportId,
        ]);

        $step->addMediaFromRequest('file')->toMediaCollection('StepFiles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $step = Step::find($id);

        return response()->json($step);
    }
}
