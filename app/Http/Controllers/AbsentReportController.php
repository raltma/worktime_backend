<?php

namespace App\Http\Controllers;

use App\Models\AbsentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AbsentReportController extends Controller
{
        /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */

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
              $report = AbsentReport::find($request->reportId);
              $report->confirmed = 1;
              $report->save();
              return back()->withErrors(['message'=>"Aruanne kinnitatud"]);
      }
      public function index()
      {
          $reports = AbsentReport::all();
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
                  'reason' => 'required',
                  'shift' => 'required',
                  'hours' => 'required',
                  'file' => 'required'
              ]);
  
              if($validate->fails()) return response()->json([
                  'status' => false,
                  'message' => 'validation error',
                  'error' => $validate->errors()
              ], 400);
          $report = new AbsentReport;
          $report->date_selected = $request->date_selected;
          $report->shift = $request->shift;
          $report->reason = $request->reason;
          $report->user_id = $request->user_id;
          $report->hours = $request->hours;
          $file = $request->file('file');
          if($file !== null){
            $file_uploaded_path = $file->store("absentReport",'public');
            $file_uploaded_path = asset("storage/".$file_uploaded_path);
            $report->filepath = $file_uploaded_path;
          }
          $report->save();
          
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
          return AbsentReport::find($reportId);
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
          AbsentReport::where('id',$id)->update($request->all());
          $report = AbsentReport::find($id);
          
          return $report;
      }
  
      /**
       * Remove the specified resource from storage.
       *
       * @param  \App\Models\Post  $post
       * @return \Illuminate\Http\Response
       */
      public function destroy(AbsentReport $report)
      {
          $report->delete();
  
          return response(null, 204);
      }
}
