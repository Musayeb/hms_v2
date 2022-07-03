<?php

namespace App\Http\Controllers\hr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\FinanceLog;
use App\Models\PartialPaymentBilling;
use App\Models\Patients;
use Illuminate\Support\Facades\Auth;

class PartialPaymentBillingController extends Controller
{
    public function index(){
        $department=Departments::select('department_name','dep_id')->get();

        $part_bill=PartialPaymentBilling::
         select('users.email','departments.department_name','patients.f_name','patients.l_name','partial_payment_billings.*')
        ->join('patients','patients.patient_id','partial_payment_billings.patient_id')
        ->join('departments','departments.dep_id','partial_payment_billings.department')
        ->join('users','users.id','partial_payment_billings.author')

        ->orderBy('partial_payment_billings.created_at','DESC')->paginate(50);

        return view('admin.billing.partialPaymentBilling',compact('part_bill','department'));
    }

    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'doctor_name'=>'required',
            'doctor_phone'=>'required',
            'patient_name'=>'required',
            'department'=>'required',
            'date'=>'required',
            'docter_charges'=>'required',
            'description'=>'required',
        ]);
        if($datavalidate)
        {
            $part_bill=new PartialPaymentBilling;
            $part_bill->doctor_name=$request->doctor_name;
            $part_bill->bill_number=$request->bill_number;
            $part_bill->doctor_phone_number=$request->doctor_phone;
            $part_bill->patient_id=$request->patient_name;
            $part_bill->department=$request->department;
            $part_bill->date=$request->date;
            $part_bill->docter_charges=$request->docter_charges;
            $part_bill->description=$request->description;
            $part_bill->author=Auth::id();
            $part_bill->save();

             
            $fin= new FinanceLog();
            $fin->payment_type="Partial bill payment";
            $fin->bill_id=$part_bill->id;
            $fin->total=$request->docter_charges;
            $fin->status="Paid";
            $fin->type="Expense";
            $fin->author=Auth::id();
            $fin->save();
            
            return response()->json(['success'=>'Partial Bill added successfully']);    
        }
    }

    public function edit($id)
    {
        $part_bill_data=PartialPaymentBilling::find($id);
        return Response()->json($part_bill_data);  
    }

    public function print($id)
    {
        $part_bill=PartialPaymentBilling::where('partial_payment_billings.id',$id)->
        select('users.email','departments.department_name','patients.f_name','patients.l_name','partial_payment_billings.*')
        ->join('patients','patients.patient_id','partial_payment_billings.patient_id')
        ->join('departments','departments.dep_id','partial_payment_billings.department')
        ->join('users','users.id','partial_payment_billings.author')
        ->get(); 
        $part_bill=$part_bill[0];


        $print='<div style="display:flex;margin-top: 20px">
        <div style="width:50%;text-align: left">
            <div class="form-group ">
                <label>Bill #: <strong id="bill_no1">'.$part_bill->bill_number.'</strong></label>
            </div>
        </div>
        <div style="width: 50%;text-align:right">
            <div class="form-group float-right">
                <label>Date: <strong id="bill_date1">'.$part_bill->date.'</strong></label>
            </div>
        </div>
    </div>
    <div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Docter Name</th>
                    <td ><small>'.$part_bill->doctor_name.'</small></td>
                    <th>Patient Name</th>
                    <td ><small>'.$part_bill->f_name.' '.$part_bill->l_name.'</small></td>
                    <th >Department</th>
                    <td ><small>'.$part_bill->department_name.'</small></td>                                                                
                </tr>
                <tr>
                    <th>Docter Charges</th>
                    <td ><small >'.$part_bill->docter_charges.'</small></td>   
                 
                    <th>Description</th>
                    <td colspan="4" ><small >'.$part_bill->description.'</small></td>                                    
                </tr>

            </tbody>
        </table>
    </div>
    <div class="row p-4" style="margin-top:20px">
        <div style="display:flex">
        <table class="printablea4 table" id="testreport" style="width:70%">
            <tbody>
                <tr>
                    <th width="20%">Issue By</th>
                    <td id="by">'.$part_bill->email.'</td>
                </tr>
            </tbody>
        </table>
        <table class="printablea4" style="width: 30%; float: right;">
            <tbody>
                <tr>
                    <th>Total</th>
                    <td id="totals1">'.$part_bill->docter_charges.'</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>';
        return response()->json($print);
    }
    public function update(Request $request)
    {
        $datavalidate=$request->validate([
            'doctor_name'=>'required',
            'doctor_phone'=>'required',
            'patient_name'=>'required',
            'department'=>'required',
            'date'=>'required',
            'docter_charges'=>'required',
            'description'=>'required',
        ]);
        if($datavalidate)
        {
            $part_bill=PartialPaymentBilling::find($request->hidden_id);
            $part_bill->doctor_name=$request->doctor_name;
            $part_bill->bill_number=$request->bill_number;
            $part_bill->doctor_phone_number=$request->doctor_phone;
            $part_bill->patient_id=$request->patient_name;
            $part_bill->department=$request->department;
            $part_bill->date=$request->date;
            $part_bill->docter_charges=$request->docter_charges;
            $part_bill->description=$request->description;
            $part_bill->save();
            
            $fin=FinanceLog::
              where('bill_id',$request->hidden_id)
            ->where('payment_type','Partial bill payment')
            ->update([
                'total'=>$request->services_charges+$request->facility_charges
            ]);

            
            return response()->json(['success'=>'Partial Bill updated successfully']);  
        }
    }

    public function destroy($id)
    {
        $sur=PartialPaymentBilling::find($id)->delete();
        return response()->json(['success'=>'Record deleted successfully']);    
    }

    public function show($id)
    {
        $part_bill=PartialPaymentBilling::where('partial_payment_billings.id',$id)->
        select('users.email','departments.department_name','patients.f_name','patients.l_name','partial_payment_billings.*')
        ->join('patients','patients.patient_id','partial_payment_billings.patient_id')
        ->join('departments','departments.dep_id','partial_payment_billings.department')
        ->join('users','users.id','partial_payment_billings.author')
        ->get(); 
        $part_bill=$part_bill[0];

        $show='<div style="display:flex;margin-top: 20px">
        <div style="width:50%;text-align: left">
            <div class="form-group ">
                <label>Bill #: <strong id="bill_no1">'.$part_bill->bill_number.'</strong></label>
            </div>
        </div>
        <div style="width: 50%;text-align:right">
            <div class="form-group float-right">
                <label>Date: <strong id="bill_date1">'.$part_bill->date.'</strong></label>
            </div>
        </div>
    </div>
    <div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Docter Name:</th>
                    <td ><small>'.$part_bill->doctor_name.'</small></td>
                    <th>Patient Name:</th>
                    <td ><small>'.$part_bill->f_name.' '.$part_bill->l_name.'</small></td>
                    <th >Department</th>
                    <td ><small>'.$part_bill->department_name.'</small></td>                                                                
                </tr>
                <tr>
                    <th>Docter Charges:</th>
                    <td ><small >'.$part_bill->docter_charges.'</small></td>   
               
                    <th>Description:</th>
                    <td ><small >'.$part_bill->description.'</small></td>                                    
                </tr>
                <tr>
                <th>Docter Phone Number:</th>
                <td ><small >'.$part_bill->doctor_phone_number.'</small></td>   
               
                <th>Generate Date</th>
                <td ><small >'.$part_bill->created_at.'</small></td>   
                                         
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
                    <td id="by">'.$part_bill->email.'</td>
                </tr>
            </tbody>
        </table>
        <table class="printablea4" style="width: 30%; float: right;">
            <tbody>
                <tr>
                    <th>Total</th>
                    <td id="totals1">'.$part_bill->docter_charges.'</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>';

    return response()->json($show);
    }


    public function getPatient($id)
    {
        $patient=Patients::select('f_name','l_name','patient_idetify_number','patient_id')->where('dep_id',$id)->get();
        return response()->json($patient);    
    }
}
