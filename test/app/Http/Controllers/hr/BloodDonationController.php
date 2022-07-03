<?php


namespace App\Http\Controllers\hr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PartialPaymentBilling;
use App\Models\BloodDonation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


use App\Models\Birth;

class BloodDonationController extends Controller
{
    
    public function index(){
        $bloodDonation=BloodDonation::orderBy('created_at','DESC')->paginate(50);
        return view('admin.bloodDonation.index',compact('bloodDonation'));
    }

    public function store(Request $request)
    {
        $datavalidate=$request->validate([
            'receiver_name'=>'required',
            'receiver_phone'=>'required',
            'blood_group'=>'required',
            'gender'=>'required',
            'donor_name'=>'required',
            'donor_phone'=>'required',
            'bag_no'=>'required',
            'amount'=>'required',

        ]);

        if($datavalidate)
        {
            $blood=new BloodDonation;
            $blood->receiver_name=$request->receiver_name;
            $blood->receiver_phone=$request->receiver_phone;
            $blood->blood_group=$request->blood_group;
            $blood->gender=$request->gender;
            $blood->donor_name=$request->donor_name;
            $blood->donor_phone=$request->donor_phone;
            $blood->bag_no=$request->bag_no;
            $blood->amount=$request->amount;
            $blood->user_id=Auth()->id();
            $blood->save();

            return response()->json(['success'=>'blood Donation added successfully']);    
        }
    }

    public function edit($id)
    {
        $birth=BloodDonation::find($id);
        return Response()->json($birth);  
    }

    public function update(Request $request)
    {
        $datavalidate=$request->validate([
            'receiver_name'=>'required',
            'receiver_phone'=>'required',
            'blood_group'=>'required',
            'gender'=>'required',
            'donor_name'=>'required',
            'donor_phone'=>'required',
            'bag_no'=>'required',
            'amount'=>'required',

        ]);

        if($datavalidate)
        {
            $blood=BloodDonation::find($request->hidden_id);
            $blood->receiver_name=$request->receiver_name;
            $blood->receiver_phone=$request->receiver_phone;
            $blood->blood_group=$request->blood_group;
            $blood->gender=$request->gender;
            $blood->donor_name=$request->donor_name;
            $blood->donor_phone=$request->donor_phone;
            $blood->bag_no=$request->bag_no;
            $blood->amount=$request->amount;
            $blood->save();

            return response()->json(['success'=>'Blood Donation updated successfully']);    
        }
    }


    public function destroy($id)
    {
        BloodDonation::find($id)->delete();
        return response()->json(['success'=>'Record successfully deleted']);  
    }




}
