<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\labMaterial;
use App\Models\labCatagory;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LabMaterialController extends Controller
{
      public function index()
    {
        $cat=labCatagory::all();
        $mat=labMaterial::
          join('users','users.id','lab_materials.author')
         ->join('lab_catagories','lab_catagories.lab_cat_id','lab_materials.lab_cat_id')
         ->groupBy('lab_materials.lab_m_id')
         ->paginate(50);
        return view('admin.laboratory.materials',compact('cat','mat'));
    }
    public function store(Request $request)
    {
      $validate=$request->validate([
          'material_catagory'=>'required',
          'material_name'=>'required',
          'company_name'=>'required', 
      ]);
      if($validate){
        $mid= new labMaterial;
        $mid->material_name=$request->material_name;
        $mid->lab_cat_id=$request->material_catagory;
        $mid->company=$request->company_name;
        $mid->author=Auth::id();
        $mid->status='Empty'; 
        $mid->save();         
         return response()->json(['success'=>'Lab Material added successfully']);
      }
    }
    public function filter($id)
    {
      
      $mat=labMaterial::where('lab_cat_id',$id)->get();
      return response()->json($mat);

    }
    public function update (Request $request){
        $validate=$request->validate([
          'material_catagory'=>'required',
          'material_name'=>'required',
          'company_name'=>'required', 
      ]);
      if($validate){
        $mid=labMaterial::find($request->id_material);
         $mid->material_name=$request->material_name;
        $mid->lab_cat_id=$request->material_catagory;
        $mid->company=$request->company_name;
        $mid->save();
        return response()->json(['success'=>'Lab Material updated successfully']);
      }

    }
}
