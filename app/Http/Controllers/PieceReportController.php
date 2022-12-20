<?php

namespace App\Http\Controllers;

use App\Models\PieceReport;
use Illuminate\Http\Request;
use App\Http\Resources\PieceReportResource;
use App\Models\PieceClassification;
use Illuminate\Support\Facades\Validator;

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
        $validate = Validator::make($request->all(),
            [
                'date_selected' => 'required|date',
                'user_id' => 'required',
                'workplace' => 'required|numeric',
                'shift' => 'required',
                'classification_id' => 'required',
                'classification_count' => 'required'
            ]);

            if($validate->fails()) return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validate->errors()
            ], 400);
        $report = new PieceReport;
        $report->date_selected = $request->date_selected;
        $report->shift = $request->shift;
        $report->workplace = $request->workplace;
        $report->user_id = $request->user_id;
        $report->save();
        for($i = 0; $i < sizeof($request->classification_id); $i++){
            $c = new PieceClassification();
            $c->classification_id = $request->classification_id[$i];
            $c->quantity = $request->classification_count[$i];
            $c->piece_report_id = $report->id;
            $c->save();
        }

        
        return $report;
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
