<?php

namespace App\Http\Controllers\finance;
use App\Http\Controllers\Controller;
use App\Models\ExtraIncome;
use App\Models\FinanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtraIncomeController extends Controller
{
    public function index()
    {
        $extra=ExtraIncome::select('users.email','extra_incomes.*')->join('users','users.id','extra_incomes.author')->orderBy('extra_incomes.created_at','DESC')->paginate(50);

        return view('admin.finance.extraincome',compact('extra'));
    }

    public function store(Request $request)
    {
        $validate=$request->validate([
            'receiver'=>'required',
            'description'=>'required',
            'amount'=>'required'
        ]);
        if($validate){
           $extra =new ExtraIncome();
           $extra->receiver=$request->receiver;
           $extra->pay_amount=$request->amount;
           $extra->description=$request->description;
           $extra->author=Auth::id();
           $extra->save();

           $fin= new FinanceLog();
           $fin->payment_type="Extra Income Receive";
           $fin->bill_id=$extra->id;
           $fin->total=$request->amount;
           $fin->description=$request->description;
           $fin->status="Paid";
           $fin->author=Auth::id();
           $fin->save();
          return response()->json(['success'=>'Income added successfully !']); 	
        }
    }

    public function edit($id)
    {
       $extra=ExtraIncome::find($id);
       return response()->json($extra);
    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'receiver'=>'required',
            'description'=>'required',
            'amount'=>'required'
        ]);
        if($validate){
            $extra=ExtraIncome::find($request->extra_id);
            $extra->receiver=$request->receiver;
            $extra->pay_amount=$request->amount;
            $extra->description=$request->description;
            $extra->save();

            $fin=FinanceLog::where('bill_id',$request->extra_id)->where('payment_type','Extra Income Receive')
            ->update(['total'=>$request->amount]);
            return response()->json(['success'=>'Income updated successfully !']); 	

        }
    }

    public function destroy($id)
    {
        $extra=ExtraIncome::find($id)->delete();
        $fin=FinanceLog::where('bill_id',$id)->where('payment_type','Extra Income Receive')
        ->delete();

        
    }
}
