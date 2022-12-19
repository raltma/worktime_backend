<?php

namespace App\Http\Controllers;

use App\Models\HourReport;
use App\Http\Resources\HourReportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HourReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = HourReport::all();
        return $reports;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(),
            [
                'date_selected' => 'required|date',
                'user_id' => 'required',
                'hours' => 'required|numeric',
                'shift' => 'required'
            ]);

            if($validate->fails()) return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validate->errors()
            ], 400);
        $HourReport = HourReport::create($request->all());
        
        return new HourReportResource($HourReport);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $reportId)
    {
        return HourReport::find($reportId);
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
        HourReport::where('id',$id)->update($request->all());
        $report = HourReport::find($id);
        
        return $report;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(HourReport $report)
    {
        $report->delete();

        return response(null, 204);
    }
}