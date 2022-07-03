<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\labCatagory;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LabCatagoryController extends Controller
{
    public function index()
    {
        $cat=labCatagory::join('users','users.id','lab_catagories.author')->groupBy('lab_cat_id')->paginate(50);
        return view('admin.laboratory.catagory',compact('cat'));
    }
    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'catagory_name'=>'required'
        ]);
        if($datavalidate){
            $cat =new labCatagory;
            $cat->lab_cat_name=$request->catagory_name;
            $cat->author=Auth::id();
            $cat->save();
            return response()->json(['success'=>'Catagory added successfully']);    
        }
        
    }
    public function destroy($id)
    {
        labCatagory::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);    

    }
    public function update(Request $request)
    {
        $cat=labCatagory::find($request->cat_id);
        $cat->lab_cat_name=$request->catagory_name;
        $cat->save();
        return response()->json(['success'=>'Catagory updated successfully']);    

    }
}
