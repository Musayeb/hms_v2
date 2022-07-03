<?php
namespace App\Http\Controllers\finance;
use App\Http\Controllers\Controller;
use App\Models\FinanceLog;
use App\Models\Pettycash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PettycashController extends Controller
{
    public function index()
    {
        $cash=Pettycash::select('users.email','pettycashes.*')->join('users','users.id','pettycashes.author')
        ->orderBy('pettycashes.created_at','DESC')
        ->paginate(50);

        return view('admin.finance.petty_cash',compact('cash'));
    }

    public function store(Request $request)
    {
       $valid=$request->validate([
        'person_name'=>'required',
        'description'=>'required',
        'request_amount'=>'required',
       ]);
        if($valid){
          $cash= new Pettycash();
          $cash->person_name=$request->person_name;
          $cash->description=$request->description;
          $cash->amount=$request->request_amount;
          $cash->author=Auth::id();
          $cash->status="Pending";
          $cash->save();
          
          $fin= new FinanceLog();
          $fin->payment_type="Daily Expenses Payment";
          $fin->bill_id=$cash->cash_id;
          $fin->total=$request->request_amount;    
          $fin->status="Pending";
          $fin->type="Expense";
          $fin->author=Auth::id();
          $fin->save();

        return response()->json(['success'=>'Expense Add Successfully']);
        }
    }


    public function edit($id)
    {
        $ex=Pettycash::find($id);
        return response()->json($ex);
    }

    public function update(Request $request)
    {
        $valid=$request->validate([
            'person_name'=>'required',
            'description'=>'required',
            'request_amount'=>'required',
           ]);
            if($valid){
              $cash=Pettycash::find($request->cash_id);
              $cash->person_name=$request->person_name;
              $cash->description=$request->description;
              $cash->amount=$request->request_amount;
              $cash->save();
              
              FinanceLog::where('bill_id',$request->cash_id)
              ->where('payment_type',"Daily Expenses Payment")
              ->update(['total'=>$request->request_amount]);

            return response()->json(['success'=>'Expense updated Successfully']);
            }
    }


    public function destroy($id)
    {
        Pettycash::find($id)->delete();        
    }
}
