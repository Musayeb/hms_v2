<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\LabBill;
use App\Models\Patients;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\FinanceLog;
use App\Models\LabBill_info;
use App\Models\Opd;
use App\Models\pharmaBill;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Helper;
class LabBillController extends Controller
{
    public function index()
    {
        $bill=LabBill::
        select('department_name','discount','users.email','bill_no','total','patient_name','patient_id',
        'employees.f_name as ef','employees.l_name as el','lab_bills.created_at as date','bill_id','p_type','p_identify')
        ->join('departments','departments.dep_id','lab_bills.dep_id')
        ->join('employees','employees.emp_id','lab_bills.emp_id')
        ->join('users','users.id','lab_bills.author')
        ->groupBy('bill_id')
        ->orderBy('lab_bills.created_at','DESC')
        ->paginate(700);

        $department=Departments::all();

       return view('admin.billing.laboratory.bill',compact('department','bill'));
    }

    public function store(Request $request)
    {
        $validate=$request->validate([
            'patient_name'=>'required',
            'bill_number'=>'required',
            'department'=>'required',
            'docter_name'=>'required'
        ]);
        if($validate){
            if($request->patient_type=="Registred Patient"){
                $ex=explode('-',$request->patient_name);
                $bill=new LabBill;
                $bill->bill_no=$request->bill_number;
                $bill->patient_id=$ex[0];
                $bill->p_identify=$ex[1];      
                $bill->patient_name=$ex[2];
                $bill->p_type=$request->patient_type;
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->note=$request->note;
                $bill->author=Auth::id();
                $bill->save();
            }else if($request->patient_type=="OPD Patient"){
                $ex=explode('-',$request->patient_name);

                $bill=new LabBill;
                $bill->bill_no=$request->bill_number;
                $bill->patient_name=$ex[0];
                $bill->p_identify=$ex[1];    
                $bill->patient_id=null;
                $bill->p_type=$request->patient_type;
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->note=$request->note;
                $bill->author=Auth::id();
                $bill->save();
            }else{
                $bill=new LabBill;
                $bill->bill_no=$request->bill_number;
                $bill->patient_name=$request->patient_name;
                $bill->patient_id=null;
                $bill->p_type=$request->patient_type;
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->note=$request->note;
                $bill->author=Auth::id();
                $bill->save();
            }

                $fin= new FinanceLog();
                $fin->payment_type="Laboratory bill payment";
                $fin->bill_id=$bill->bill_id;
                $fin->total=null;
                $fin->status="Pending";
                $fin->author=Auth::id();
                $fin->save();

            $bill=LabBill::
            select('department_name','discount','users.email','bill_no','total','patient_name','patient_id',
            'employees.f_name as ef','employees.l_name as el','lab_bills.created_at as date','bill_id','p_type','p_identify')
            ->join('departments','departments.dep_id','lab_bills.dep_id')
            ->join('employees','employees.emp_id','lab_bills.emp_id')
            ->join('users','users.id','lab_bills.author')
            ->groupBy('bill_id')
            ->orderBy('lab_bills.created_at','DESC')
            ->first();
            


if($bill->p_type=="OPD Patient"){
 $bill_pat = 'opd-'.$bill->p_identify;
}elseif($bill->p_type=="Registred Patient"){
$bill_pat ='p-'.$bill->p_identify;
}else{
$bill_pat ='N/A';
}
if(!empty($bill->discount)){
    $dis= $bill->discount."%";
}else{
    $dis="N/A";
}
 $total=Helper::getTotallabBill($bill->bill_id);
$html='<tr style="background-color:#828cc1 !important" id="row2'.$bill->bill_id.'">
<td>1</td>
<td>'. $bill->bill_no.' </td>
<td>'.$bill->patient_name.'</td>
<td>'. $bill->p_type.'</td>
<td>'.$bill_pat.'</td>
<td>'.$bill->email.'</td>
<td>'.$dis.'</td>
<td> '.$total.'</td>
<td><span data-toggle="tooltip" title="'.Carbon::parse($bill->date)->diffForHumans().'" >
'.date("Y-m-d h:i:s a", strtotime($bill->date)).'
</span> </td>                            
<td>
    <a data-discount="'.$bill->discount.'" data-id="'.$bill->bill_id.'" data-bill="'.$bill->bill_no.'" 
        data-patient="'.$bill->patient_name.'"  data-department="'.$bill->department_name.'"
         data-docter="'.$bill->ef." ".$bill->el.'" data-total="'.$bill->total.'" data-date="'.date("Y-m-d h:i:s a", strtotime($bill->date)).'"
        class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed" data-toggle="modal">Add Test</a>
    <a data-toggle="modal" data-target="#print_modal" class="btn btn-success btn-sm text-white mr-2 print_slip "
    data-discount="'.$bill->discount.'" data-id="'.$bill->bill_id.'" data-bill="'.$bill->bill_no.'" 
        data-patient="'.$bill->patient_name.'"  data-department="'.$bill->department_name.'"
         data-docter="'.$bill->ef." ".$bill->el.'" data-total="'.$bill->total.'" data-date="'.date("Y-m-d h:i:s a", strtotime($bill->date)).'">Print Bill</a>                            
<a data-id="'.$bill->bill_id.'" class="btn btn-danger btn-sm text-white mr-2 delete2 d-none">Delete</a>
<a data-toggle="modal" data-target="#editBill" data-id="'.$bill->bill_id.'" class="btn btn-info btn-sm text-white mr-2 edit_bi d-none">Edit</a>
</td>
</tr>';
 $arr=array(
     'html'=>$html,
     'msg'=>'Lab Bill generated Successfully'
 );

 return  response()->json($arr);
            
            
            // return  response()->json(['success'=>'Lab Bill generated Successfully']);
        }
    }


