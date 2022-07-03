<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departments;
class DepartmentsController extends Controller
{
    public function index()
    {
        $departmetns=Departments::join('users','users.id','departments.author')->get();
        return view('admin.departments',compact('departmetns'));
    }

    public function store(Request $request)
    {
        $datavalide = $request->validate([
            'department_name'=>"required",
            ]);
            if($datavalide==true){
                $Departments = new Departments;
                $Departments->department_name=$request->department_name;
                $Departments->author=Auth()->user()->id;
                $Departments->save();
            }

            return response()->json(['success'=>"Department added successfully !"]);

    }
    public function update(Request $request){
        $datavalide = $request->validate(['department_name'=>"required"]);

       if($datavalide==true){
        $Departments=Departments::find($request->dept_id);
        $Departments->department_name=$request->department_name;
        $Departments->save();
        return response()->json(['success'=>"Department updated successfully !"]);

        }
    }

    public function destroy($id){
        $Departments=Departments::find($id)->delete();
        return response()->json(['success'=>"Department updated successfully !"]);
        
    }
}
