<?php

namespace App\Http\Controllers\hr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\Permissions;
use App\Models\role_has_permissions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Helper;

class RolesController extends Controller
{

    


    public function index()
    {
        // Permissions
        $per_data=Permissions::OrderBy('created_at','DESC')->get();
        // roles
        $roles=DB::table('role_has_permissions')
        ->select('roles.id as roles_id','roles.name as roles_name','roles.created_at as role_created')
        ->join('roles','roles.id','role_has_permissions.role_id')
        ->join('permissions','permissions.id','role_has_permissions.permission_id')
        ->groupBy('role_has_permissions.role_id')
        ->orderBy('role_created','desc')
        ->get();

        // Helper::getActivityLog('User ');

        return view('admin.usermanagement.roles',compact('per_data','roles'));
    }

    public function store(Request $request)
    {
        $valid=$request->validate([
            'role_name'=>'required',
            'permissions'=>'required',
        ]);
        if($valid){
            $data=DB::table('roles')->insert([
                'name'=>$request->role_name,
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
            $last_id=DB::getPdo()->lastInsertId();
            foreach ($request->permissions as $value) 
            {
                $data=DB::table('role_has_permissions')->insert([
                    'permission_id'=>$value,
                    'role_id'=> $last_id,
                ]);    
            }
            return  response()->json(['success'=>'Role added successfully']);
        } 
    }  


    public function destroy($id)
    {
        DB::table('roles')->where('id',$id)->delete();
        DB::table('role_has_permissions')->where('role_id',$id)->delete();
        
        return response()->json(['success'=>'Record successfully deleted']);    
    }


    public function edit($id)
    {
        $permission_query=DB::table('role_has_permissions')
        ->select('permissions.name','id')
        ->join('permissions','permissions.id','role_has_permissions.permission_id')
        ->where('role_id',$id)->get();
        
        $permission_all=Permissions::select('name','id')->OrderBy('created_at','DESC')->get();
        return response()->json(array([
            'permission_query'=>$permission_query,
            'permission_all'=>$permission_all
        ]));
    }


    public function update(Request $request)
    {
        $valid=$request->validate([
            'role_name'=>'required',
            'permissions'=>'required',
        ]);
        if($valid)
        {
            $data=DB::table('roles')
            ->where('id',$request->role_hidden)
            ->update([
                'name'=>$request->role_name,
                'updated_at'=>now()
            ]);
            DB::table('role_has_permissions')->where('role_id',$request->role_hidden)->delete();
            foreach ($request->permissions as $value) 
            {
                $data=DB::table('role_has_permissions')->insert([
                    'permission_id'=>$value,
                    'role_id'=> $request->role_hidden,
                ]);    
            }
            return response()->json(['success'=>'Role updated successfully','permissions'=>$request->permissions]); 
        }

        
    }


    
}
