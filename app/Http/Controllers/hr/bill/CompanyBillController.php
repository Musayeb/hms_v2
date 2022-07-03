<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\companyBill;
use App\Models\companyBillInfo;
use App\Models\companyBillInfo1;
use App\Models\FinanceLog;
use App\Models\LabBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyBillController extends Controller
{
    public function index()
    {
        $bill=companyBill::join('users','users.id','company_bills.author')
        ->orderBy('company_bills.created_at','DESC')
        ->paginate(50);
        
        return view('admin.billing.medicalCompany.bill',compact('bill'));
    }

    public function store(Request $request)
    {
        $validate=$request->validate([
            'company_name'=>'required',
            'issue_date'=>'required',
        ]);
        
        if($validate){
           $bill= new companyBill(); 
           $bill->company_name=$request->company_name;
           $bill->date=$request->issue_date;
           $bill->description=$request->description;
           $bill->author=Auth::id();
           $bill->save();
           
   
           return response()->json(['success'=>"Bill generated successfully"]);
        }
    }

    public function edit($id)
    {
        $bill=companyBill::find($id);
        return response()->json($bill);
        
    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'company_name'=>'required',
            'issue_date'=>'required',
        ]);
        $bill=companyBill::find($request->pay_id);
        $bill->company_name=$request->company_name;
        $bill->date=$request->issue_date;
        $bill->description=$request->description;
        $bill->save();


  
        return response()->json(['success'=>"Bill update successfully"]);
    }
    public function destroy($id)
    {
        $bill=companyBill::find($id)->delete();
    }

    public function show($id)
    {
        $bill=companyBill::
        join('users','users.id','company_bills.author')
        ->where('company_bill_id',$id)
        ->orderBy('company_bills.created_at','DESC')
        ->get();
        
        $sum=companyBillInfo::where('company_bill_id1',$id)
        ->sum('paid_amount');
        
        $bill=$bill[0];
        $bills=companyBillInfo::join('users','users.id','company_bill_infos.author')
        ->where('company_bill_id1',$id)
        ->orderBy('company_bill_infos.created_at','DESC')->get();

        $bills1=companyBillInfo1::select('users.email','company_bill_info1s.*')
        ->join('users','users.id','company_bill_info1s.author')
        ->where('company_bill_id',$id)
        ->orderBy('company_bill_info1s.created_at','DESC')->get();
        
        $total=companyBillInfo1::select(DB::raw('Sum(AMOUNT)as total'))->where('company_bill_info1s.company_bill_id',$id)->get();

        return view('admin.billing.medicalCompany.show',compact('bills1','bills','bill','id','sum','total'));
    }
}
