<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;

use App\Models\Departments;
use App\Models\Employee;
use App\Models\Patients;
use App\Models\procedure;
use App\Models\Surgery;
use App\Models\patientOperation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class PatientOperationController extends Controller
{
    public function index()
    {  
         $dep=Departments::select('department_name','dep_id')->get();
        $patient=Patients::select('patient_id','f_name','l_name','patient_idetify_number')->orderBy('created_at','DESC')->get();
        $operate=patientOperation::
        select('department_name','employees.f_name as emp_f_name','employees.l_name as emp_l_name'
        ,'patients.f_name','patients.l_name','patient_idetify_number','users.email','patient_operations.*')
        ->join('departments','departments.dep_id','patient_operations.dep_id')
        ->join('employees','employees.emp_id','patient_operations.emp_id')
        ->join('patients','patients.patient_id','patient_operations.patient_id')
        ->join('users','users.id','patient_operations.author')
        ->groupBy('patient_operations.patient_s_del_pro_id')
        ->orderBy('patient_operations.created_at','DESC')->paginate(50);
        return view('admin.surgery.patient_p_s_d',compact('patient','dep','operate'));
    }
    public function docter_reg_operate($dep_id,$type)
    {
       $emp=Employee::where('dep_id',$dep_id)->get();

     if($type=="surgery"){
        $sur=Surgery::where('dep_id',$dep_id)->get();
     }else{
         $sur="";
     }
     if($type=='procedure'){
        $pro=procedure::where('dep_id',$dep_id)->get();
     }else{
        $pro="";
     }
     if($type=="normal delivery"){
        $normal="normal";
     }else{
        $normal="";
     }     
       return Response()->json(array(
        'sur' => $sur,
        'emp' => $emp,
        'pro' => $pro,
        'normal'=>$normal
       ));  
    }

    public function store(Request $request)
    {
        
        if($request->type=="surgery"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'surgery'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
                $pa= new patientOperation;
                $pa->type=$request->type;
                $pa->surgery_id=$request->surgery;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->author=Auth::id();
                $pa->save();
                return response()->json(['success'=>'Operation Registered successfully']);
               } 
        }
        if($request->type=="procedure"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'procedure'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
               $pa= new patientOperation;
               $pa->type=$request->type;
               $pa->procedure_id=$request->procedure;
               $pa->date=$request->date;
               $pa->time=$request->time;
               $pa->patient_id=$request->patient;
               $pa->emp_id=$request->docter;
               $pa->dep_id=$request->department;
               $pa->author=Auth::id();
               $pa->save();
               return response()->json(['success'=>'Operation Registered successfully']);

               } 
        }
        if($request->type=="normal delivery"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
                $pa= new patientOperation;
                $pa->type=$request->type;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->author=Auth::id();
                $pa->save();
                return response()->json(['success'=>'Operation Registered successfully']);
               } 
        }
        if($request->type=="direct admission"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
                $pa= new patientOperation;
                $pa->type=$request->type;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->author=Auth::id();
                $pa->save();
                return response()->json(['success'=>'Operation Registered successfully']);
               } 
        }
        if(empty($request->type)){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
        }
    }

    public function edit($id)
    {
        $edit=patientOperation::find($id);
        return response()->json($edit);
    }

    public function update(Request $request){
        if($request->type=="surgery"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'surgery'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
                $pa=patientOperation::find($request->surg_id);
                $pa->type=$request->type;
                $pa->surgery_id=$request->surgery;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->procedure_id=null;
                $pa->save();
                return response()->json(['success'=>'Operation updated successfully']);
               } 
        }
        if($request->type=="procedure"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'procedure'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
                $pa=patientOperation::find($request->surg_id);
                $pa->type=$request->type;
               $pa->procedure_id=$request->procedure;
               $pa->date=$request->date;
               $pa->time=$request->time;
               $pa->patient_id=$request->patient;
               $pa->emp_id=$request->docter;
               $pa->dep_id=$request->department;
               $pa->surgery_id=null;
               $pa->save();
               return response()->json(['success'=>'Operation updated successfully']);

               } 
        }
        if($request->type=="normal delivery"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
               if($valid){
                $pa=patientOperation::find($request->surg_id);
                $pa->type=$request->type;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->surgery_id=null;
                $pa->procedure_id=null;
                $pa->save();
                return response()->json(['success'=>'Operation updated successfully']);
               } 
        }
        if(empty($request->type)){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required'
            ]);
        }
    }


    public function destroy($id)
    {
        patientOperation::find($id)->delete();
        return response()->json(['success'=>'Operation deleted successfully']);

    }
}
