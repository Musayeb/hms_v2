<?php

namespace App\Http\Controllers\hr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\Permissions;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Auth\User as Authenticatable;


class UserController extends Controller
{
    

    public function index()
    {
        $users=User::OrderBy('created_at','DESC')->get();
        $roles=DB::table('roles')->select('id','name')->get();
        $permissions=DB::table('role_has_permissions')->get();
        return view('admin.usermanagement.users',compact('users','roles','permissions'));
    }


    public function store(Request $request)
    {
        $valid=$request->validate([
            'user_name'=>'required',
            'user_email'=>'required|email|unique:users,email',
            'user_password'=>'required|min:8',
            'password_confirm'=>'required|same:user_password',
            'user_role'=>'required'
        ]);
       if($valid){
            $password= Hash::make($request->user_password);
                $user=new user();
                $user->name=$request->user_name;
                $user->email=$request->user_email;
                $user->password=$password;
                $user->discountp=$request->discount;
                $user->role_id=$request->user_role;
                $user->save();

                return  response()->json(['success'=>'User added successfully']);
            }
    
    }


    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);    
    }


    public function update(Request $request)
    {
            $valid=$request->validate([
                'user_name'=>'required',
                'user_email'=>'required|email',
                'user_role'=>'required'
            ]);
            if($valid)
            {
                $user=User::find($request->user_id_hidden);
                $user->name=$request->user_name;
                $user->email=$request->user_email;
                $user->role_id=$request->user_role;
                $user->discountp=$request->discount;
                $user->updated_at=now();
                $user->save();
                return response()->json(['success'=>'User updated successfully']);  
            } 
    }


    public function reset_password(Request $request)
    {
        $valid=$request->validate([
            'user_password'=>'required|min:8',
            'password_confirm'=>'required|same:user_password',
        ]);
        if($valid)
        {
            $password= Hash::make($request->user_password);
            $user=User::find($request->reset_password_id);
            $user->password=$password;
            $user->updated_at=now();
            $user->save();
            return  response()->json(['success'=>'Password reset updated successfully']);
        }
    }

    public function log()
    {
        $sessions = DB::table('sessions')
        ->select('users.name','users.email','sessions.*')
        ->join('users','users.id','sessions.user_id')
        ->groupby('sessions.user_agent')
        ->paginate(50);
    //    dd($sessions);
        return view('admin.usermanagement.userLog',compact('sessions'));
    }


    
}
