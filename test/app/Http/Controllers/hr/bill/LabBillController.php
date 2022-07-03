<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\LabBill;
use App\Models\Patients;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\FinanceLog;
use App\Models\LabBill_info;
use App\Models\Opd;
use App\Models\pharmaBill;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Helper;
use datatables;
class LabBillController extends Controller
{
    public function index()
    {
        $bill=LabBill::
        select('discount','users.email','bill_no','total','patient_name',
        'lab_bills.created_at as date','bill_id')
        ->join('users','users.id','lab_bills.author')
        ->groupBy('bill_id')
        ->orderBy('lab_bills.created_at','DESC')
        ->limit(1000);
        
        if(request()->ajax()) {
            return datatables()->of($bill)
            ->addColumn('action', 'company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $department=Departments::all();

       return view('admin.billing.laboratory.bill',compact('department'));
    }

    public function store(Request $request)
    {
        $validate=$request->validate([
            'patient_name'=>'required',
        ]);

        if($validate){
            $max=helper::getlabBillNo();

                $bill=new LabBill;
                $bill->bill_no=$max;
                $bill->patient_name=$request->patient_name;
                $bill->total=$request->netamount;
                $bill->discount=$request->discount;
                $bill->author=Auth::id();
                $bill->note=$request->note;
                $bill->save();

                $fin= new FinanceLog();
                $fin->payment_type="Laboratory bill payment";
                $fin->bill_id=$bill->bill_id;
                $fin->total=$request->netamount;
                $fin->status="Paid";
                $fin->identify="lab";
                $fin->transaction=rand(11111111111111,99999999999999);
                $fin->author=Auth::id();
                $fin->save();

               foreach($request->test_id as $index => $product) {
                $billinfo= new LabBill_info;
                $billinfo->bill_id=$bill->bill_id;
                $billinfo->test_id=$request->test_id[$index];
                $billinfo->total=$request->fees[$index];
                $billinfo->author=Auth::id();
                $billinfo->save();
                }
                $arr=array(
                    'id'=>$bill->bill_id,
                    'msg'=>'Lab Bill generated Successfully'
                );
                return response()->json($arr);


            }
    }


    public function edit($id)
    {
        $lab=LabBill::find($id);
       return response()->json($lab);
    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'patient_names'=>'required',
        ]);
        if($validate){

            $total=LabBill_info::where('bill_id',$request->bill_id)->sum('total');
            $total1=$request->discount*$total/100;   
            $final=$total-$total1;

            $bill=LabBill::find($request->bill_id);           
            $bill->patient_name=$request->patient_names;
            $bill->total=$final;
            $bill->discount=$request->discount;
            $bill->note=$request->note;
            $bill->save();

            $fin=FinanceLog::where('bill_id',$request->bill_id)
            ->where('payment_type',"Laboratory bill payment")
            ->update(['status'=>'Paid','total'=>$final]);

        $arr=array(
            'id'=>$request->bill_id,
            'msg'=>'Lab Bill updated Successfully'
        );
       
        return  response()->json($arr);
            
        }
        
    }

    function destroy($id)
    {
        LabBill::find($id)->delete();
        FinanceLog::where('payment_type','Laboratory bill payment')->where('bill_id',$id)->delete();   
    }
    public function get_test_using_dep($id)
    {
        $test=Test::where('dep_id',$id)->get();
        // dd($test);
        return response()->json($test);
    }

    public function gettest_info($id)
    {
        $test=Test::find($id)->fees;
        return response()->json($test);

    }

    public function getDocter($id)
    {
        $emp=Employee::where('dep_id',$id)->get();
        return response()->json($emp);
    }

    public function discount(Request $request)
    {
        $bill=LabBill::find($request->bill_id);
        $bill->discount=$request->discount;
        $bill->save();

              
        $total=LabBill_info::where('bill_id',$request->bill_id)->sum('total');
        $discount=LabBill::find($request->bill_id)->discount;
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total=$nettotal;

        $fin=FinanceLog::where('bill_id',$request->bill_id)
        ->where('payment_type',"Laboratory bill payment")
        ->update(['status'=>'Paid','total'=>$total]);

        return  response()->json(['success'=>'Discount Add Successfully']);
    }

    public function lab_pos()
    {
        return view('admin.billing.laboratory.lab_pos');
    }
    public function get_tests(Request $request)
    {
  
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data=Test::select('test_id','test_type')->where('test_type','LIKE',"%$search%")->get();
        }
        return response()->json($data);

    }

    public function add_test_detail($id)
    {
        
        $test=Test::where('test_id',$id)->get();
      
      // dd($sale.$expire.$quant);
        $html='<tr id="row'.$test[0]->test_id.'">
        <td><input type="text" form="form_medicine" class="form-control text_filed_sm" disabled value="'.$test[0]->test_type.'"></td>
        <input type="hidden" name="test_id[]" value="'.$test[0]->test_id.'">
        <input type="hidden" name="fees[]" value="'.$test[0]->fees.'">
        <td><input type="text" form="form_medicine" class="form-control text_filed_sm total_price_final" disabled value="'.$test[0]->fees.'"></td>
        <td class="text-center "form="form_medicine" data-toggle="tooltip" title="Delete"><a data-ref="'.$test[0]->test_id.'" class="btn-sm btn btn-danger text-white del-medicine"><i class="fa fa-trash fa-lg"></a></td>
        </td>
      </tr>';
      $final=array(
        'html'=>$html,
        'id'=>$id
      );
      return response()->json($final);
    }

    public function lab_bill_update ($id)
    {
        $bill=LabBill::find($id);
        $bill_info=LabBill_info::
        select('tests.test_type','lab_bill_infos.*')
        ->join('tests','tests.test_id','lab_bill_infos.test_id')
        ->where('bill_id',$id)->get();
        $test=Test::all();

       return view('admin.billing.laboratory.lab_bill_update',compact('bill','bill_info','test'));
    }
}