    public function edit($id)
    {
        $lab=LabBill::find($id);
       return response()->json($lab);
    }

    public function update(Request $request)
    {
        $validate=$request->validate([
            'patient_names'=>'required',
            'bill_number'=>'required',
            'department'=>'required',
            'docter_name'=>'required'
        ]);
        if($validate){

        if($request->patient_type=="Registred Patient"){
            $ex=explode('-',$request->patient_names);
            $bill=LabBill::find($request->bill_ids1234);           
            $bill->patient_id=$ex[0];
            $bill->p_identify=$ex[1];      
            $bill->patient_name=$ex[2];
            $bill->p_type=$request->patient_type;
            $bill->emp_id=$request->docter_name;
            $bill->dep_id=$request->department;
            $bill->note=$request->note;
            $bill->save();
        }else if($request->patient_type=="OPD Patient"){
            $ex=explode('-',$request->patient_names);
            $bill=LabBill::find($request->bill_ids1234);           
            $bill->patient_id=null;
            $bill->p_identify=$ex[1];      
            $bill->patient_name=$ex[0];
            $bill->p_type=$request->patient_type;
            $bill->emp_id=$request->docter_name;
            $bill->dep_id=$request->department;
            $bill->note=$request->note;
            $bill->save();
        }else{
            $bill=LabBill::find($request->bill_ids1234);           
            $bill->patient_id=null;
            $bill->patient_name=$request->patient_names;
            $bill->p_type=$request->patient_type;
            $bill->emp_id=$request->docter_name;
            $bill->dep_id=$request->department;
            $bill->note=$request->note;
            $bill->save();
       }
       $bill=LabBill::
       select('department_name','discount','users.email','bill_no','total','patient_name','patient_id',
       'employees.f_name as ef','employees.l_name as el','lab_bills.created_at as date','bill_id','p_type','p_identify')
       ->join('departments','departments.dep_id','lab_bills.dep_id')
       ->join('employees','employees.emp_id','lab_bills.emp_id')
       ->join('users','users.id','lab_bills.author')
       ->groupBy('bill_id')
        ->where('lab_bills.bill_id',$request->bill_ids1234)
       ->orderBy('lab_bills.created_at','DESC')
       ->first();
       

       if($bill->p_type=="OPD Patient"){
        $bill_pat = 'opd-'.$bill->p_identify;
       }elseif($bill->p_type=="Registred Patient"){
       $bill_pat ='p-'.$bill->p_identify;
       }else{
       $bill_pat ='N/A';
       }
       if(!empty($bill->discount)){
           $dis= $bill->discount."%";
       }else{
           $dis="N/A";
       }
        $total=Helper::getTotallabBill($bill->bill_id);
       $html='<tr style="background-color:#828cc1 !important" id="row2'.$bill->bill_id.'">
       <td>1</td>
       <td>'. $bill->bill_no.' </td>
       <td>'.$bill->patient_name.'</td>
       <td>'. $bill->p_type.'</td>
       <td>'.$bill_pat.'</td>
       <td>'.$bill->email.'</td>
       <td>'.$dis.'</td>
       <td> '.$total.'</td>
       <td><span data-toggle="tooltip" title="'.Carbon::parse($bill->date)->diffForHumans().'" >
       '.date("Y-m-d h:i:s a", strtotime($bill->date)).'
       </span> </td>                            
       <td>
           <a data-discount="'.$bill->discount.'" data-id="'.$bill->bill_id.'" data-bill="'.$bill->bill_no.'" 
               data-patient="'.$bill->patient_name.'"  data-department="'.$bill->department_name.'"
                data-docter="'.$bill->ef." ".$bill->el.'" data-total="'.$bill->total.'" data-date="'.date("Y-m-d h:i:s a", strtotime($bill->date)).'"
               class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed" data-toggle="modal">Add Test</a>
           <a data-toggle="modal" data-target="#print_modal" class="btn btn-success btn-sm text-white mr-2 print_slip "
           data-discount="'.$bill->discount.'" data-id="'.$bill->bill_id.'" data-bill="'.$bill->bill_no.'" 
               data-patient="'.$bill->patient_name.'"  data-department="'.$bill->department_name.'"
                data-docter="'.$bill->ef." ".$bill->el.'" data-total="'.$bill->total.'" data-date="'.date("Y-m-d h:i:s a", strtotime($bill->date)).'">Print Bill</a>                            
       <a data-id="'.$bill->bill_id.'" class="btn btn-danger btn-sm text-white mr-2 delete2 d-none">Delete</a>
       <a data-toggle="modal" data-target="#editBill" data-id="'.$bill->bill_id.'" class="btn btn-info btn-sm text-white mr-2 edit_bi d-none">Edit</a>
       </td>
       </tr>';
        $arr=array(
            'html'=>$html,
            'msg'=>'Lab Bill updated Successfully'
        );
       
        return  response()->json($arr);
            
            // return  response()->json(['success'=>'Lab Bill updated Successfully']);
        }
        
    }

    function destroy($id)
    {
        LabBill::find($id)->delete();
        FinanceLog::where('payment_type','Laboratory bill payment')->where('bill_id',$id)->delete();   
    }
    public function get_test_using_dep($id)
    {
        $test=Test::where('dep_id',$id)->get();
        // dd($test);
        return response()->json($test);
    }

    public function gettest_info($id)
    {
        $test=Test::find($id)->fees;
        return response()->json($test);

    }

    public function getDocter($id)
    {
        $emp=Employee::where('dep_id',$id)->get();
        return response()->json($emp);
    }

    public function discount(Request $request)
    {
        $bill=LabBill::find($request->bill_id);
        $bill->discount=$request->discount;
        $bill->save();

              
        $total=LabBill_info::where('bill_id',$request->bill_id)->sum('total');
        $discount=LabBill::find($request->bill_id)->discount;
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total=$nettotal;

        $fin=FinanceLog::where('bill_id',$request->bill_id)
        ->where('payment_type',"Laboratory bill payment")
        ->update(['status'=>'Paid','total'=>$total]);

        return  response()->json(['success'=>'Discount Add Successfully']);
    }
}
