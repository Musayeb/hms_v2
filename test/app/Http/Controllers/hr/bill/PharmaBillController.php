<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\FinanceLog;
use App\Models\pharmaBill;
use App\Models\Pharma_Main_Catagory;
use App\Models\Patients;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;
use App\Models\PurchaseMidicines;
use App\Models\Midicines;
use App\Models\Opd;
use App\Models\pharmaBill_info;
use Helper;
use Illuminate\Support\Carbon;
// use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use datatables;
use DNS1D;

class PharmaBillController extends Controller
{
    public function index()
    {
             
        $bill=pharmaBill::
        select('discount','users.email',
        'bill_no','total','pharma_bills.patient_name','pharma_bills.created_at as date','bill_id')
        ->join('users','users.id','pharma_bills.author')
        ->groupBy('bill_id')
        ->limit(1000)
        ->orderBy('pharma_bills.created_at','DESC')
        ->get();

        if(request()->ajax()) {
            return datatables()->of($bill)
            ->addColumn('action', 'company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }


       return view('admin.billing.pharmacy.bill',compact('bill'));

    }

    public function search_opd_in_add_bill(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data=Opd::
             select('o_f_name','o_l_name','patient_id')
            ->where('o_f_name','LIKE',"%$search%")
            ->orwhere('o_l_name','LIKE',"%$search%")
            ->orwhere('patient_id','LIKE',"%$search%")
            ->get();
        }

        return response()->json($data);

    }
    public function search_patient_in_add_bill(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data=Patients::
            select('f_name','l_name','patient_id','patient_idetify_number')
            ->where('f_name','LIKE',"%$search%")
            ->orwhere('l_name','LIKE',"%$search%")
            ->orwhere('patient_idetify_number','LIKE',"%$search%")
            ->get();
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {

        $max=helper::getpharmacyBillNo();
        $bill=new pharmaBill;
        $bill->bill_no=$max;
        $bill->patient_name=$request->patient_name;             
        $bill->total=$request->netamount;
        $bill->discount=$request->discount;
        $bill->author=Auth::id();
        $bill->save();
        
        $fin= new FinanceLog();
        $fin->payment_type="Pharmacy bill payment";
        $fin->bill_id=$bill->bill_id;
        $fin->total=$request->netamount;
        $fin->status="Paid";
        $fin->identify="ph";
        $fin->transaction=rand(11111111111111,99999999999999);
        $fin->author=Auth::id();
        $fin->save();	

        foreach($request->medicine_name as $index => $product) {
            $info= new pharmaBill_info;
            $info->bill_id=$bill->bill_id;
            $info->midi_id=$request->medicine_name[$index];
            $info->expiry_date=$request->expiryDate[$index];
            $info->quanitity=$request->quantity[$index];
            $info->price=$request->price[$index];
            $info->total=$request->quantity[$index]*$request->price[$index];
            $info->author=Auth::id();
            $info->save();
            $mid=Midicines::find($request->medicine_name[$index]);
            $mid->sold_quantity+=(int)$request->quantity[$index];
            $mid->save();

        }
       
    
        $arr=array(
                 'bill_id'=>$bill->bill_id,
                 'msg'=>'Pharmacy Bill generated Successfully !'
             );
        return  response()->json($arr);
            

}
    public function pharmacy_pos(){
        return view('admin.billing.pharmacy.pharmacy_pos');
        
    }

    public function edit($id)
    {
        $bill=pharmaBill::find($id);
        return response()->json($bill);
    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'patient_names'=>'required',
            'bill_number'=>'required',
        ]);
        if($validate){

        $total=pharmaBill_info::where('bill_id',$request->bill_id)->sum('total');
        $total1=$request->discount*$total/100;   
        $final=$total-$total1;

        $bill=pharmaBill::find($request->bill_id);
        $bill->patient_name=$request->patient_names;
        $bill->note=$request->note;
        $bill->total=$final;
        $bill->discount=$request->discount;
        $bill->author=Auth::id();
        $bill->save();
        
        $fin=FinanceLog::where('bill_id',$request->bill_id)
        ->where('payment_type',"Pharmacy bill payment")
        ->update(['status'=>'Paid','total'=>$final]);

        return  response()->json(['success'=>'Pharmacy Bill updated successfully']);

        }
    }
    public function destroy($id)
    {
        pharmaBill::find($id)->delete();
    }
    public function getMedicine($id)
    {
      $total=PurchaseMidicines::select(DB::raw('Sum(quantity)as quant'),
       DB::raw("(SELECT sale_price FROM purchase_midicines WHERE midi_id='".$id."' ORDER by created_at DESC LIMIT 1)as sale_price"),
       DB::raw("(SELECT expiry_date FROM purchase_midicines WHERE midi_id='".$id."' ORDER by created_at DESC LIMIT 1)as expiry_date"))
       ->where('midi_id',$id)
      ->orderBY('created_at','ASC')
      ->limit(1)
      ->get();

      $mid=Midicines::where('midi_id',$id)->sum('sold_quantity');
       $avaliable=$total[0]->quant-$mid;
     
       return Response()->json(array(
        'avaliable' => $avaliable,
        'total' => $total,
        ));   

    }
    public function addmedicine_to_bill(Request $request)
    {
       
       $datavalidate=$request->validate([
           'medicine'=>'required',
           'quantity'=>'required',
           'sale_price'=>'required',
           'amount'=>'required',
           'expiry_date'=>'required',
       ]);
        if($datavalidate){

          $info= new pharmaBill_info;
          $info->bill_id=$request->bill_id;
          $info->midi_id=$request->medicine;
          $info->expiry_date=$request->expiry_date;
          $info->quanitity=$request->quantity;
          $info->price=$request->sale_price;
          $info->total=$request->amount;
          $info->author=Auth::id();
          $info->save();

          $total=pharmaBill_info::where('bill_id',$request->bill_id)->sum('total');
          
          $discount=pharmaBill::find($request->bill_id)->discount;
          $discountamount=$discount*$total/100;
          $nettotal=$total-$discountamount;
          $total=$nettotal;
          
          $mid1=Midicines::find($request->medicine);
          $mid1->sold_quantity +=(int)$request->quantity;
          $mid1->save();

          
          $discount=pharmaBill::where('bill_id',$request->bill_id)->update(['total'=>$total]);

           $fin=FinanceLog::where('bill_id',$request->bill_id)
          ->where('payment_type',"Pharmacy bill payment")
          ->update(['status'=>'Paid','total'=>$total]);

          return  response()->json(['success'=>'Medicine Added to Bill Successfully']);
        }
    }
    
