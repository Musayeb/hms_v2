<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\companyBillInfo;
use App\Models\companyBillInfo1;
use App\Models\FinanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyBillInfoController extends Controller
{
    public function store(Request $request)
    {
        $validate=$request->validate([
            'bill_number'=>'required',
            'receiver_name'=>'required',
            'pay_amount'=>'required',
        ]);

        if($validate){
            
            $bill=new companyBillInfo;
            $bill->bill_number=$request->bill_number;
            $bill->receiver_name=$request->receiver_name;
            $bill->paid_amount=$request->pay_amount;
            $bill->company_bill_id1=$request->company_bill;
            $bill->receipt_number=$request->receipt_number;
            $bill->author=Auth::id();
            $bill->save();
           
           $fin= new FinanceLog();
           $fin->payment_type="Medical company bill payment";
           $fin->bill_id=$bill->company_bill_id;
           $fin->total=$request->pay_amount;
           $fin->status="Paid";
           $fin->type="Expense";
           $fin->author=Auth::id();
           $fin->save();

            
            return response()->json(['success'=>'Bill Generated Successfully']);
        }
    }

    public function getinfo($id){
        $bill=companyBillInfo::
        select('company_bills.company_name','users.email','company_bill_infos.*')
        ->join('users','users.id','company_bill_infos.author')
        ->join('company_bills','company_bills.company_bill_id','company_bill_infos.company_bill_id1')
        ->where('company_bill_infos.company_bill_id',$id)->get();

        $total=companyBillInfo::select(DB::raw('Sum(paid_amount)as paid'))->where('company_bill_infos.company_bill_id1',$bill[0]->company_bill_id1)->get();
        $total1=companyBillInfo1::select(DB::raw('Sum(AMOUNT)as total'))
        ->where('company_bill_info1s.company_bill_id',$bill[0]->company_bill_id1)
        ->get();

    if(!$total1[0]->total>=$total[0]->paid){
        $totalss=0;
    }else{
      $totalss=$total1[0]->total-$total[0]->paid;
    }

        $div='<div style="display:flex;margin-top: 20px">
        <div style="width:50%;text-align: left">
            <div class="form-group ">
                <label>Bill #: <strong id="bill_no1">'.$bill[0]->bill_number.'</strong></label>
            </div>
        </div>
        <div style="width: 50%;text-align:right">
            <div class="form-group float-right">
                <label>Date: <strong id="bill_date1">'.$bill[0]->created_at.'</strong></label>
            </div>
        </div>
    </div>
    <div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Receiver Name</th>
                    <td><small >'.$bill[0]->receiver_name.'</small></td>
                </tr>
            </tbody>
        </table>
    </div>

   
    <div class="row " style="margin-top:20px;width:100%">
        <div style="display:flex">
        <table class="printablea4 table" id="testreport" style="width:100%">
            <tbody>
                <tr>
                <th align="left">Company Name</th>
                <td ><small >'.$bill[0]->company_name.'</small></td>
            </tr>
            <tr>
                <th align="left">Total Amount</th>
                <td ><small >'.$total1[0]->total.'</small></td>

                
            </tr>
            <tr>
                <th align="left">Paid Amount</th>
                <td ><small>'.$total[0]->paid.'</small></td>
            </tr>
            <tr>
                <th align="left">Due Amount</th>
                <td ><small>'.$totalss.'</small></td>       
                                            
            </tr>
            </tbody>
        </table>
        
    </div>
    <div class="row p-4" style="margin-top:20px;width:100%">
        <div style="display:flex">
        <table class="printablea4 table" id="testreport" style="width:100%">
            <tbody>
                <tr>
                    <th width="20%">Issue By</th>
                    <td id="by">'.$bill[0]->email.'</td>

                </tr>
            </tbody>
        </table>
        
    </div>
</div>
';
return response()->json($div);

    }


    public function edit($id)
    {
        $bill=companyBillInfo::where('company_bill_id',$id)->get();
        return response()->json($bill);

    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'bill_number'=>'required',
            'receiver_name'=>'required',
            'pay_amount'=>'required',
        ]);

        if($validate){

        $bill=companyBillInfo::find($request->company_bill);
        $bill->bill_number=$request->bill_number;
        $bill->receiver_name=$request->receiver_name;
        $bill->paid_amount=$request->pay_amount;
        $bill->receipt_number=$request->receipt_number;
        $bill->save();

        $fin=FinanceLog::where('bill_id',$request->company_bill)
        ->where('payment_type','Medical company bill payment')
        ->update(['total'=>$request->pay_amount]);

        return response()->json(['success'=>'Bill update Successfully']);
     }

    }

    public function destroy($id)
    {
        FinanceLog::where('bill_id',$id)->where('payment_type','Medical company bill payment')->delete();
        companyBillInfo::find($id)->delete();

    }

    public function store1(Request $request)
    {
        $validate=$request->validate([
            'receipt_number'=>'required',
            'total_amount'=>'required',
            'date'=>'required',
        ]);

        if($validate){
            $bill = new companyBillInfo1();
            $bill->receipt_number=$request->receipt_number;
            $bill->amount=$request->total_amount;
            $bill->date=$request->date;
            $bill->company_bill_id=$request->company_bill;
            $bill->author=Auth::id();
            $bill->save();

            return response()->json(['success'=>'Bill Generated Successfully ! ']);
        }
    }

    public function edit1($id)
    {
        $bill = companyBillInfo1::find($id);
        return response()->json($bill);
    }

    public function update1(Request $request)
    {
        $validate=$request->validate([
            'receipt_number'=>'required',
            'total_amount'=>'required',
            'date'=>'required',
        ]);
        if($validate){
            $bill =companyBillInfo1::find($request->bill_id);
            $bill->receipt_number=$request->receipt_number;
            $bill->amount=$request->total_amount;
            $bill->date=$request->date;
            $bill->save();

            return response()->json(['success'=>'Bill updated Successfully ! ']);
        }
    }

    public function delete($id)
    {
        companyBillInfo1::find($id)->delete();
    }
    
}
