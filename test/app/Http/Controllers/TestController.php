<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Departments;
use Illuminate\Support\Facades\Auth;


class TestController extends Controller
{
   public function index()
   {
       $test=Test::join('users','users.id','tests.author')->join('departments','departments.dep_id','tests.dep_id')->groupBy('tests.test_id')->get();
       $dep= Departments::all();
       return view('admin.test',compact('test','dep'));
   }
   public function store(Request $request)
   {
    $datavalide = $request->validate([
        'test_type'=>"required",
        'department'=>"required",
        'fees'=>"required",
    ]);
    if( $datavalide==true){
       $test= new Test;
       $test->test_type=$request->test_type;
       $test->dep_id=$request->department;
       $test->fees=$request->fees;
       $test->author=Auth::id();
       $test->save();
       return response()->json(['success'=>"Test added successfully !"]);

    }
   }
   public function update(Request $request)
   {
    $datavalide = $request->validate([
        'test_type'=>"required",
        'department'=>"required",
        'fees'=>"required",
    ]);
    if( $datavalide==true){
       $test=Test::find($request->test_id);
       $test->test_type=$request->test_type;
       $test->dep_id=$request->department;
       $test->fees=$request->fees;
       $test->author=Auth::id();
       $test->save();
       return response()->json(['success'=>"Test Updated successfully !"]);

    }
   }
   public function destroy ($id){
       Test::find($id)->delete();
       return response()->json(['success'=>"Test Updated successfully !"]);
   }
}
