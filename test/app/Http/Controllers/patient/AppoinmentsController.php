<?php

namespace App\Http\Controllers\patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Appoinments;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\Test;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AppoinmentsController extends Controller
{
    public function index()
    {
        
        $dep=Departments::all();
  
        $app=Appoinments::select('employees.f_name','employees.l_name','departments.department_name','date','appoinments.emp_id')
        ->join('employees','employees.emp_id','appoinments.emp_id')
        ->join('departments','departments.dep_id','appoinments.dep_id')
        ->where('date',date('Y-m-d'))
        ->groupBy('employees.emp_id')
        ->orderBy('patient_id','DESC')->get();    

        return view('admin.patients.appoinments.index',compact('app','dep'));
    }
    public function getpostition($id)
    {
        $pos=Employee::where('dep_id',$id)->where('type','docter')->get();
        return response()->json($pos);
    }
    public function getdocterfee($id)
    {
        $fee=Employee::select('fees')->where('emp_id',$id)->get();
        return response()->json($fee);
    }
    public function get_test_dep($id)
    {

        $test=Test::where('dep_id',$id)->get();
        return response()->json($test);
    }
    public function get_test_fee($id)
    {
        $test=Test::select('fees')->where('test_id',$id)->get();
        return response()->json($test);
    }
    
    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'age'=>'required',
            'phone'=>'required',
            'date'=>'required',
            'date'=>'required',
            'time'=>'required',
            'department'=>'required',
            'docter'=>'required',
        ]);
        $ex=explode('-',$request->date);
        $patient_number=Appoinments::select(DB::raw('Max(patient_id)as max'))->get();
        $opp_number=Appoinments::
        select(DB::raw('Max(app_number)as max'))
        ->where('date',$request->date)
        ->whereDay('date',$ex[2])
        ->where('emp_id',$request->docter)
        ->get();

         $app=new Appoinments;
         $app->p_f_name=$request->first_name;
         $app->p_l_name=$request->last_name;
         $app->age=$request->age;
         $app->phone=$request->phone;
         $app->dep_id=$request->department;
         $app->emp_id=$request->docter;
         $app->date=$request->date;
         $app->time=$request->time;
         $app->author=Auth::id();
         $app->app_number=$opp_number[0]->max+1;
         $app->patient_id=$patient_number[0]->max+1;
         $app->save();
        return response()->json(['success'=>"Appoinment Created Successfully !",'id'=>$app->app_id]);   								
    }
    public function edit($id)
    {
        $app=Appoinments::find($id);
        $pos=Employee::where('dep_id',$app->dep_id)->get();
        return Response()->json(array(
            'app' => $app,
            'pos' => $pos,
        ));
    }
    public function update(Request $request)
    {
        $datavalidate=$request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'age'=>'required',
            'phone'=>'required',
            'date'=>'required',
            'date'=>'required',
            'time'=>'required',
            'department'=>'required',
            'docter'=>'required',
        ]);
        if($datavalidate==true){
        $app=Appoinments::find($request->app_id);
         $app->p_f_name=$request->first_name;
         $app->p_l_name=$request->last_name;
         $app->age=$request->age;
         $app->phone=$request->phone;
         $app->dep_id=$request->department;
         $app->emp_id=$request->docter;
         $app->date=$request->date;
         $app->time=$request->time;
         $app->save();
        return response()->json(['success'=>"Appoinment Updated Successfully !"]);   								

        }
    }
    public function destroy($id)
    {
        $app=Appoinments::find($id)->delete();
    }
    public function show($id)
    {
        $app=Appoinments::
        select('users.email','departments.*','employees.f_name','employees.l_name','appoinments.*')->
        join('employees','employees.emp_id','appoinments.emp_id')
        ->join('departments','departments.dep_id','appoinments.dep_id')
        ->join('users','users.id','appoinments.author')
        ->where('app_id',$id)
        ->get();
        $app=$app[0];

        
        $show='<div style="display:flex;margin-top: 20px">
        <div style="width:40%;text-align: left">
            <div class="form-group ">
                <label>Date #: <strong id="bill_no1">'.$app->date.'</strong></label>
            </div>
        </div>
        <div style="width:40%;text-align: center">
        <div class="form-group ">
            <label>Appoinment No #: <strong id="bill_no1">APP-N-'.$app->app_number.'</strong></label>
        </div>
    </div>
        <div style="width: 40%;text-align:right">
            <div class="form-group float-right">
                <label>Time: <strong id="bill_date1">'.$app->time.'</strong></label>
            </div>
        </div>
    </div>
    <div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Docter Name:</th>
                    <td ><small>'.$app->f_name . ' ' . $app->l_name.'</small></td>
                    <th>Patient Name:</th>
                    <td ><small>'.$app->p_f_name.' '.$app->p_l_name.'</small></td>
                    <th >Department</th>
                    <td ><small>'.$app->department_name.'</small></td>                                                                
                </tr>
                <tr>
                    <th>Age:</th>
                    <td ><small >'.$app->age.'</small></td>   
                    <th>Phone:</th>
                    <td ><small >'.$app->phone.'</small></td>   
                                
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class=" p-4" style="margin-top:20px">
        <div style="display:flex">
        <table class="printablea4 table" id="testreport" style="width:70%">
            <tbody>
                <tr>
                    <th >Issue By</th>
                    <td id="by">'.$app->email.'</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>';
        return response()->json($show);
    }


    public function print($id)
    {
        $app=Appoinments::
        select('users.email','departments.*','employees.f_name','employees.l_name','appoinments.*')->
        join('employees','employees.emp_id','appoinments.emp_id')
        ->join('departments','departments.dep_id','appoinments.dep_id')
        ->join('users','users.id','appoinments.author')
        ->where('app_id',$id)
        ->get();
        $app=$app[0];

        
        $show='<div style="display:flex;margin-top: 20px">
      
    
    
    </div>
    <div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
            <tr><td align="center"><small>Appoiment Number: <br><strong align="center">APP-N-'.$app->app_number.'</small></strong></td><tr>
            <tr><td align="center"><small>Date: '.$app->date.'</small></td><tr>
            <tr><td align="center"><small>Time: '.$app->time.'</small></td><tr>
            <tr><td align="center"><small>Docter Name: '.$app->f_name . ' ' . $app->l_name.'</small></td><tr>
            <tr><td align="center"><small>Patient Name: '.$app->p_f_name.' '.$app->p_l_name.'</small></td><tr>
            <tr><td align="center"><small>Department: '.$app->department_name.'</small></td><tr>
            <tr><td align="center"><small>Age: '.$app->age.'</small></td><tr>
            <tr><td align="center"><small>Phone:'.$app->phone.'</small></td><tr>
            </tbody>
        </table>
    </div>
    </div>';
        return response()->json($show);
    }

    public function getfilter(Request $request)
    {
        $app=Appoinments::select('employees.f_name','employees.l_name','departments.department_name','date','appoinments.emp_id')
        ->join('employees','employees.emp_id','appoinments.emp_id')
        ->join('departments','departments.dep_id','appoinments.dep_id')
        ->where('date',$request->date)
        ->groupBy('employees.emp_id')
        ->orderBy('patient_id','DESC')->get();    
        $dep=Departments::all();
        return view('admin.patients.appoinments.index',compact('app','dep'));


    }
}
