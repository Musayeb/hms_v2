<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Surgery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    public function index()
    {
        $surgery= Surgery::
          join('departments','surgeries.dep_id','departments.dep_id')
         ->join('users','surgeries.author','users.id')
         ->groupBy('surgeries.surgery_id')
         ->orderBy('surgeries.created_at','DESC')->get();

        $dep= Departments::all();

        return view('admin.surgery.surgery',compact('surgery','dep'));
    }
    public function store(Request $request)
    {
       $valid= $request->validate([
        'department'=>'required',
        'surgery_name'=>'required',
        ]);
        if($valid){
          $surg= new Surgery;
          $surg->surgery_name=$request->surgery_name;
          $surg->dep_id=$request->department;
          $surg->author=Auth::id();
          $surg->save();
          return response()->json(['success'=>'Surgery added successfully']);    
        }
    }
    public function update(Request $request)
    {
        $valid= $request->validate([
            'department'=>'required',
            'surgery_name'=>'required',
        ]);
        if($valid){
            $sur=Surgery::find($request->surg_id);
            $sur->surgery_name=$request->surgery_name;
            $sur->dep_id=$request->department;
            $sur->save();
            return response()->json(['success'=>'Surgery updated successfully']);    
        }
    }

    public function destroy($id)
    {
        $sur=Surgery::find($id)->delete();
        return response()->json(['success'=>'Record deleted successfully']);    
    }

}
