<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\FinanceLog;
use App\Models\LabBill;
use App\Models\LabBill_info;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DNS1D;

class LabBillInfoController extends Controller
{
    
    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'test'=>'required',
        ]);
         if($datavalidate){
            $test=Test::find($request->test);
            
            $bill= new LabBill_info;
            $bill->bill_id=$request->bill_id;
            $bill->test_id=$request->test;
            $bill->total=$test->fees;
            $bill->author=Auth::id();
            $bill->save();
              
            $total=LabBill_info::where('bill_id',$request->bill_id)->sum('total');
            $discount=LabBill::find($request->bill_id)->discount;
            $discountamount=$discount*$total/100;
            $nettotal=$total-$discountamount;
            $total=$nettotal;

            $bill=LabBill::find($request->bill_id);
            $bill->total=$total;
            $bill->save();

           $fin=FinanceLog::where('bill_id',$request->bill_id)
           ->where('payment_type',"Laboratory bill payment")
           ->update(['total'=>$total,'status'=>'Paid']);

          return  response()->json(['success'=>'Test added to bill successfully']);
        }   

    }

    public function bill_info_detail($id)
    {
        $bill=LabBill::find($id);
        $info=LabBill_info::
          select('tests.test_type','lab_bill_infos.*')
        ->join('tests','tests.test_id','lab_bill_infos.test_id')
        ->where('bill_id',$id)->get();

        $qrlink="<img src='data:image/png;base64,".DNS1D::getBarcodePNG(strval($bill->bill_no), 'C39',1,55,array(0,0,0), true)."'/>";
        $date=date('Y-m-d h:i:s a', strtotime($bill->created_at));

        $total=LabBill_info::where('bill_id',$id)->sum('total');
        $discount=LabBill::find($id)->discount;
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total1=$nettotal;

        return Response()->json(array(
            'info' => $info,
            'bill' => $bill,
            'qr'=>$qrlink,
            'date'=>$date,
            'total'=>$total1
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
            'test'=>'required',
        ]);
        if($datavalidate){
            $test=Test::find($request->test);
            
            $lab=LabBill_info::find($request->bill_ids);
            $lab->test_id=$request->test;
            $lab->total=$test->fees;
            $lab->save();

            $total=LabBill_info::where('bill_id',$lab->bill_id)->sum('total');
            
            $discount=LabBill::find($lab->bill_id)->discount;
            $discountamount=$discount*$total/100;
            $nettotal=$total-$discountamount;
            $total=$nettotal;
            
            $lab=LabBill::find($lab->bill_id);
            $lab->total=$total;
            $lab->save();

            FinanceLog::where('bill_id',$lab->bill_id)
            ->where('payment_type',"Laboratory bill payment")
            ->update(['total'=>$total]);
            // endfinance

            return  response()->json(['success'=>'bill updated successfully']);
          }  

        }
    
        public function destroy($id)
        {

            $bill=LabBill_info::find($id);
        
            $total=LabBill_info::where('bill_id',$bill->bill_id)->sum('total');
        

            if($total <= $bill->total){
                $finance=FinanceLog::where('payment_type','Laboratory bill payment')->where('bill_id',$bill->bill_id)->update(['total'=>0]);

            }else{
                 $netamountbeforprecentage=(int)$total-(int)$bill->total;
                
                 $discount=LabBill::find($bill->bill_id)->discount;
                 $discountamount=$discount* $netamountbeforprecentage/100;
                 $nettotal=$netamountbeforprecentage-$discountamount;
                 $total=$nettotal;

                $finance=FinanceLog::where('payment_type','Laboratory bill payment')
                ->where('bill_id',$bill->bill_id)
                ->update(['total'=>$total]);
                
                $billa=LabBill::find($bill->bill_id);
                $billa->total=$total;
                $billa->save();

            }
            $bill->delete();

        
        }
}