    public function bill_pharmacy_print($id)
    {
     $info=pharmaBill_info::select('midicines.medicine_name','pharma_bill_infos.*')
        ->join('midicines','midicines.midi_id','pharma_bill_infos.midi_id')
        ->where('bill_id',$id)->get();

     $bill=pharmaBill::find($id);

        $qrlink="<img src='data:image/png;base64,".DNS1D::getBarcodePNG(strval($bill->bill_no), 'C39',1,55,array(0,0,0), true)."'/>";
        $date=date('Y-m-d h:i:s a', strtotime($bill->created_at)); 
        
        $total=pharmaBill_info::where('bill_id',$id)->sum('total');
        $discount=pharmaBill::find($id)->discount;
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total1=$nettotal;

        return Response()->json(array(
            'info' => $info,
            'bill' => $bill,
            'qr' => $qrlink,
            'date'=>$date,
            'total'=>$total1,
        ));   

    }

    public function bill_pharmacy_discount(Request $request)
    {
        $bill=pharmaBill::find($request->bill_id);
        $bill->discount=$request->discount;
        $bill->save();


        $total=pharmaBill_info::where('bill_id',$request->bill_id)->sum('total');

        $discount=pharmaBill::find($request->bill_id)->discount;
        
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total=$nettotal;
    
        $fin=FinanceLog::where('bill_id',$request->bill_id)
        ->where('payment_type',"Pharmacy bill payment")
        ->update(['status'=>'Paid','total'=>$total]);


    
    
        return  response()->json(['success'=>'Discount Add Successfully']);
    }

