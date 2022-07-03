<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\Pharma_Main_Catagory;
use App\Models\Midicines;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MidicinesController extends Controller
{
    public function index()
    {
        $cat=Pharma_Main_Catagory::all();
        $mid=Midicines::
          join('users','users.id','midicines.author')
         ->join('pharma__main__catagories','pharma__main__catagories.ph_main_cat_id','midicines.ph_main_cat_id')
         ->groupBy('midicines.midi_id')
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
    $mid->save();
    return response()->json(['success'=>'Medicine updated successfully']);
    } 
    }
    public function destroy($id){
    $mid=Midicines::find($id)->delete();
             
    }
}
