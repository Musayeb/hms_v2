<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use App\Models\Pharma_Main_Catagory;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PharmaMainCatagoryController extends Controller
{
    public function index()
    {
        $cat=Pharma_Main_Catagory::join('users','users.id','pharma__main__catagories.author')->groupBy('ph_main_cat_id')->paginate(50);
        return view('admin.pharmacy.mainCat',compact('cat'));
    }
    public function store(Request $request)
    {
        $datavalide=$request->validate([
         "catagory_name"=>'required'
        ]);
        if($datavalide){
            $main =new Pharma_Main_Catagory;
            $main->m_cat_name=$request->catagory_name;
            $main->author=Auth::id();
            $main->save();
            return response()->json(['success'=>"Catagory added successfully !"]);
        }
    }
    public function update(Request $request)
    {
        $datavalide=$request->validate([
            "catagory_name"=>'required'
        ]);
        if($datavalide){
            $main =Pharma_Main_Catagory::find($request->cat_id);
            $main->m_cat_name=$request->catagory_name;
            $main->save();
            return response()->json(['success'=>"Catagory updated successfully !"]);
        }

    }

    public function destroy($id)
    {
    Pharma_Main_Catagory::find($id)->delete();
    }
}
