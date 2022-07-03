<?php

namespace App\Http\Controllers\hr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmissionBill_info;
use App\Models\companyBillInfo;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\EndOfTheDay;
use App\Models\ExtraIncome;
use App\Models\FinanceLog;
use App\Models\LabBill_info;
use App\Models\NurseBill;
use App\Models\OvertimePay;
use App\Models\PartialPaymentBilling;
use App\Models\Pettycash;
use App\Models\pharmaBill;
use App\Models\pharmaBill_info;
use Illuminate\Support\Facades\Auth;

class EndOfTheDayController extends Controller
{
    
    public function index()
    {
        $eod=EndOfTheDay::
        select('users.name as user_name','end_of_the_days.id','end_of_the_days.user_id','end_of_the_days.created_at','end_of_the_days.updated_at')
        ->join('users','users.id','end_of_the_days.user_id')
        ->where('end_of_the_days.user_id',Auth::id())
        ->orderBy('created_at','DESC')
        ->paginate(50);

        return view('admin.endoftheday',compact('eod'));
    }

    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'total_expense'=>'required',
            'total_income'=>'required',
        ]);
        if($datavalidate)
        {
            $eod=new EndOfTheDay();
            $eod->bill_number=$request->bill_number;
            $eod->user_id=Auth::id();
            $eod->total_expense=$request->total_expense;
            $eod->total_income=$request->total_income;
            $eod->save();
            // $eod=EndOfTheDay::select('users.name as user_name','end_of_the_days.id','end_of_the_days.bill_number','end_of_the_days.user_id','end_of_the_days.total_expense','end_of_the_days.total_income','end_of_the_days.created_at','end_of_the_days.updated_at')
            // ->join('users','users.id','end_of_the_days.user_id')->orderBy('created_at','DESC')->get();

            return response()->json(['success'=>'End Of The Day added successfully']); 
        }
    }

    public function update(Request $request)
    {
        $datavalidate=$request->validate([
            'total_expense'=>'required',
            'total_income'=>'required',
        ]);
        if($datavalidate)
        {
            $eod=EndOfTheDAy::find($request->hidden_id);
            $eod->bill_number=$request->bill_number;
            $eod->total_expense=$request->total_expense;
            $eod->total_income=$request->total_income;
            $eod->updated_at=now();
            $eod->save();
            return response()->json(['success'=>'End Of The Day updated successfully']);
        }
       
    }

    public function destroy($id)
    {
        EndOfTheDAy::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);    
    }

    public function show($id)
    {
        
        $end=EndOfTheDay::find($id);
        $totalincome=$end->opd+$end->pharmacy+$end->laboratory+$end->admission+$end->nursebill+$end->extra_income;
        $totalExpense=$end->dailyexpenses+$end->medicalcompany+$end->partialpayment+$end->overtimepayment;

        $email=User::find(Auth::id())->email;

        $print='<div style="display:flex;margin-top: 20px">
        <div style="width:50%;text-align: left">
    
        </div>
        <div style="width: 50%;text-align:right">
            <div class="form-group float-right">
                <label>Date: <strong id="bill_date1">'.$end->created_at.'</strong></label>
            </div>
        </div>
    </div>
    <div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tr>
            <th colspan="2">Income</th>
            <th colspan="4" >Expense</th>
            </tr>
            <tbody>
            <tr>
            <td>OPD Income </td>
            <td>'.$end->opd.'</td>
            <td>Medical Company Payment</td>
            <td>'.$end->medicalcompany.'</td>
            <tr>
            <tr>
            <td>Pharmacy Income</td>
            <td>'.$end->pharmacy.'</td>
            <td>Dialy Expenses Payment</td>
            <td>'.$end->dailyexpenses.'</td>
            <tr>
            <td>Laboratory Income</td>
            <td>'.$end->laboratory.'</td>
            <td>Staff Over Time Payment</td>
            <td>'.$end->overtimepayment.'</td>
            </tr>
            
            <tr>
            <td>Admisssion Income</td>
            <td>'.$end->admission.'</td>
            <td rowspan="3">Partial Payment Payment</td>
            <td rowspan="3">'.$end->partialpayment.'</td>            
            </tr>
            <tr>
            <td>Nurse Income</td>
            <td>'.$end->nursebill.'</td>
            </tr>
            <tr>
            <td>Extra Income</td>
            <td>'.$end->extra_income.'</td>    
            </tr>
            <tr>
                <td ><strong>Total Income</strong></td>
                <td ><strong id="print_total_income">'.$totalincome.'</strong></td>
                <td ><strong>Total Expense</strong></th>
                <td><strong id="print_total_expense">'.$totalExpense.'</strong></td>
            </tr>
            </tbody>
        </table>
        
    </div>
    <div class="row p-4" style="margin-top:20px">
        <div style="display:flex">
      <div style="width:50%">
      <p>Issue By:  '.$email.' </p>
      </div>
      <div style="width:50%">
      <p>Finance Manager: </p>
      </div>     
    </div>
    </div>';
    return response()->json($print);

    }

    public function calculate(){
    
// Pharmacy Bill  ////////////////////////////////////////////////////////////////////
    
      function getpharmacy(){
        $pht1=0;
          $ph=pharmaBill_info::
          select('pharma_bills.discount','pharma_bill_infos.total')
          ->join('pharma_bills','pharma_bill_infos.bill_id','pharma_bills.bill_id')
          ->where('pharma_bill_infos.author',Auth::id())
          ->where('eod_count',0)
          ->get();

          foreach ($ph as $row){
            $total=$row->total;
            $discount=$row->discount;
            $pht= $discount*$total/100;
            $pht1+=$total-$pht;
          }
          return $pht1;
        } 
        $eod= new EndOfTheDay;
        $eod->pharmacy=getpharmacy();
        $eod->user_id=Auth::id();
        $eod->save();
        $eod_id=$eod->id;
         // update
         pharmaBill_info::where('pharma_bill_infos.author',Auth::id())
          ->where('eod_count',0)
          ->update(['eod_count'=>1]);

// End pharmacy Bill  ////////////////////////////////////////////////////////////////////

// Laboratory Bill  ////////////////////////////////////////////////////////////////////
    function getlaboratory(){
        $lab2=0;
       $lab=LabBill_info::
        select('lab_bills.discount','lab_bill_infos.total')
        ->join('lab_bills','lab_bill_infos.bill_id','lab_bills.bill_id')
        ->where('lab_bill_infos.author',Auth::id())
        ->where('eod_count',0)
        ->get();
        // dd($lab);
        foreach ($lab as $rows){
            $total=$rows->total;
            $discount=$rows->discount;
            $lab1= $discount*$total/100;
            $lab2+=$total-$lab1;
          }
          return $lab2;
        
    } 
    $eod=EndOfTheDay::find($eod_id);
    $eod->laboratory=getlaboratory();
    $eod->save();
    LabBill_info::where('lab_bill_infos.author',Auth::id())
        ->where('eod_count',0)
        ->update(['eod_count'=>1]);

// End Laboratory Bill  ////////////////////////////////////////////////////////////////////

// Admission Bill  ////////////////////////////////////////////////////////////////////
    function getadmission(){
        $adm1=0;
       $ad=AdmissionBill_info::
        select('admission_bills.discount','admission_bill_infos.amount')
        ->join('admission_bills','admission_bill_infos.admission_id','admission_bills.admission_id')
        ->where('admission_bill_infos.author',Auth::id())
      ->where('eod_count',0)
        ->get();
        foreach ($ad as $rows1){
            $total=$rows1->amount;
            $discount=$rows1->discount;
            $adm= $discount*$total/100;
            $adm1+=$total-$adm;
          }
          return $adm1;
    } 
    $eod=EndOfTheDay::find($eod_id);
    $eod->admission=getadmission();
    $eod->save();
    // UPDATE
    AdmissionBill_info::where('admission_bill_infos.author',Auth::id())
      ->where('eod_count',0)
      ->update(['eod_count'=>1]);
// End Admission Bill  ////////////////////////////////////////////////////////////////////

// Over Time Bill  ////////////////////////////////////////////////////////////////////

    function getovert(){
     $over=OvertimePay::where('author',Auth::id())
     ->where('eod_count',0)
     ->sum('total_amount');  
       return $over;
    } 
    $eod=EndOfTheDay::find($eod_id);
    $eod->overtimepayment=getovert();
    $eod->save();
    // UPDATE
    OvertimePay::where('author',Auth::id())
     ->where('eod_count',0)
     ->update(['eod_count'=>1]);
// End Over time  Bill  ////////////////////////////////////////////////////////////////////

// Nurse Bill  ////////////////////////////////////////////////////////////////////
    function nursebill(){
        $nurse=NurseBill::where('author',Auth::id())->where('eod_count',0)->sum('fees');  
        return $nurse;
     } 
     
     $eod=EndOfTheDay::find($eod_id);
     $eod->nursebill=nursebill();
     $eod->save();
    // update
    NurseBill::where('author',Auth::id())->where('eod_count',0)->update(['eod_count'=>1]);
// End Nurse Bill  ////////////////////////////////////////////////////////////////////

// Partial Bill  ////////////////////////////////////////////////////////////////////
 
    function partialbill(){
        $partial=PartialPaymentBilling::where('author',Auth::id())
           ->where('eod_count',0)
           ->sum('docter_charges');  
        return $partial;
    } 
    $eod=EndOfTheDay::find($eod_id);
     $eod->partialpayment=partialbill();
     $eod->save();
     // update
      PartialPaymentBilling::where('author',Auth::id())
           ->where('eod_count',0)->update(['eod_count'=>1]);
// end Partial Bill  ////////////////////////////////////////////////////////////////////

// Company Bill ////////////////////////////////////////////////////////////////////
     
    function companybill(){
        $companybill=companyBillInfo::where('author',Auth::id())->where('eod_count',0)
        ->sum('paid_amount');  
        return $companybill;
    } 
     $eod=EndOfTheDay::find($eod_id);
     $eod->medicalcompany=companybill();
     $eod->save();
   // update
    companyBillInfo::where('author',Auth::id())->where('eod_count',0)->update(['eod_count'=>1]);

// End Company  ////////////////////////////////////////////////////////////////////

// extra income  ////////////////////////////////////////////////////////////////////
     
     function extraincome(){
        $extraincome=ExtraIncome::where('author',Auth::id())
          ->where('eod_count',0)
          ->sum('pay_amount');  
        return $extraincome;
    } 
     $eod=EndOfTheDay::find($eod_id);
     $eod->extra_income=extraincome();
     $eod->save();
     // update
     ExtraIncome::where('author',Auth::id())
       ->where('eod_count',0)->update(['eod_count'=>1]);
// end extra income  ////////////////////////////////////////////////////////////////////
// Daily Expenses   ////////////////////////////////////////////////////////////////////

     function dailyexpenses(){
        $daily=Pettycash::where('author',Auth::id())->where('status','Approved')
                  ->where('eod_count',0)->sum('amount');  
        return $daily;
    } 
    // update


     $eod=EndOfTheDay::find($eod_id);
     $eod->dailyexpenses=dailyexpenses();
     $eod->save();
     
     Pettycash::where('author',Auth::id())->where('status','Approved')
          ->where('eod_count',0)->update(['eod_count'=>1]);
          

// end daily expenses //////////////////////////////////////////////////////////////////


// opd ///////////////////////////////////////////////////////////////////////////////
     function opd(){
        $visit=DB::table('visit')->where('author',Auth::id())->where('status','Paid')
        ->where('eod_count',0)
        ->sum('fees');  
        $daily=DB::table('patient_test')->where('author',Auth::id())
        ->where('status','Paid')
        ->where('eod_count',0)
       ->sum('fees');  
        return $daily+$visit;
      } 

     $eod=EndOfTheDay::find($eod_id);
     $eod->opd=opd();
     $eod->save();
           // update
      DB::table('visit')->where('author',Auth::id())->where('status','Paid')
        ->where('eod_count',0)
        ->update(['eod_count'=>1]);
      DB::table('patient_test')->where('author',Auth::id())
        ->where('status','Paid')
        ->where('eod_count',0)
       ->update(['eod_count'=>1]);
// end opd ///////////////////////////////////////////////////////////////////////////////

    session()->flash('notif','End Of The Day created successfully');
    return back();        
    }
}
