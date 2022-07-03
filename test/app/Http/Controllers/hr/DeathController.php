<?php

namespace App\Http\Controllers\hr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PartialPaymentBilling;
use App\Models\Death;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class DeathController extends Controller
{
    public function index()
    {
        $death=Death::orderBy('created_at','DESC')->paginate(50);
        return view('admin.birth&DeathRecord.death',compact('death'));
    }

    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'patient_name'=>'required',
            'gender'=>'required',
            'death_date'=>'required',
            'death_time'=>'required',
            'guardian'=>'required',
            'report'=>'required',
        ]);
        if($datavalidate)
        {
            $birth=new Death;
            $birth->patient_name=$request->patient_name;
            $birth->gender=$request->gender;
            $birth->death_date=$request->death_date;
            $birth->death_time=$request->death_time;
            $birth->guardian=$request->guardian;
            $birth->report=$request->report;
            $birth->user_id=Auth()->id();
            $birth->save();

            return response()->json(['success'=>'Death added successfully']);    
        }
    }

    public function edit($id)
    {
        $death=Death::find($id);
        return Response()->json($death);  
    }

    public function update(Request $request)
    {
        $datavalidate=$request->validate([
            'patient_name'=>'required',
            'gender'=>'required',
            'death_date'=>'required',
            'death_time'=>'required',
            'guardian'=>'required',
            'report'=>'required',

        ]);

        if($datavalidate)
        {
            $birth=Death::find($request->hidden_id);
            $birth->patient_name=$request->patient_name;
            $birth->gender=$request->gender;
            $birth->death_date=$request->death_date;
            $birth->death_time=$request->death_time;
            $birth->guardian=$request->guardian;
            $birth->report=$request->report;
            $birth->save();

            return response()->json(['success'=>'Death updated successfully']);    
        }
    }

    public function destroy($id)
    {
        Death::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);  
    }

    public function print($id)
    {
        $death=Death::find($id);
        $div='<div class="row p-4 table-sm table " style="margin-top: 20px">
        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
                <tr>
                    <th>Patient Name:</th>
                    <td ><small >'.$death->patient_name.'</small></td>
                    <th >Gender</th>
                    <td ><small>'.$death->gender.'</small></td>
                    <th >Guardian</th>
                    <td ><small>'.$death->guardian.'</small></td>
                    
                </tr>
              <tr style="padding-top:10px">
                <th>Death Date:</th>
                <td ><small >'.$death->death_date.'</small></td>
                <th colspan="2">Death Time</th>
                <td colspan="2"><small>'.$death->death_time.'</small></td>
              </tr>
              <tr style="padding-top:10px">
                <th rowspan="2">Report:</th>
                <td rowspan="2" colspan="6"><small >'.$death->report.'</small></td>
              </tr>
            </tbody>
        </table>
    </div>
</div>';

    return  response()->json($div);

        
    }
}