    public function edit_medicine_info($id)
    {
        $info=pharmaBill_info::find($id);
        $quant=PurchaseMidicines::select(DB::raw('Sum(quantity)as quant'))->where('midi_id',$info->midi_id)->orderBY('created_at','DESC')->get();
        $mid_cat=Midicines::find($info->midi_id)->ph_main_cat_id;
        $sold=Midicines::find($info->midi_id)->sold_quantity;
        $avaliable=$quant[0]->quant-$sold;
      
        return Response()->json(array(
            'info' => $info,
            'mid_cat' => $mid_cat,
            'avaliable' => $avaliable,

        ));      
    }

    public function updatemedicine_to_bill(Request $request)
    {
        $datavalidate=$request->validate([
            'medicine'=>'required',
            'quantity'=>'required',
            'sale_price'=>'required',
            'amount'=>'required',
            'expiry_date'=>'required',
        ]);
        if($datavalidate){
          
          $info=pharmaBill_info::find($request->bill_id);       
          //   finance
     
          $total=pharmaBill_info::where('bill_id',$info->bill_id)->sum('total');

          $f=(int)$total-(int)$info->total;
          $total=$f+$request->amount;
          
          $discount=pharmaBill::find($info->bill_id)->discount;
          $discountamount=$discount*$total/100;
          $nettotal=$total-$discountamount;
          $total=$nettotal;
          $t=pharmaBill::find($info->bill_id);
          $t->total=$total;
          $t->save();

 
          $fin=FinanceLog::where('bill_id',$info->bill_id)
          ->where('payment_type',"Pharmacy bill payment")
          ->update(['total'=>$total]);
 
          
          $mid=Midicines::find($request->medicine);
          $mid->sold_quantity =$mid->sold_quantity-$info->quanitity;
          $mid->save();
          
          $info->midi_id=$request->medicine;
          $info->expiry_date=$request->expiry_date;
          $info->quanitity=$request->quantity;
          $info->price=$request->sale_price;
          $info->total=$request->amount;
          $info->save();

          $mid1=Midicines::find($request->medicine);
          $mid1->sold_quantity +=(int)$request->quantity;
          $mid1->save();
          return  response()->json(['success'=>'Bill Medicine updated Successfully']);

        }
    }
    public function deletemedicine_to_bill($id)
    {
        $pharm=pharmaBill_info::find($id);
     
        $total=pharmaBill_info::where('bill_id',$pharm->bill_id)->sum('total');

        if($total <= $pharm->total){
            $finance=FinanceLog::where('payment_type','Pharmacy bill payment')->where('bill_id',$pharm->bill_id)->update(['total'=>0]);
        }else{
            // total
            $netamountbeforprecentage=(int)$total-(int)$pharm->total;

            // descount
            $discount=pharmaBill::find($pharm->bill_id)->discount;
            $discountamount=$discount* $netamountbeforprecentage/100;
            $nettotal=$netamountbeforprecentage-$discountamount;
            $total=$nettotal;

            $finance=FinanceLog::where('payment_type','Pharmacy bill payment')
            ->where('bill_id',$pharm->bill_id)
            ->update(['total'=>$total]);
            $billa=pharmaBill::find($pharm->bill_id);
            $billa->total=$total;
            $billa->save();
        }
    
        $mid=Midicines::find($pharm->midi_id);
        $total=$mid->sold_quantity-$pharm->quanitity;
        $mid->sold_quantity =$total;
        $mid->save();
        
        $pharm->delete();

    }

    // add new bill second version of pharmacy bill

    public function generate_bill(){
        $department=Departments::all();
        $cat=Pharma_Main_Catagory::all();
        return view('admin.billing.pharmacy.generate_bill',compact('department','cat'));
    }

    public function barcode_serach($bar)
    {
        $bar=Midicines::select('midi_id')->where('barcode',$bar)->get();
        return response()->json($bar);
    }

    // update bill
    public function update_view($id)
    {
        $bill=pharmaBill::find($id);
        $bill_info=pharmaBill_info::join('midicines','midicines.midi_id','pharma_bill_infos.midi_id')
        ->select('pharma_bill_infos.*','midicines.medicine_name')
        ->where('bill_id',$id)->get();
        $mid=Midicines::orderBy('midi_id')->get();

        return view('admin.billing.pharmacy.pharmacy_update',compact('bill_info','bill','mid'));

    }

    
}

