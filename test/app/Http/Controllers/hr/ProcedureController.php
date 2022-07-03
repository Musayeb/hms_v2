<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\procedure;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    public function index()
    {
        $dep= Departments::all();
        $pro= procedure::
        join('departments','procedures.dep_id','departments.dep_id')
       ->join('users','procedures.author','users.id')
       ->groupBy('procedures.procedure_id')
       ->orderBy('procedures.created_at','DESC')->get();
        return view('admin.surgery.procedure',compact('dep','pro'));
    }
    public function store(Request $request)
    {
        $valid= $request->validate([
            'department'=>'required',
            'procedure_name'=>'required',
            'procedure_fees'=>'required',
        ]);
        if($valid){
           $pro=new procedure;
           $pro->dep_id=$request->department;
           $pro->procedure_name=$request->procedure_name;
           $pro->procedure_fees=$request->procedure_fees;
           $pro->author=Auth::id();
           $pro->save();

          return response()->json(['success'=>'Procedure added successfully']);    
        }
    }
    public function update(Request $request)
    {
        $valid= $request->validate([
            'department'=>'required',
            'procedure_name'=>'required',
            'procedure_fees'=>'required',
        ]);
        if($valid){
            $pro=procedure::find($request->pro_id);
            $pro->dep_id=$request->department;
            $pro->procedure_name=$request->procedure_name;
            $pro->procedure_fees=$request->procedure_fees;
            $pro->save();
           return response()->json(['success'=>'Procedure updated successfully']);    
        }
    }

    public function destroy($id)
    {
        $pro=procedure::find($id)->delete();
        return response()->json(['success'=>'Record deleted successfully']);    
    }
}
