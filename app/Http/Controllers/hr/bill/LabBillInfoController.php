<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\FinanceLog;
use App\Models\LabBill;
use App\Models\LabBill_info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabBillInfoController extends Controller
{
    
    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'department'=>'required',
            'test'=>'required',
            'amount'=>'required',
        ]);
         if($datavalidate){
            $bill= new LabBill_info;
            $bill->bill_id=$request->bill_id;
            $bill->test_id=$request->test;
            $bill->total=$request->amount;
            $bill->author=Auth::id();
            $bill->save();
              
            $total=LabBill_info::where('bill_id',$request->bill_id)->sum('total');
            $discount=LabBill::find($request->bill_id)->discount;
            $discountamount=$discount*$total/100;
            $nettotal=$total-$discountamount;
            $total=$nettotal;

           $fin=FinanceLog::where('bill_id',$request->bill_id)->where('payment_type',"Laboratory bill payment")
           ->update(['total'=>$total,'status'=>'Paid']);

          return  response()->json(['success'=>'Test added to bill successfully']);
        }   

    }

    public function bill_info_detail($id)
    {
        $info=LabBill_info::
          select('department_name','tests.test_type','lab_bill_infos.*')
        ->join('tests','tests.test_id','lab_bill_infos.test_id')
        ->join('departments','departments.dep_id','tests.dep_id')
        ->where('bill_id',$id)->get();
      
        $discount=LabBill::find($id)->discount;
        if($discount==null){
            $discount=0;
        }
        $totals=LabBill_info::where('bill_id',$id)->sum('total');

        return Response()->json(array(
            'info' => $info,
            'totals' => $totals,
            'discount'=>$discount,
        ));

    }

    public function getlab_info_edit($id)
    {
        $info=LabBill_info::where('lab_bill_ifo_id',$id)
        ->join('tests','tests.test_id','lab_bill_infos.test_id')
        ->groupBy('dep_id')
        ->get();

        return response()->json($info);

    }


    public function updatelab_info_edit(Request $request)
    {
        $datavalidate=$request->validate([
            'department'=>'required',
            'test'=>'required',
            'amount'=>'required',
        ]);
        if($datavalidate){
            $lab=LabBill_info::find($request->bill_ids);


            $total=LabBill_info::where('bill_id',$lab->bill_id)->sum('total');

            $f=(int)$total-(int)$lab->total;
            $total=$f+$request->amount;
            
            $discount=LabBill::find($lab->bill_id)->discount;
            $discountamount=$discount*$total/100;
            $nettotal=$total-$discountamount;
            $total=$nettotal;

            
            FinanceLog::where('bill_id',$lab->bill_id)
            ->where('payment_type',"Laboratory bill payment")
            ->update(['total'=>$total]);
            // endfinance
          
            $lab=LabBill_info::find($request->bill_ids);
            $lab->test_id=$request->test;
            $lab->total=$request->amount;
            $lab->save();

            return  response()->json(['success'=>'bill edited successfully']);
          }  

        }
    
        public function destroy($id)
        {

            $bill=LabBill_info::find($id);
        
            $finance=FinanceLog::where('payment_type','Laboratory bill payment')->where('bill_id',$bill->bill_id)->get();
            if($finance[0]->total <= $bill->total){
                $total=$finance[0]->total - $bill->total;
                $finance=FinanceLog::
                  where('payment_type','Laboratory bill payment')
                  ->where('bill_id',$bill->bill_id)
                  ->update(['status'=>'Pending','total'=>$total]);
    
            }else{
                $total=$finance[0]->total - $bill->total;
                $finance=FinanceLog::where('payment_type','Laboratory bill payment')
                ->where('bill_id',$bill->bill_id)
                ->update(['total'=>$total]);
            }
            $bill->delete();

        
        }
}
