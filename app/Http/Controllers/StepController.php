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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $documentPaths = [];
        $document = $request->file('documents');

        $path = $document->store('documents');
        $documentPaths[] = $path;

        Step::create([
            'name' => $request->name,
            'infos' => $request->infos,
            'documents' => json_encode($documentPaths),
        ]);
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
