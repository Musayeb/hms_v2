<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\FinanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $emp=Employee::all();
        $pay=DB::table('payroll_process')->select('users.email','payroll_process.*')
        ->join('users','users.id','payroll_process.author')
        ->orderBy('created_at','DESC')
        ->get();
        return view('admin.payroll.process_payroll',compact('emp','pay'));
    }

    public function show($id)
    {
        $payroll=DB::table('payrolls')->select('employees.f_name','employees.l_name','payrolls.*')
        ->join('employees','employees.emp_id','payrolls.emp_id')
        ->where('payroll_id',$id)->get();


        return view('admin.payroll.payroll',compact('payroll'));

    }
    public function store(Request $request)
    {
        $check=DB::table('payroll_process')->where('month_year',$request->month)->get();
        if(empty($check[0]->pay_id)){
        $pay=DB::table('payroll_process')->insert([
            'author'=>Auth::id(),
            'month_year'=>$request->month,
            'status'=>'Pending',
                'created_at'=>Date::now(),
                'updated_at'=>Date::now(),
        ]);
        $id=DB::getPdo()->lastinsertid();
        $tax=0;
        $Taxamount=0;
        $amount=0;


       foreach ($request->employee as $value) {
        $emp=Employee::select('salary')->where('emp_id',$value)->get();
        if(!empty($emp[0]->salary)){
        if ($emp[0]->salary <= 5000) {
            $tax = 0;
            $Taxamount =$emp[0]->salary * $tax / 100;
            $amount = $emp[0]->salary - $Taxamount;

        }
        if ($emp[0]->salary > 5000) {
            $tax = 2;
            $Taxamount = (int)$emp[0]->salary * 2 / 100;
            $amount = $emp[0]->salary - $Taxamount;
        }

        if ($amount > 12500) {
              $tax = $tax+10; 
            $Taxamount += $amount * 10 / 100;
            $amount = $emp[0]->salary - $Taxamount;
        }

        if ($amount > 100000) {
              $tax = $tax+20;
            $Taxamount += $amount * 20 / 100;
            $amount = $emp[0]->salary - $Taxamount;
        }

        $pay= new Payroll;
        $pay->author=Auth::id();
        $pay->payroll_id=$id;
        $pay->emp_id=$value;
        $pay->tax_precentage=$tax;
        $pay->tax_amount=$Taxamount;
        $pay->net_salary=$amount;
        $pay->deduction='';
        $pay->deduction_description='';
        $pay->month_year=$request->month;
        $pay->status='Pending';
        $pay->save();
        }
       }

    // 
       
        $total=Payroll::where('payroll_id',$id)->sum('net_salary');
        $fin= new FinanceLog();
        $fin->payment_type="Payroll proceeded to employee";
        $fin->bill_id=$id;
        $fin->transaction=rand(11111111111111,99999999999999);
        $fin->identify='payroll';
        $fin->total=$total;
        $fin->status="Pending";
        $fin->type="Expense";
        $fin->author=Auth::id();
        $fin->save();

        return response()->json(['notif'=>'Payroll successfully paid']);

    }else{
        return response()->json(['error'=>'Payroll of this month already paid']);
    }

    }
    public function status_change(Request $request)
    {
        DB::table('payroll_process')->where('payroll_id',$request->id)
        ->update(['status'=>$request->status]);
        
        FinanceLog::where('identify','payroll')->where('bill_id',$request->id)->update(['status'=>'Paid']);
        return response()->json(['success'=>'Payroll status changed successfully']);

    }

    public function print($id)
    {
       $pay= Payroll::select('employees.f_name','users.email','employees.l_name','payrolls.*')
       ->join('users','users.id','payrolls.author')
       ->join('employees','employees.emp_id','payrolls.emp_id')
       ->where('pay_id',$id)->get();

       $div='<h4>Payroll Bill</h4> <h6>Month  '.$pay[0]->month_year.'</h6><div class="row p-4 table-sm table " style="margin-top: 20px">
       <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Employee Name:</th>
                    <td ><small >'.$pay[0]->f_name.' '.$pay[0]->l_name.'</small></td>
                    <th >Tax %</th>
                    <td ><small>'.$pay[0]->tax_precentage.'%</small></td>
                </tr>
                <tr style="padding-top:10px">
                <th>Tax Amount:</th>
                <td ><small >'.$pay[0]->tax_amount.'</small></td>
                <th >Net Salary</th>
                <td colspan="3"><small>'.$pay[0]->net_salary.'</small></td>
                </tr>
                <tr style="padding-top:10px">
                <th >Deduction</th>
                <td ><small>'.$pay[0]->deduction.'</small></td>
                <th>Deduction Description</th>
                <td colspan="3" ><small >'.$pay[0]->deduction_description.'</small></td>
                </tr>
              
                </tr>
                <tr style="padding-top:10px">
                <th>Generated By</th>
                <td colspan="6"><small >'.$pay[0]->email.'</small></td>

                </tr>

             
            </tbody>
        </table>
        <p>Employee Signature</p>

    </div>
    </div>';

    return response()->json($div);
    }


    public function payroll_edit_second($id)
    {
        $pay=Payroll::where('pay_id',$id)->get()[0];
        $emp=Employee::find($pay->emp_id)->salary;

        return response()->json(['salary'=>$emp,'pay'=>$pay]);
    }

    public function payroll_edit(Request $request)
    {
        $pay=Payroll::find($request->payroll_id);
        DB::table('payrolls')->where('pay_id',$request->payroll_id)->update(['net_salary'=>(int)$pay->net_salary+(int)$pay->deduction]);


        $a=Payroll::find($request->payroll_id);
        DB::table('payrolls')->where('pay_id',$request->payroll_id)->update(['net_salary'=>(int)$a->net_salary-(int)$request->deduction,'deduction'=>$request->deduction]);

        // change in f statment
        $total=Payroll::where('payroll_id',$a->payroll_id)->sum('net_salary');
        FinanceLog::where('bill_id',$a->payroll_id)->where('identify','payroll')->update(['total'=>$total]);
        return response()->json(['success'=>'Payroll updated successfully !']);

                
    }

    public function destroy($id)
    {
        Payroll::where('payroll_id',$id)->delete();
        DB::table('payroll_process')->where('payroll_id',$id)->delete();
        FinanceLog::where('bill_id',$id)->where('identify','payroll')->delete();
        return response()->json(['success'=>'successfully done !']);
    }

    public function delete_payroll_detail_record($id)
    {
        $pay=Payroll::find($id);
        $pay1=$pay->payroll_id;
        $pay->delete();

        $total=Payroll::where('payroll_id',$pay1)->sum('net_salary');
        FinanceLog::where('bill_id',$pay1)->where('identify','payroll')->update(['total'=>$total]);
        return response()->json(['success'=>'successfully done !']);

        
    }
}
