<?php

namespace App\Http\Controllers;

use App\Models\HourReport;
use App\Http\Resources\HourReportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HourReportController extends Controller
{

    public function delete(Request $request, $id){
        $report = HourReport::find($id);
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
            $report = HourReport::find($input['id']);
            
            if(isset($input['date'])) $report->date_selected = $input['date'];
            if(isset($input['shift']))$report->shift = $input['shift'];
            if(isset($input['hours']))$report->hours = $input['hours'];
            if(isset($input['overtime'])){
                if($input['overtime']>0) $report->overtime = 1;
                $report->overtime_hours = $input['overtime'];
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
              $report = HourReport::find($request->reportId);
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