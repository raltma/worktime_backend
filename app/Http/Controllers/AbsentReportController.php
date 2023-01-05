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

        public function delete(Request $request, $id){
        $report = AbsentReport::find($id);
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
            $report = AbsentReport::find($input['id']);
            
            if(isset($input['date'])) $report->date_selected = $input['date'];
            if(isset($input['shift']))$report->shift = $input['shift'];
            if(isset($input['hours']))$report->hours = $input['hours'];
            if(isset($input['reason']))$report->reason = $input['reason'];
            $file = $request->file('file');
            if($file !== null){
                $file_uploaded_path = $file->store("absentReport",'public');
                $file_uploaded_path = asset("storage/".$file_uploaded_path);
                $report->filepath = $file_uploaded_path;
                $report->filename = $file->getClientOriginalName();
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
              $report = AbsentReport::find($request->reportId);
              $report->confirmed = 1;
              $report->confirmer_id = $request->user()->id;
              $report->confirmed_at = now();
              $report->save();
              return back()->withErrors(['message'=>"Aruanne kinnitatud"]);
      }
      public function index()
      {
          $reports = AbsentReport::all();
          return $reports;
      }

      public function getByDate(Request $request)
      {
        $validate = Validator::make($request->all(),
            [
                'date' => 'required|date'
            ]);
        if($validate->fails()) return response()->json([
            'status' => false,
            'message' => 'validation error',
            'error' => $validate->errors()
        ], 400);
        $data = $request->validated();
        $reports = AbsentReport::where('date_selected', $data['date']);
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
            $report->filename = $file->getClientOriginalName();
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
