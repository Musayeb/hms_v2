<?php

namespace App\Http\Controllers\hr;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $emp=Employee::join('departments','departments.dep_id','employees.dep_id')->groupBy('employees.emp_id')->get();
        return view('admin.employee.employee',compact('emp'));
    }
    public function regoption(){
        return view('admin.employee.option');
    }
    public function docterCreate()
    {
        $dep= Departments::all();
        return view('admin.employee.docterRegister',compact('dep'));
    }
    public function create ()
    {
       $dep= Departments::all();
        return view('admin.employee.create',compact('dep'));
    }   

    public function store (Request $request)
    {
        if(isset($request->type)){
            $datavalide = $request->validate([
                'first_name'=>"required",
                'last_name'=>"required",
                'father_name'=>'required',
                'id_or_passport'=>'required',
                'email'=>'required|email',
                'date_of_birth'=>"required",
                'date_of_join'=>"required",
                'gender'=>"required",
                'fees'=>"required",
                'department'=>"required",
                'phone_number'=>"required",
                'marital_status'=>"required",
                'current_address'=>"required",
                'permenent_address'=>"required",
                'position'=>"required",
                'salary'=>"required",
                'emergency_contact'=>"required",
                'relationship'=>"required",
                "cv" => "mimes:pdf|max:2048",
                "contract" => "mimes:pdf|max:2048",
                'photo' => 'image|mimes:jpeg,png,jpg,|max:2048',
                'end_of_contract'=>"required",
                
                ]);
        }else{
            $datavalide = $request->validate([
                'first_name'=>"required",
                'last_name'=>"required",
                'father_name'=>'required',
                'id_or_passport'=>'required',
                'email'=>'required|email',
                'date_of_birth'=>"required",
                'date_of_join'=>"required",
                'gender'=>"required",
                'department'=>"required",
                'phone_number'=>"required",
                'marital_status'=>"required",
                'current_address'=>"required",
                'permenent_address'=>"required",
                'position'=>"required",
                'salary'=>"required",
                'emergency_contact'=>"required",
                'relationship'=>"required",
                "cv" => "mimes:pdf|max:2048",
                "contract" => "mimes:pdf|max:2048",
                'photo' => 'image|mimes:jpeg,png,jpg,|max:2048',
                'end_of_contract'=>"required",

                ]);
        }

        
            if($datavalide==true){
                $check=DB::table('employees')
                ->select(DB::raw('Max(emp_identify_id)as max'))->get();
                if($request->hasFile('cv')){
                    $path= Storage::putFile('/public/cv',$request->file('cv'));
                }else{
                    $path="";
                }
                if($request->hasFile('contract')){
                    $path1= Storage::putFile('/public/contract',$request->file('contract'));
                }else{
                    $path1="";
                }  
                if($request->hasFile('photo')){
                    $path2= Storage::putFile('/public/EmpPhoto',$request->file('photo'));
                }else{
                    $path2="";
                }       
                $emp= new Employee;
                $emp->emp_identify_id=$check[0]->max+1;
                $emp->dep_id=$request->department;
                $emp->position=$request->position;
                $emp->f_name=$request->first_name;
                $emp->l_name=$request->last_name;
                $emp->passport_id=$request->id_or_passport;
                $emp->father_name=$request->father_name;
                $emp->mother_name=$request->mother_name;
                $emp->gender=$request->gender;
                $emp->m_status=$request->marital_status;
                $emp->date_of_birth=$request->date_of_birth;
                $emp->date_of_join=$request->date_of_join;
                $emp->phone_number=$request->phone_number;
                $emp->email=$request->email;
                $emp->tin_number=$request->tin_number;
                $emp->end_of_contract=$request->end_of_contract;
                $emp->email=$request->email;
                $emp->current_address=$request->current_address;
                $emp->permanent_address=$request->permenent_address;
                $emp->salary=$request->salary;
                $emp->fees=$request->fees;
                $emp->photo=$path2;
                $emp->type=$request->type;
                $emp->cv_file=$path;
                $emp->contract_file=$path1;
                $emp->author=Auth::id();
                $emp->emergency_contact=$request->emergency_contact;
                $emp->relationship=$request->relationship;
                $emp->save();
  
                session()->flash('notif','Employee Registred Successfully');
                return redirect('/employees');                      
            }
    }
    public function show($id)
    {
     $emp=Employee::join('departments','departments.dep_id','employees.dep_id')->where('emp_id',$id)->groupBy('employees.emp_id')->get();
     $emp=$emp[0];

     $sum=Payroll::where('emp_id',$id)->sum('net_salary');
     $pay=Payroll::where('emp_id',$id)->orderBy('month_year','DESC')->paginate(20);


     return view('admin.employee.show',compact('emp','sum','pay'));   
    }

    public function download($id,$type)
    {
        $emp=Employee::find($id);
        if($type=="cv_file"){
            $file='app/'.$emp->cv_file;
            return response()->download(storage_path($file));

        }else{
            $file='app/'.$emp->contract_file;
            return response()->download(storage_path($file));
        }
       
    }
    public function edit($id){
        
        $emp=Employee::find($id);
        $dep= Departments::all();
        return view('admin.employee.edit',compact('emp','dep','id'));
    }

    public function update(Request $request)
    {
        $emp=Employee::find($request->emp);
        if($request->hasFile('cv')){
            $path= Storage::putFile('/public/cv',$request->file('cv'));
        }else{
            $path=$emp->cv_file;
        }
        if($request->hasFile('contract')){
            $path1= Storage::putFile('/public/contract',$request->file('contract'));
        }else{
            $path1=$emp->contract_file;
        }  
        if($request->hasFile('photo')){
            $path2= Storage::putFile('/public/EmpPhoto',$request->file('photo'));
        }else{
            $path2=$emp->photo;
        }       
        $emp->dep_id=$request->department;
        $emp->position=$request->position;
        $emp->f_name=$request->first_name;
        $emp->l_name=$request->last_name;
        $emp->passport_id=$request->id_or_passport;
        $emp->father_name=$request->father_name;
        $emp->mother_name=$request->mother_name;
        $emp->gender=$request->gender;
        $emp->m_status=$request->marital_status;
        $emp->date_of_birth=$request->date_of_birth;
        $emp->date_of_join=$request->date_of_join;
        $emp->phone_number=$request->phone_number;
        $emp->email=$request->email;
        $emp->current_address=$request->current_address;
        $emp->permanent_address=$request->permenent_address;
        $emp->salary=$request->salary;
        $emp->photo=$path2;
        $emp->cv_file=$path;
        $emp->contract_file=$path1;
        $emp->emergency_contact=$request->emergency_contact;
        $emp->emergency_contact=$request->fees;
        
        $emp->relationship=$request->relationship;
        $emp->save();
        
        session()->flash('notif','Employee updated successfully');
        return redirect('/employees'); 
    }

    public function destroy($id)
    {
        Employee::find($id)->delete();
    }
}
