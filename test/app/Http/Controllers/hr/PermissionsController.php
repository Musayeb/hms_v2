<?php

namespace App\Http\Controllers\hr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\Permissions;


class PermissionsController extends Controller
{

    public function index()
    {
        $permissions=Permissions::OrderBy('created_at','DESC')->get();
        return view('admin.usermanagement.permissions',compact('permissions'));
    }

    public function store(Request $request)
    {
        $valid=$request->validate([
            'permission_name'=>'required',
        ]);
        if($valid)
        {
            $per= new Permissions;
            $per->name=$request->permission_name;
            $per->save();
            return response()->json(['success'=>'Permission added successfully']);
        } 
    } 

    public function update(Request $request)
    {
        $valid= $request->validate([
            'permission_name'=>'required',
        ]);
        if($valid)
        {
            $per=Permissions::find($request->permission_id);
            $per->name=$request->permission_name;
            $per->save();
           return response()->json(['success'=>'Permission updated successfully']);    
        }
    }

    public function destroy($id)
    {
        Permissions::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);    
    }

}
