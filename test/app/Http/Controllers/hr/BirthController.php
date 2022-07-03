<?php

namespace App\Http\Controllers\hr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PartialPaymentBilling;
use App\Models\Birth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BirthController extends Controller
{

    public function index()
    {
        $birth=Birth::orderBy('created_at','DESC')->paginate(50);
        return view('admin.birth&DeathRecord.birth',compact('birth'));
    }

    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'child_name'=>'required',
            'gender'=>'required',
            'birth_date'=>'required',
            'birth_time'=>'required',
            'father_name'=>'required',
            'mother_name'=>'required',
            'phone_number'=>'required',
            'weight'=>'required',
        ]);
        if($datavalidate)
        {
            $birth=new Birth;
            $birth->child_name=$request->child_name;
            $birth->gender=$request->gender;
            $birth->birth_date=$request->birth_date;
            $birth->birth_time=$request->birth_time;
            $birth->father_name=$request->father_name;
            $birth->mother_name=$request->mother_name;
            $birth->phone_number=$request->phone_number;
            $birth->weight=$request->weight;
            $birth->blood_group=$request->blood_group;          
            $birth->user_id=Auth()->id();
            $birth->save();

            return response()->json(['success'=>'Birth added successfully']);    
        }
    }

    public function edit($id)
    {
        $birth=Birth::find($id);
        return Response()->json($birth);  
    }

    public function update(Request $request)
    {
        $datavalidate=$request->validate([
            'child_name'=>'required',
            'gender'=>'required',
            'birth_date'=>'required',
            'birth_time'=>'required',
            'father_name'=>'required',
            'mother_name'=>'required',
            'phone_number'=>'required',
            'weight'=>'required',
        ]);
        if($datavalidate)
        {
            $birth=Birth::find($request->hidden_id);
            $birth->child_name=$request->child_name;
            $birth->gender=$request->gender;
            $birth->birth_date=$request->birth_date;
            $birth->birth_time=$request->birth_time;
            $birth->father_name=$request->father_name;
            $birth->mother_name=$request->mother_name;
            $birth->phone_number=$request->phone_number;
            $birth->weight=$request->weight;
            $birth->blood_group=$request->blood_group;          
            $birth->save();

            return response()->json(['success'=>'Birth updated successfully']);    
        }
    }

    public function destroy($id)
    {
        Birth::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);  
    }

    public function print($id)
    {
        $birth=Birth::find($id);

        if(empty($birth->blood_group)){
            $blood='N/A';

        }else{
            $blood= $birth->blood_group;
        } 
        $div='<div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Patient Name:</th>
                    <td ><small >'.$birth->child_name.'</small></td>
                    <th >Gender</th>
                    <td ><small>'.$birth->gender.'</small></td>
                    <th >Mother Name</th>
                    <td ><small>'.$birth->mother_name.'</small></td>
                    
                </tr>
              <tr style="padding-top:10px">
                <th>Father Name:</th>
                <td ><small >'.$birth->father_name.'</small></td>
                <th >Birth Date</th>
                <td ><small>'.$birth->birth_date.'</small></td>
                <th >Birth Time</th>
                <td ><small>'.$birth->birth_time.'</small></td>
              </tr>
              <tr style="padding-top:10px">
                <th>Weight:</th>
                <td ><small >'.$birth->weight.' Kg</small></td>
                <th colspan="2">Blood Group:</th>
                <td colspan="2"><small >'.$blood.'</small></td>
              </tr>
            </tbody>
        </table>
    </div>
</div>';

    return  response()->json($div);

        
    }
    
    
}
