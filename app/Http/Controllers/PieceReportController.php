<?php

namespace App\Http\Controllers;

use App\Models\PieceReport;
use Illuminate\Http\Request;
use App\Http\Resources\PieceReportResource;

class PieceReportController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = PieceReport::all();
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
        $PieceReport = PieceReport::create($request->all());
        
        return new PieceReportResource($PieceReport);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $reportId)
    {
        return PieceReport::find($reportId);
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
        PieceReport::where('id',$id)->update($request->all());
        $report = PieceReport::find($id);
        
        return $report;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(PieceReport $report)
    {
        $report->delete();

        return response(null, 204);
    }
}
