<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pharma_Main_Catagory;
use App\Models\Midicines;
use App\Models\PurchaseMidicines;
use Composer\Util\Http\Response;
use Illuminate\Support\Facades\DB;

class PurchaseMidicinesController extends Controller
{
   public function index()
   {   
       $cat=Pharma_Main_Catagory::all();
       $mid=PurchaseMidicines::
        select('midicines.medicine_name','purchase_midicines.*','users.email')
       ->join('users','users.id','purchase_midicines.author')
       ->join('midicines','midicines.midi_id','purchase_midicines.midi_id')
       ->groupBy('purchase_midicines.purchase_m_id')
       ->orderBy('purchase_midicines.created_at','DESC')
       ->get();
    
       if(request()->ajax()) {
        return datatables()->of($mid)
        ->addIndexColumn()
        ->make(true);
    }

      
       return view('admin.pharmacy.medicinePurchase',compact('cat','mid'));
   }
   public function filter($id)
   {
        $mid=Midicines::where('ph_main_cat_id',$id)->get();
        return response()->json($mid);
   }

   public function store(Request $request)
   {
       $validate=$request->validate([
           'supplier_name'=>'required',
           'medicine'=>'required',
           'purchase_price'=>'required',
           'sale_price'=>'required',
           'expiry_date'=>'required',
           'quantity'=>'required',
           'batch_number'=>'required'
        ]);

      $pur= new PurchaseMidicines;
      $pur->midi_id=$request->medicine;
      $pur->supplier_name=$request->supplier_name;
      $pur->quantity=$request->quantity;
      $pur->status="Pending";
      $pur->purchase_price=$request->purchase_price;
      $pur->batch_number=$request->batch_number;
      $pur->sale_price=$request->sale_price;
      $pur->expiry_date=$request->expiry_date;
      $pur->amount=$request->purchase_price*$request->quantity;
      $pur->author=Auth::id();
      $pur->save();
     
      $mid=Midicines::find($request->medicine);

      if(empty($mid->quantitiy)){
        $q=0;
      }else{
          $q=$mid->quantitiy+$request->quantity;
      }

      DB::table('midicines')
      ->where('midi_id',$request->medicine)
      ->update([
        'quantitiy'=>$mid->quantitiy+$request->quantity,
        'expiry_date'=>$request->expiry_date,
        'purchase_price'=>$request->purchase_price,
        'sale_price'=>$request->sale_price,
      ]);

      return response()->json(['success'=>'Medicine purchases successfully']);  						
   }

   public function edit($id)
   {
    
    $purchase=PurchaseMidicines::find($id);
    $mid=Midicines::find($purchase->midi_id);
    $pharma=Pharma_Main_Catagory::find($mid->ph_main_cat_id);
   
    return response()->json(array(
      'purchase' => $purchase,
      'pharma' => $pharma->ph_main_cat_id,
   ));
    
   }

   public function update(Request $request)
   {
    $validate=$request->validate([
      'supplier_name'=>'required',
      'medicine'=>'required',
      'purchase_price'=>'required',
      'sale_price'=>'required',
      'expiry_date'=>'required',
      'quantity'=>'required',
      'batch_number'=>'required'
   ]);

   if($validate){
      $pur=PurchaseMidicines::find($request->hide);
      $mid=Midicines::find($request->medicine);
      $mid->quantitiy=$mid->quantitiy-$pur->quantity;
      $mid->save();

      $pur->midi_id=$request->medicine;
      $pur->supplier_name=$request->supplier_name;
      $pur->quantity=$request->quantity;
      $pur->purchase_price=$request->purchase_price;
      $pur->sale_price=$request->sale_price;
      $pur->batch_number=$request->batch_number;
      $pur->expiry_date=$request->expiry_date;
      $pur->amount=$request->purchase_price*$request->quantity;
      $pur->save();
      
      
      DB::table('midicines')
      ->where('midi_id',$request->medicine)
      ->update([
        'quantitiy'=>$mid->quantitiy+$request->quantity,
        'expiry_date'=>$request->expiry_date,
        'purchase_price'=>$request->purchase_price,
        'sale_price'=>$request->sale_price,
      ]);
   }
  }

  public function destroy($id)
  {
    $quant=PurchaseMidicines::find($id);
    $mid=Midicines::find($quant->midi_id);
    $mid->quantitiy=$mid->quantitiy-$quant->quantity;
    $mid->save();
    // decrease the quanitity
    // delete record 
    $quant->delete();
  }
  public function show($id){
    $mid=PurchaseMidicines::
    select('midicines.medicine_name','purchase_midicines.*','users.email')
   ->join('users','users.id','purchase_midicines.author')
   ->join('midicines','midicines.midi_id','purchase_midicines.midi_id')
   ->where('purchase_m_id',$id)
   ->get();
    return response()->json($mid);

  }


  
}
