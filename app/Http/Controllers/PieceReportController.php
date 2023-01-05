<?php

namespace App\Http\Controllers;

use App\Models\PieceReport;
use Illuminate\Http\Request;
use App\Http\Resources\PieceReportResource;
use App\Models\PieceClassification;
use Illuminate\Support\Facades\Validator;

class PieceReportController extends Controller
{
    public function delete(Request $request, $id){
        $report = PieceReport::find($id);
        if($report !== null){
            $report->delete();
            return back()->withErrors(['message'=>"Aruanne Kustutatud"]);
        }
        return back()->withErrors([
            'error' => 'Aruannet ei leitud andmebaasist',
        ])->onlyInput('message');
    }

    public function update(Request $request){
        $validate = Validator::make($request->all(),['id'=> 'required']);
        if($validate->fails()) return back()->withErrors([
                'error' => 'ID puudus!']);
        $input = $request->all(); 
        if(auth()->user()->admin === 1){
            $report = PieceReport::find($input['id']);
            
            if(isset($input['date'])) $report->date_selected = $input['date'];
            if(isset($input['shift']))$report->shift = $input['shift'];
            if(isset($input['workplace']))$report->workplace = $input['workplace'];
            
            $report->classifications()->delete();
            if(isset($input['classification'])){
                foreach($input['classification'] as $key=>$value) {
                    $c = new PieceClassification();
                    $c->classification_id = $value;
                    $c->quantity = 0;
                    if(isset($input['count'][$key])) $c->quantity= $input['count'][$key];
                    $c->overtime = 0;
                    if(isset($input['overtime'][$key])) $c->overtime= 1;
                    $c->piece_report_id = $report->id;
                    $c->save();
                }
            }

            if(isset($input['confirmed'])){
                if($report->confirmed !== 1){
                    $report->confirmer_id = $request->user()->id;
                    $report->confirmed_at = now();
                }
                $report->confirmed = 1;
            }else{
                $report->confirmed = 0;
                $report->confirmer_id = null;
                $report->confirmed_at = null;
            }
            $report->save();
            return back()->withErrors(['message'=>"Aruanne muudetud"]);
        }
        return back()->withErrors([
            'error' => 'Kasutajal pole admini õigust!',
        ])->onlyInput('message');
        }

    public function confirm(Request $request){
        $validate = Validator::make($request->all(),
              [
                  'reportId' => 'required',
              ]);
              if($validate->fails()) return response()->json([
                  'status' => false,
                  'message' => 'validation error',
                  'error' => $validate->errors()
              ], 400);
              $report = PieceReport::find($request->reportId);
              $report->confirmed = 1;
              $report->confirmer_id = $request->user()->id;
              $report->confirmed_at = now();
              $report->save();
              return back()->withErrors(['message'=>"Aruanne kinnitatud"]);
      }
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
            $c->overtime = $request->classification_overtime[$i];
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
