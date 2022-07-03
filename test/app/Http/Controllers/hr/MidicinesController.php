<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\Pharma_Main_Catagory;
use App\Models\Midicines;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use Helper;
use DNS1D;

class MidicinesController extends Controller
{
    public function index()
    {
        $cat=Pharma_Main_Catagory::all();
        $mid=Midicines::
        select('pharma__main__catagories.m_cat_name','users.email','midicines.*')
        ->join('users','users.id','midicines.author')
         ->join('pharma__main__catagories','pharma__main__catagories.ph_main_cat_id','midicines.ph_main_cat_id')
         ->groupBy('midicines.midi_id')
         ->orderBY('midicines.created_at','DESC')
         ->get();
        return view('admin.pharmacy.midicine',compact('cat','mid'));
    }
    public function store(Request $request)
    {
      $validate=$request->validate([
          'midicine_catagory'=>'required',
          'medicine_name'=>'required',
          'company_name'=>'required', 
      ]);
      if($validate){
        $mid= new Midicines;
        $mid->medicine_name=$request->medicine_name;
        $mid->ph_main_cat_id=$request->midicine_catagory;
        $mid->company=$request->company_name;
        $mid->barcode=$request->barcode;
        $mid->author=Auth::id();
        $mid->status='Empty'; 
        $mid->save();         
         return response()->json(['success'=>'Medicine added successfully']);
      }
    }

    public function update(Request $request)
    {
      $validate=$request->validate([
        'midicine_catagory'=>'required',
        'medicine_name'=>'required',
        'company_name'=>'required', 
    ]);
    if($validate){     
    $mid=Midicines::find($request->med_id);
    $mid->medicine_name=$request->medicine_name;
    $mid->ph_main_cat_id=$request->midicine_catagory;
    $mid->company=$request->company_name;
    $mid->barcode=$request->barcode;
    $mid->save();
    return response()->json(['success'=>'Medicine updated successfully']);
    } 
    }
    public function destroy($id){
    $mid=Midicines::find($id)->delete();
             
    }

    public function medicine_barcode($id)
    {
      $text= Midicines::where('midi_id',$id)->select('barcode','medicine_name')->get();
      if(!empty($text[0]->barcode)){
  
        $code=DNS1D::getBarcodePNG(strval($text[0]->barcode), 'C39',1,60,array(0,0,0), true);

      
        $qrCode = new QrCode();
        $qrCode->setText($text[0]->barcode)
            ->setSize(50)
            ->setPadding(5)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

      return view('admin.pharmacy.midicine_barcode',compact('code','qrCode','text'));
    }else{
     session()->flash('error','Please add barcode first then try!'); 
    return redirect()->back();
  }
}


  public function serach_medicine_name(Request $request){
    $data = [];

    if($request->has('q')){
        $search = $request->q;
        $data=Midicines::
        select('midi_id','medicine_name')
        ->where('medicine_name','LIKE',"%$search%")
        ->get();
    }

    return response()->json($data);
  }
  public function add_medicine_detail($id){
  $medicine_name=Midicines::find($id)->medicine_name;

  $sale=Helper::getmedicinesaleprice($id)->sale_price;
  $expire=Helper::getmedicineexpirydate($id)->expiry_date;
  $quant=Helper::getmedicinequantity($id);

// dd($sale.$expire.$quant);
  $html='<tr id="row'.$id.'">
  <td><input type="text" form="form_medicine" class="form-control text_filed_sm" disabled value="'.$medicine_name.'"></td>
  <td><input type="text" class="form-control text_filed_sm"  disabled value="'.$expire.'">
  <input type="hidden" name="expiryDate[]" value="'.$expire.'">
  <input type="hidden" name="price[]" value="'.$sale.'">
  <input type="hidden" name="medicine_name[]" form="form_medicine" id="hide'.$id.'"  value="'.$id.'">

  </td>
  <td><input type="number"  name="quantity[]" form="form_medicine" class="form-control text_filed_sm invoice_q" max="'.$quant.'"  min="0" step="0.1" data-d="'.$id.'"  required></td>
  <td><input type="number" form="form_medicine" class="form-control text_filed_sm" disabled   value="'.$quant.'"></td>
  <td><input type="number" form="form_medicine" class="form-control text_filed_sm" disabled id="price_item'.$id.'"  value="'.$sale.'"></td>
  <td><input type="number" form="form_medicine"  class="form-control text_filed_sm total_price_final"  disabled id="total_price'.$id.'"></td>
  <td class="text-center "form="form_medicine" data-toggle="tooltip" title="Delete"><a data-ref="'.$id.'" class="btn-sm btn btn-danger text-white del-medicine"><i class="fa fa-trash fa-lg"></a></td>
</tr>';
$final=array(
  'html'=>$html,
  'id'=>$id
);
return response()->json($final);

  }
}
