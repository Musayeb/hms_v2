<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\AdmissionBill;
use App\Models\AdmissionBill_info;
use App\Models\FinanceLog;
use App\Models\patientOperation;
use App\Models\Patients;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionBillController extends Controller
{
    public function index()
    {
        $patient=patientOperation::
          join('patients','patients.patient_id','patient_operations.patient_id')
        ->select('f_name','l_name','patient_idetify_number','patient_operations.patient_id')
        ->orderBy('patient_operations.created_at','DESC')  
        ->get();

        $room=Room::select('room_type','room_number','room_fees','room_id')->get();

        $ad=AdmissionBill::
        select('patients.f_name as p_name','patients.l_name as p_last_name'
        ,'department_name','employees.f_name as e_name' , 'employees.l_name as e_last_name','users.email' 
        ,'admission_bills.*')->
          join('departments','departments.dep_id','admission_bills.dep_id')
        ->join('employees','employees.emp_id','admission_bills.emp_id')
        ->join('patients','patients.patient_id','admission_bills.patient_id')
        ->join('users','users.id','admission_bills.author')
        ->orderBy('admission_bills.created_at','DESC')
        ->paginate(50);

        return view('admin.billing.admission.bill',compact('patient','room','ad'));
    }

    public function store(Request $request)
    {
        $datavalid=$request->validate([
            'bill_number'=>'required',
            'admission_date'=>'required',
            'patient_name'=>'required',
            'deposit_amount'=>'required',
        ]);
        $ad=new AdmissionBill;
        $ad->bill_number=$request->bill_number;
        $ad->admission_date=$request->admission_date;
        $ad->patient_id=$request->patient_name;
        $ad->emp_id=$request->emp_id;
        $ad->dep_id=$request->dep_id;
        $ad->operate_type=$request->operate_type;
        $ad->operate_id=$request->id;
        $ad->deposit_amount=$request->deposit_amount;
        $ad->author=Auth::id();
        $ad->save();

        $fin= new FinanceLog();
        $fin->payment_type="Admission bill payment";
        $fin->bill_id=$ad->admission_id;
        $fin->total=null;
        $fin->status="Pending";
        $fin->identify="Admission";
        $fin->transaction=rand(11111111111111,99999999999999);
        $fin->author=Auth::id();
        $fin->save();
        
        return response(['success'=>'Admission Bill generated successfully']);
    }

    public function destroy($id)
    {
    AdmissionBill::find($id)->delete();
    } 
    public function edit ($id)
    {
       $bill= AdmissionBill::find($id);
       
       return response()->json($bill);
    }

    public function update(Request $request)
    {   
        $datavalid=$request->validate([
            'bill_number'=>'required',
            'admission_date'=>'required',
            'patient_name'=>'required',
            'deposit_amount'=>'required',
        ]);
        $ad=AdmissionBill::find($request->bill_id);
        $ad->bill_number=$request->bill_number;
        $ad->admission_date=$request->admission_date;
        $ad->patient_id=$request->patient_name;
        $ad->deposit_amount=$request->deposit_amount;
        $ad->save();
        return response(['success'=>'Admission Bill updated successfully']);

    }
    public function surgerygetdata($id)
    {
        $patient=patientOperation::where('patient_id',$id)
        ->orderBy('patient_operations.created_at','DESC')
        ->limit('1')
        ->get();
        return $patient;
    }

    public function getroomfees($id)
    {
        $room=Room::find($id);
        return response()->json($room);
    }

    public function addroomtobill(Request $request)
    {
        $datavalid=$request->validate([
            'room'=>'required',
            'room_fees'=>'required',
            'duration'=>'required',
            'total'=>'required',
        ]);
        if($datavalid){
           $info= new AdmissionBill_info();
           $info->charges_type=$request->room_info;
           $info->charge_description=$request->room_info.'-duration-'.$request->duration;
           $info->admission_id=$request->bill_id;
           $info->amount=$request->total;
           $info->author=Auth::id();
           $info->status="paid";
           $info->save();
           
        
           $total=AdmissionBill_info::where('admission_id',$request->bill_id)->sum('amount');
           $discount=AdmissionBill::find($request->bill_id)->discount;
           $discountamount=$discount*$total/100;
           $nettotal=$total-$discountamount;
           $total=$nettotal;


           $fin=FinanceLog::where('bill_id',$request->bill_id)
           ->where('payment_type','Admission bill payment')
           ->update(['status'=>'Paid','total'=>$total]);

           return response(['success'=>'Room added to Bill successfully']);
        }
    }

    public function bill_info_detail($id)
    {
        $info=AdmissionBill_info::where('admission_id',$id)->get();
        
        $discount=AdmissionBill::find($id)->discount;

        if($discount==null){
            $discount=0;
        }
        $totals=AdmissionBill_info::where('admission_id',$id)->sum('amount');

        return Response()->json(array(
            'info' => $info,
            'totals' => $totals,
            'discount'=>$discount,
        ));
    }

    public function bill_add_charges(Request $request)
    {
        $datavalid=$request->validate([
            'charge_type'=>'required',
            'charge_description'=>'required',
            'amount'=>'required',
        ]);              
        if($datavalid){
            $info= new AdmissionBill_info();
            $info->charges_type=$request->charge_type;
            $info->charge_description=$request->charge_description;
            $info->admission_id=$request->bill_id;
            $info->amount=$request->amount;
            $info->author=Auth::id();
            $info->status="paid";
            $info->save();

  
            $total=AdmissionBill_info::where('admission_id',$request->bill_id)->sum('amount');
            $discount=AdmissionBill::find($request->bill_id)->discount;
            $discountamount=$discount*$total/100;
            $nettotal=$total-$discountamount;
            $total=$nettotal;


            $fin=FinanceLog::where('bill_id',$request->bill_id)
            ->where('payment_type','Admission bill payment')
            ->update(['status'=>'Paid','total'=>$total]);

           return response(['success'=>'Charges added to Bill successfully']);

        }
    }

    public function bill_edit_charges(Request $request)
    {
        $datavalid=$request->validate([
            'charge_type'=>'required',
            'charge_description'=>'required',
            'amount'=>'required',
        ]);              
        if($datavalid){
           
            $info= AdmissionBill_info::find($request->bill_idss);
        
            $total=AdmissionBill_info::where('admission_id',$info->admission_id)->sum('amount');
            $f=(int)$total-(int)$info->amount;
            $total=$f+$request->amount;
            
            $discount=AdmissionBill::find($info->admission_id)->discount;

            $discountamount=$discount*$total/100;
            $nettotal=$total-$discountamount;
            $total=$nettotal;

            FinanceLog::where('bill_id',$info->admission_id)
            ->where('payment_type',"Admission bill payment")
            ->update(['total'=>$total]);
          
            $info= AdmissionBill_info::find($request->bill_idss);
            $info->charges_type=$request->charge_type;
            $info->charge_description=$request->charge_description;
            $info->amount=$request->amount;
            $info->save();
              

           return response(['success'=>'Charges Edit to Bill successfully']);
        }
    }

    public function bill_edit_charges_edit($id)
    {
        $data=AdmissionBill_info::find($id);
        return response()->json($data);
    }
    public function bill_delete_charges($id)
    {
        AdmissionBill_info::find($id)->delete();
    }

    public function admission_discount(Request $request)
    {
        $bill=AdmissionBill::find($request->bill_id);
        $bill->discount=$request->discount;
        $bill->save();


        $total=AdmissionBill_info::where('admission_id',$request->bill_id)->sum('amount');
        $discount=AdmissionBill::find($request->bill_id)->discount;
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total=$nettotal;

        $fin=FinanceLog::where('bill_id',$request->bill_id)
        ->where('payment_type',"Admission bill payment")
        ->update(['status'=>'Paid','total'=>$total]);


        return  response()->json(['success'=>'Discount Add Successfully']);
    }

    public function discharge($id)
    {
        AdmissionBill::where('admission_id',$id)
        ->update(['discharge_date'=>now(),'discharged_by'=>Auth::user()->email]);
        return response()->json(['success'=>'The admitted patient made discharged !']);
    }
}
    
