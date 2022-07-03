<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\FinanceLog;
use App\Models\OvertimePay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimePayController extends Controller
{
    public function index()
    {
        $dep=Departments::all();

        $bill=OvertimePay::join('employees','overtime_pays.emp_id','employees.emp_id')
        ->join('users','users.id','overtime_pays.author')
        ->OrderBy('overtime_pays.created_at','DESC')->paginate(50);
        return view('admin.billing.overtime.bill',compact('dep','bill'));
    }

    public function store(Request $request)
    {
        $validate=$request->validate([
            'bill_number'=>'required',
            'department'=>'required',
            'employee'=>'required',
            'issue_date'=>'required',
            'hours'=>'required',
            'total_amount'=>'required',
            
        ]);
        if($validate){
        $over = new OvertimePay();
           $over->bill_number=$request->bill_number;
           $over->emp_id=$request->employee;
           $over->extra_hours=$request->hours;
           $over->date=$request->issue_date;
           $over->total_amount=$request->total_amount;          
           $over->author=Auth::id();
           $over->save();
           
           $fin= new FinanceLog();
           $fin->payment_type="Staff over time payment";
           $fin->bill_id=$over->overtime_id;
           $fin->total=$request->total_amount;    
           $fin->status="Paid";
           $fin->type="Expense";
           $fin->author=Auth::id();
           $fin->save();

        return response()->json(['success'=>'Bill generated successfully']);
        }
    }

    public function edit($id)
    {
        $pay=OvertimePay::find($id);
        $dep=Employee::find($pay->emp_id)->select('dep_id')->get();

        return Response()->json(array(
            'pay' => $pay,
            'dep' => $dep,
        ));
    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'bill_number'=>'required',
            'department'=>'required',
            'employee'=>'required',
            'issue_date'=>'required',
            'hours'=>'required',
            'total_amount'=>'required',
            
        ]);
        if($validate){
        $over=OvertimePay::find($request->pay);

           $fin=FinanceLog::where('bill_id',$over->overtime_id)
           ->where('payment_type',"Staff over time payment")
           ->get();
           
           $f=(int)$fin[0]->total-(int)$over->total_amount;
           $total1=$f+$request->total_amount;
           
           FinanceLog::where('bill_id',$over->overtime_id)
           ->where('payment_type',"Staff over time payment")
           ->update(['total'=>$total1]);
           
           $over=OvertimePay::find($request->pay);
           $over->bill_number=$request->bill_number;
           $over->emp_id=$request->employee;
           $over->extra_hours=$request->hours;
           $over->date=$request->issue_date;
           $over->total_amount=$request->total_amount;  
           $over->save();

           return response()->json(['success'=>'Bill updated successfully']);

        }
    }

    public function destroy($id)
    {
       OvertimePay::find($id)->delete();
    }
}
