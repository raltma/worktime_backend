<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassificationResource;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassificationController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classifications = Classification::all();
        return $classifications;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(),['name' => 'required']);

            if($validate->fails()) return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validate->errors()
            ], 400);
        $classification = Classification::create($request->all());
        
        return new ClassificationResource($classification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $reportId)
    {
        return Classification::find($reportId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Classification::where('id',$id)->update($request->all());
        $classification = Classification::find($id);
        
        return $classification;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classification $report)
    {
        $report->delete();

        return response(null, 204);
    }
}
