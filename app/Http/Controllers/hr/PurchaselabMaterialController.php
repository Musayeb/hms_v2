<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaselabMaterial;
use App\Models\labCatagory;
use App\Models\labMaterial;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class PurchaselabMaterialController extends Controller
{
    public function index()
    {   
        $cat=labCatagory::all();
        $mid=PurchaselabMaterial::
        select('lab_materials.material_name','purchaselab_materials.*','users.email')
        ->join('users','users.id','purchaselab_materials.author')
        ->join('lab_materials','lab_materials.lab_m_id','purchaselab_materials.lab_m_id')
        ->groupBy('purchaselab_materials.lab_purchase_id')
        ->paginate(50);
       
        return view('admin.laboratory.purchase',compact('cat','mid'));
    }
    public function store(Request $request)
    {
        $validate=$request->validate([
            'supplier_name'=>'required',
            'material'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'expiry_date'=>'required',
            'quantity'=>'required',
         ]);
        if($validate){
            $pur= new PurchaselabMaterial;
            $pur->lab_m_id=$request->material;
            $pur->supplier_name=$request->supplier_name;
            $pur->quantity=$request->quantity;
            $pur->purchase_price=$request->purchase_price;
            $pur->sale_price=$request->sale_price;
            $pur->expiry_date=$request->expiry_date;
            $pur->status="Pending";
            $pur->amount=$request->purchase_price*$request->quantity;
            $pur->author=Auth::id();
            $pur->save();
           
            $mid=labMaterial::find($request->material);
            if(empty($mid->quantitiy)){
                $q=0;
              }else{
                  $q=$mid->quantitiy+$request->quantity;
              }
            DB::table('lab_materials')
            ->where('lab_m_id',$request->material)
            ->update([
              'quantitiy'=>(int)$mid->quantitiy+(int)$request->quantity,
              'expiry_date'=>$request->expiry_date,
              'purchase_price'=>$request->purchase_price,
              'sale_price'=>$request->sale_price,
            ]);
           return response()->json(['success'=>'Material purchased successfully']);
        }    
    }
    public function edit($id)
    {
        $purchase=PurchaselabMaterial::find($id);
        $test=labMaterial::find($purchase->lab_m_id);
        $mat=labMaterial::where('lab_cat_id',$test->lab_cat_id)->get();
        
        return Response()->json(array(
            'mat' => $mat,
            'purchase' => $purchase,
            'cat'=>$mat[0]->lab_cat_id,
        ));         

    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'supplier_name'=>'required',
            'material'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'expiry_date'=>'required',
            'quantity'=>'required',
         ]);

            $pur= PurchaselabMaterial::find($request->purchase_id);
            $pur->lab_m_id=$request->material;
            $pur->supplier_name=$request->supplier_name;
            $pur->quantity=$request->quantity;
            $pur->purchase_price=$request->purchase_price;
            $pur->sale_price=$request->sale_price;
            $pur->expiry_date=$request->expiry_date;
            $pur->amount=$request->purchase_price*$request->quantity;
            $pur->save();
            $mid=labMaterial::find($request->material);
            if(empty($mid->quantitiy)){
                $q=0;
            }else{
                $q=$mid->quantitiy+$request->quantity;
            }
        
        DB::table('lab_materials')
        ->where('lab_m_id',$request->material)
        ->update([
          'quantitiy'=>(int)$mid->quantitiy+(int)$request->quantity,
          'expiry_date'=>$request->expiry_date,
          'purchase_price'=>$request->purchase_price,
          'sale_price'=>$request->sale_price,
        ]);
       return response()->json(['success'=>'Material Updated successfully']);
    }
}

