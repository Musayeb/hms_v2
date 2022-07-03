<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;

use App\Models\Departments;
use App\Models\Employee;
use App\Models\FinanceLog;
use App\Models\Patients;
use App\Models\procedure;
use App\Models\Surgery;
use App\Models\patientOperation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DNS1D;
use helper;
use Illuminate\Http\Request;

class PatientOperationController extends Controller
{
    public function index()
    {  
        $dep=Departments::select('department_name','dep_id')->get();
        $patient=Patients::select('patient_id','f_name','l_name','patient_idetify_number')
        ->orderBy('created_at','DESC')
        ->get();
        $surgery=Surgery::select('surgery_id','surgery_name')->get();
        $procedure=procedure::select('procedure_id','procedure_name')->get();        

        $operate=patientOperation::
        select('department_name','employees.f_name as emp_f_name','employees.l_name as emp_l_name'
        ,'patients.f_name','patients.l_name','patient_idetify_number','users.email','patient_operations.*')
        ->join('departments','departments.dep_id','patient_operations.dep_id')
        ->join('employees','employees.emp_id','patient_operations.emp_id')
        ->join('patients','patients.patient_id','patient_operations.patient_id')
        ->join('users','users.id','patient_operations.author')
        ->groupBy('patient_operations.patient_s_del_pro_id')
        ->orderBy('patient_operations.created_at','DESC')->paginate(50);

        return view('admin.surgery.patient_p_s_d',compact('patient','dep','operate','surgery','procedure'));
    }
    public function docter_reg_operate($dep_id,$type)
    {
       $emp=Employee::where('dep_id',$dep_id)->get();

       return Response()->json(array(
        'emp' => $emp,
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
                'time'=>'required',
                'fees'=>'required'
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
                $pa->operate_no=$request->bill_number;
                $pa->fees=$request->fees;
                $pa->author=Auth::id();
                $pa->save();

                $fin= new FinanceLog();
                $fin->payment_type="Surgery&delivery bill payment";
                $fin->bill_id=$pa->patient_s_del_pro_id;
                $fin->total=$request->fees;
                $fin->status="Paid";
                $fin->identify="Surgery-D";
                $fin->transaction=rand(11111111111111,99999999999999);
                $fin->author=Auth::id();
                $fin->save();

                return response()->json(['success'=>'Operation Registered successfully','id'=>$pa->patient_s_del_pro_id]);
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
                'time'=>'required',
                'fees'=>'required'
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
               $pa->operate_no=$request->bill_number;
               $pa->fees=$request->fees;
               $pa->author=Auth::id();
               $pa->save();

               $fin= new FinanceLog();
               $fin->payment_type="Surgery&delivery bill payment";
               $fin->bill_id=$pa->patient_s_del_pro_id;
               $fin->total=$request->fees;
               $fin->status="Paid";
               $fin->identify="Surgery-D";
               $fin->transaction=rand(11111111111111,99999999999999);
               $fin->author=Auth::id();
               $fin->save();

               return response()->json(['success'=>'Operation Registered successfully','id'=>$pa->patient_s_del_pro_id]);

               } 
        }
        if($request->type=="normal delivery"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required',
                'fees'=>'required'
            ]);
               if($valid){
                $pa= new patientOperation;
                $pa->type=$request->type;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->operate_no=$request->bill_number;
                $pa->fees=$request->fees;
                $pa->author=Auth::id();
                $pa->save();

                $fin= new FinanceLog();
                $fin->payment_type="Surgery&delivery bill payment";
                $fin->bill_id=$pa->patient_s_del_pro_id;
                $fin->total=$request->fees;
                $fin->status="Paid";
                $fin->identify="Surgery-D";
                $fin->transaction=rand(11111111111111,99999999999999);
                $fin->author=Auth::id();
                $fin->save();

                return response()->json(['success'=>'Operation Registered successfully','id'=>$pa->patient_s_del_pro_id]);
               } 
        }
        if($request->type=="direct admission"){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required',
                'fees'=>'required'

            ]);
               if($valid){
                $pa= new patientOperation;
                $pa->type=$request->type;
                $pa->date=$request->date;
                $pa->time=$request->time;
                $pa->patient_id=$request->patient;
                $pa->emp_id=$request->docter;
                $pa->dep_id=$request->department;
                $pa->operate_no=$request->bill_number;
                $pa->fees=$request->fees;
                $pa->author=Auth::id();
                $pa->save();

                $fin= new FinanceLog();
                $fin->payment_type="Surgery&delivery bill payment";
                $fin->bill_id=$pa->patient_s_del_pro_id;
                $fin->total=$request->fees;
                $fin->status="Paid";
                $fin->identify="Surgery-D";
                $fin->transaction=rand(11111111111111,99999999999999);
                $fin->author=Auth::id();
                $fin->save();

                return response()->json(['success'=>'Operation Registered successfully','id'=>$pa->patient_s_del_pro_id]);
               } 
        }
        if(empty($request->type)){
            $valid=$request->validate([
                'patient'=>'required',
                'type'=>'required',
                'department'=>'required',
                'docter'=>'required',
                'date'=>'required',
                'time'=>'required',
                'fees'=>'required'
            ]);
        }
    }

    public function edit($id)
    {
        $edit=patientOperation::find($id);
        return response()->json($edit);
    }

    // public function update(Request $request){
    //     if($request->type=="surgery"){
    //         $valid=$request->validate([
    //             'patient'=>'required',
    //             'type'=>'required',
    //             'department'=>'required',
    //             'docter'=>'required',
    //             'surgery'=>'required',
    //             'date'=>'required',
    //             'time'=>'required'
    //         ]);
    //            if($valid){
    //             $pa=patientOperation::find($request->surg_id);
    //             $pa->type=$request->type;
    //             $pa->surgery_id=$request->surgery;
    //             $pa->date=$request->date;
    //             $pa->time=$request->time;
    //             $pa->patient_id=$request->patient;
    //             $pa->emp_id=$request->docter;
    //             $pa->dep_id=$request->department;
    //             $pa->procedure_id=null;
    //             $pa->save();
    //             return response()->json(['success'=>'Operation updated successfully']);
    //            } 
    //     }
    //     if($request->type=="procedure"){
    //         $valid=$request->validate([
    //             'patient'=>'required',
    //             'type'=>'required',
    //             'department'=>'required',
    //             'docter'=>'required',
    //             'procedure'=>'required',
    //             'date'=>'required',
    //             'time'=>'required'
    //         ]);
    //            if($valid){
    //             $pa=patientOperation::find($request->surg_id);
    //             $pa->type=$request->type;
    //            $pa->procedure_id=$request->procedure;
    //            $pa->date=$request->date;
    //            $pa->time=$request->time;
    //            $pa->patient_id=$request->patient;
    //            $pa->emp_id=$request->docter;
    //            $pa->dep_id=$request->department;
    //            $pa->surgery_id=null;
    //            $pa->save();
    //            return response()->json(['success'=>'Operation updated successfully']);

    //            } 
    //     }
    //     if($request->type=="normal delivery"){
    //         $valid=$request->validate([
    //             'patient'=>'required',
    //             'type'=>'required',
    //             'department'=>'required',
    //             'docter'=>'required',
    //             'date'=>'required',
    //             'time'=>'required'
    //         ]);
    //            if($valid){
    //             $pa=patientOperation::find($request->surg_id);
    //             $pa->type=$request->type;
    //             $pa->date=$request->date;
    //             $pa->time=$request->time;
    //             $pa->patient_id=$request->patient;
    //             $pa->emp_id=$request->docter;
    //             $pa->dep_id=$request->department;
    //             $pa->surgery_id=null;
    //             $pa->procedure_id=null;
    //             $pa->save();
    //             return response()->json(['success'=>'Operation updated successfully']);
    //            } 
    //     }
    //     if(empty($request->type)){
    //         $valid=$request->validate([
    //             'patient'=>'required',
    //             'type'=>'required',
    //             'department'=>'required',
    //             'docter'=>'required',
    //             'date'=>'required',
    //             'time'=>'required'
    //         ]);
    //     }
    // }


    public function destroy($id)
    {
        FinanceLog::where('identify','Surgery-D')->where('bill_id',$id)->delete(); 
        patientOperation::find($id)->delete();
        return response()->json(['success'=>'Operation deleted successfully']);

    }

    public function create_record()
    {
        $dep=Departments::select('department_name','dep_id')->get();
        $surgery=Surgery::select('surgery_id','surgery_name')->get();
        $procedure=procedure::select('procedure_id','procedure_name')->get();  
        $patient=Patients::select('patient_id','f_name','l_name','patient_idetify_number')
        ->orderBy('created_at','DESC')
        ->get();
        return view('admin.surgery.patient_p_create',compact('dep','surgery','procedure','patient'));
    }

    public function procedure_fees($id)
    {
        $procedure=procedure::find($id)->procedure_fees;  
        return response()->json($procedure);
    }
    public function surgery_fees($id)
    {
        $surgery=Surgery::find($id)->surgery_fees;
        return response()->json($surgery);
    }

    public function print_bill($id)
    {
    //    $patient= patientOperation::where('patient_s_del_pro_id',$id)->get();
       $operate=patientOperation::
       select('department_name','employees.f_name as emp_f_name','employees.l_name as emp_l_name'
       ,'patients.f_name','patients.l_name','patient_idetify_number','users.email','patient_operations.*')
       ->join('departments','departments.dep_id','patient_operations.dep_id')
       ->join('employees','employees.emp_id','patient_operations.emp_id')
       ->join('patients','patients.patient_id','patient_operations.patient_id')
       ->join('users','users.id','patient_operations.author')
       ->groupBy('patient_operations.patient_s_del_pro_id')
       ->where('patient_s_del_pro_id',$id)->get();

       if(!empty($operate[0]->surgery_id)){
        $surg=helper::getsurgery($operate[0]->patient_s_del_pro_id); 
        $op_name=$surg[0]->surgery_name;
       }
       
        if (!empty($operate[0]->procedure_id)){
          $proc=helper::getprocedure($operate[0]->patient_s_del_pro_id); 
          $op_name=$proc[0]->procedure_name;
        }
        if (empty($operate[0]->surgery_id) && empty($operate[0]->procedure_id)){
            if($operate[0]->type=="normal delivery"){
                $op_name='Normal Delivery';

            }else{
                $op_name=$operate[0]->type;
            }
          }
       
        $show='
        <div style="display:flex;margin-top: 20px">
        <div style="width:50%;text-align: left">
            <div class="form-group ">
                <label>Bill Number #: <strong id="bill_no1">'.$operate[0]->operate_no.'</strong></label>
            </div>
        </div>
        <div style="width: 50%;text-align:right">
            <div class="form-group float-right">
                <label>Register Date: <strong id="bill_date1">'.$operate[0]->created_at.'</strong></label>
            </div>
        </div>
      </div>
      <div style="width:50%;text-align: left;margin-top:10px">
      <div class="form-group ">
          <label>Patient Name : <strong >'.$operate[0]->f_name.' '.$operate[0]->l_name.'</strong></label>
      </div>
     </div>
     

  <div class="row p-4 table-sm table " style="margin-top: 20px">
  <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
      <tbody>
 
          <tr>
              <th>Docter Name:</th>
              <td ><small>'.$operate[0]->emp_f_name.' '.$operate[0]->emp_l_name.'</small></td>
              <th>Operation Type:</th>
              <td ><small>'.$operate[0]->type.'</small></td>
          </tr>
          <tr>
          <th>Operation Name:</th>
          <td ><small>'.$op_name.'</small></td>
          <th>Department:</th>
          <td ><small>'.$operate[0]->department_name.'</small></td>
          </tr>
          <tr>
          <th>Time:</th>
          <td ><small>'.$operate[0]->time.'</small></td>
          <th>Date:</th>
          <td ><small>'.$operate[0]->date.'</small></td>
          </tr>
          <tr>
          <th>Fees:</th>
          <td colspan="3"><small>'.$operate[0]->fees.'</small></td>
          </tr>
      </tbody>
  </table>
 </div>
    </div>';
    $qrlink="<img src='data:image/png;base64,".DNS1D::getBarcodePNG(strval($operate[0]->operate_no), 'C39',1,35,array(0,0,0), true)."'/>";
    $arr=array(
     'html'=>$show,
     'qr'=>$qrlink,
   );
   return response()->json($arr);
 
    }
}
