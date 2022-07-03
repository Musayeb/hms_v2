<?php

namespace App\Http\Controllers\hr\bill;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\FinanceLog;
use App\Models\pharmaBill;
use App\Models\Pharma_Main_Catagory;
use App\Models\Patients;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;
use App\Models\PurchaseMidicines;
use App\Models\Midicines;
use App\Models\Opd;
use App\Models\pharmaBill_info;
use Helper;
use Illuminate\Support\Carbon;

class PharmaBillController extends Controller
{
    public function index()
    {
             
        $bill=pharmaBill::
        select('department_name','discount','users.email',
        'bill_no','total','pharma_bills.patient_id','pharma_bills.patient_name','employees.f_name as ef',
        'employees.l_name as el','pharma_bills.created_at as date','bill_id','p_type','p_identify')
        ->join('departments','departments.dep_id','pharma_bills.dep_id')
        ->join('employees','employees.emp_id','pharma_bills.emp_id')
        ->join('users','users.id','pharma_bills.author')
        ->groupBy('bill_id')
        ->orderBy('pharma_bills.created_at','DESC')
        ->paginate(700);

        //         $bill=pharmaBill::
//         select('department_name','discount','users.email',
//         'bill_no','total','pharma_bills.patient_id','pharma_bills.patient_name','employees.f_name as ef',
//         'employees.l_name as el','pharma_bills.created_at as date','bill_id','p_type','p_identify')
//         ->join('departments','departments.dep_id','pharma_bills.dep_id')
//         ->join('employees','employees.emp_id','pharma_bills.emp_id')
//         ->join('users','users.id','pharma_bills.author')
//         ->groupBy('bill_id')
//         ->orderBy('pharma_bills.created_at','DESC')
//         ->first();



// dd($html);


        $cat=Pharma_Main_Catagory::all();
    //     $p=Patients::select('f_name','l_name','patient_id','patient_idetify_number')->get();
    //     $opd=Opd::
    //     select('o_f_name','o_l_name','patient_id')
    // //    ->where('o_f_name','LIKE',"%$search%")
    //    ->get();
    
        $department=Departments::all();

        // dd($bill);
       return view('admin.billing.pharmacy.bill',compact('cat','department','bill'));
    //    return view('admin.billing.pharmacy.bill',compact('cat','p','department','bill','opd'));

    }

    public function search_opd_in_add_bill(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data=Opd::
             select('o_f_name','o_l_name','patient_id')
            ->where('o_f_name','LIKE',"%$search%")
            ->orwhere('o_l_name','LIKE',"%$search%")
            ->orwhere('patient_id','LIKE',"%$search%")
            ->get();
        }

        return response()->json($data);

    }
    public function search_patient_in_add_bill(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data=Patients::
            select('f_name','l_name','patient_id','patient_idetify_number')
            ->where('f_name','LIKE',"%$search%")
            ->orwhere('l_name','LIKE',"%$search%")
            ->orwhere('patient_idetify_number','LIKE',"%$search%")
            ->get();
        }

        return response()->json($data);
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
                $bill=new pharmaBill;
                $bill->bill_no=$request->bill_number;
                $bill->patient_id=$ex[0];
                $bill->p_identify=$ex[1]; 
                $bill->patient_name=$ex[2];             
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->note=$request->note;
                $bill->p_type=$request->patient_type;
                $bill->author=Auth::id();
                $bill->save();
            }else if($request->patient_type=="OPD Patient"){
                $ex=explode('-',$request->patient_name);
                $bill=new pharmaBill;
                $bill->bill_no=$request->bill_number;
                $bill->patient_name=$ex[0];
                $bill->p_identify=$ex[1];              
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->note=$request->note;
                $bill->p_type=$request->patient_type;
                $bill->author=Auth::id();
                $bill->save();
            }else{
                $bill=new pharmaBill;
                $bill->bill_no=$request->bill_number;
                $bill->patient_name=$request->patient_name;
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->note=$request->note;
                $bill->p_type=$request->patient_type;
                $bill->author=Auth::id();
                $bill->save();
            }
            
            $fin= new FinanceLog();
            $fin->payment_type="Pharmacy bill payment";
            $fin->bill_id=$bill->bill_id;
            $fin->total=null;
            $fin->status="Pending";
            $fin->author=Auth::id();
            $fin->save();	

// send new record to view

$bill=pharmaBill::
select('department_name','discount','users.email',
'bill_no','total','pharma_bills.patient_id','pharma_bills.patient_name','employees.f_name as ef',
'employees.l_name as el','pharma_bills.created_at as date','bill_id','p_type','p_identify')
->join('departments','departments.dep_id','pharma_bills.dep_id')
->join('employees','employees.emp_id','pharma_bills.emp_id')
->join('users','users.id','pharma_bills.author')
->groupBy('bill_id')
->orderBy('pharma_bills.created_at','DESC')
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
 $total=Helper::getTotalpharmacyBill($bill->bill_id);
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
        class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed" data-toggle="modal">Add Medicine</a>
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
     'msg'=>'Pharmacy Bill generated Successfully'
 );

 return  response()->json($arr);

    }
}

    public function edit($id)
    {
        $bill=pharmaBill::find($id);
        return response()->json($bill);
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
                
                $bill=pharmaBill::find($request->bill_number_id);
                $bill->bill_no=$request->bill_number;
                $ex=explode('-',$request->patient_names);
                $bill->patient_id=$request->patient_names;
                $bill->patient_name=$ex[2];
                $bill->patient_id=$ex[0];
                $bill->p_identify=$ex[1];     
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->p_type=$request->patient_type;
                $bill->note=$request->note;
                $bill->author=Auth::id();
                $bill->save();

            }else if($request->patient_type=="OPD Patient"){
                $bill=pharmaBill::find($request->bill_number_id);
                $bill->bill_no=$request->bill_number;
                $ex=explode('-',$request->patient_names);
                $bill->patient_id=null;
                $bill->patient_name=$ex[0];
                $bill->p_identify=$ex[1];                  
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->p_type=$request->patient_type;
                $bill->note=$request->note;
                $bill->author=Auth::id();
                $bill->save();
            }else{
                $bill=pharmaBill::find($request->bill_number_id);
                $bill->bill_no=$request->bill_number;
                $bill->patient_id=null;
                $bill->patient_name=$request->patient_names;
                $bill->emp_id=$request->docter_name;
                $bill->dep_id=$request->department;
                $bill->p_type=$request->patient_type;
                $bill->note=$request->note;
                $bill->author=Auth::id();
                $bill->save();
            } 

// send record to view
$bill=pharmaBill::
select('department_name','discount','users.email',
'bill_no','total','pharma_bills.patient_id','pharma_bills.patient_name','employees.f_name as ef',
'employees.l_name as el','pharma_bills.created_at as date','bill_id','p_type','p_identify')
->join('departments','departments.dep_id','pharma_bills.dep_id')
->join('employees','employees.emp_id','pharma_bills.emp_id')
->join('users','users.id','pharma_bills.author')
->groupBy('bill_id')
->where('pharma_bills.bill_id',$request->bill_number_id)
->orderBy('pharma_bills.created_at','DESC')
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
 $total=Helper::getTotalpharmacyBill($bill->bill_id);
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
        class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed" data-toggle="modal">Add Medicine</a>
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
     'msg'=>'Pharmacy Bill updated successfully'
 );

 return  response()->json($arr);

        }
    }
    public function destroy($id)
    {
        pharmaBill::find($id)->delete();
    }
    public function getMedicine($id)
    {
      $total=PurchaseMidicines::select(DB::raw('Sum(quantity)as quant'),
       DB::raw("(SELECT sale_price FROM purchase_midicines WHERE midi_id='".$id."' ORDER by created_at DESC LIMIT 1)as sale_price"),
       DB::raw("(SELECT expiry_date FROM purchase_midicines WHERE midi_id='".$id."' ORDER by created_at DESC LIMIT 1)as expiry_date"))
       ->where('midi_id',$id)
      ->orderBY('created_at','ASC')
      ->limit(1)
      ->get();

      $mid=Midicines::where('midi_id',$id)->sum('sold_quantity');
       $avaliable=$total[0]->quant-$mid;
     
       return Response()->json(array(
        'avaliable' => $avaliable,
        'total' => $total,
        ));   

    }
    public function addmedicine_to_bill(Request $request)
    {
       
       $datavalidate=$request->validate([
           'midicine_catagory'=>'required',
           'medicine'=>'required',
           'quantity'=>'required',
           'sale_price'=>'required',
           'midicine_catagory'=>'required',
           'amount'=>'required',
           'expiry_date'=>'required',
       ]);
        if($datavalidate){

          $info= new pharmaBill_info;
          $info->bill_id=$request->bill_id;
          $info->midi_id=$request->medicine;
          $info->expiry_date=$request->expiry_date;
          $info->quanitity=$request->quantity;
          $info->price=$request->sale_price;
          $info->total=$request->amount;
          $info->author=Auth::id();
          $info->save();

       
        //   $fin=FinanceLog::where('bill_id',$request->bill_id)
        //   ->where('payment_type',"Pharmacy bill payment")->get();

          $total=pharmaBill_info::where('bill_id',$request->bill_id)->sum('total');
          
          $discount=pharmaBill::find($request->bill_id)->discount;
          $discountamount=$discount*$total/100;
          $nettotal=$total-$discountamount;
          $total=$nettotal;

           $fin=FinanceLog::where('bill_id',$request->bill_id)
          ->where('payment_type',"Pharmacy bill payment")
          ->update(['status'=>'Paid','total'=>$total]);


          $mid=Midicines::find($request->medicine);
          $mid->sold_quantity+=(int)$request->quantity;
          $mid->save();

          return  response()->json(['success'=>'Medicine Added to Bill Successfully']);
        }
    }
    
    public function bill_pharmacy_detail($id)
    {
     $info=pharmaBill_info::select('midicines.medicine_name','pharma_bill_infos.*')
        ->join('midicines','midicines.midi_id','pharma_bill_infos.midi_id')
        ->where('bill_id',$id)->get();

        $discount=pharmaBill::find($id)->discount;
        $totals=pharmaBill_info::where('bill_id',$id)->sum('total');


        return Response()->json(array(
            'info' => $info,
            'totals' => $totals,
            'discount'=>$discount,
        ));   

    }

    public function bill_pharmacy_discount(Request $request)
    {
        $bill=pharmaBill::find($request->bill_id);
        $bill->discount=$request->discount;
        $bill->save();


        $total=pharmaBill_info::where('bill_id',$request->bill_id)->sum('total');
        $discount=pharmaBill::find($request->bill_id)->discount;
        $discountamount=$discount*$total/100;
        $nettotal=$total-$discountamount;
        $total=$nettotal;
    
        $fin=FinanceLog::where('bill_id',$request->bill_id)
        ->where('payment_type',"Pharmacy bill payment")
        ->update(['status'=>'Paid','total'=>$total]);


    
    
        return  response()->json(['success'=>'Discount Add Successfully']);
    }

    public function edit_medicine_info($id)
    {
        $info=pharmaBill_info::find($id);
        $quant=PurchaseMidicines::select(DB::raw('Sum(quantity)as quant'))->where('midi_id',$info->midi_id)->orderBY('created_at','DESC')->get();
        $mid_cat=Midicines::find($info->midi_id)->ph_main_cat_id;
        $sold=Midicines::find($info->midi_id)->sold_quantity;
        $avaliable=$quant[0]->quant-$sold;
      
        return Response()->json(array(
            'info' => $info,
            'mid_cat' => $mid_cat,
            'avaliable' => $avaliable,

        ));      
    }

    public function updatemedicine_to_bill(Request $request)
    {
        $datavalidate=$request->validate([
            'midicine_catagory'=>'required',
            'medicine'=>'required',
            'quantity'=>'required',
            'sale_price'=>'required',
            'midicine_catagory'=>'required',
            'amount'=>'required',
            'expiry_date'=>'required',
        ]);
        if($datavalidate){
          
          $info=pharmaBill_info::find($request->bill_id);       
          //   finance
     

          $total=pharmaBill_info::where('bill_id',$info->bill_id)->sum('total');

          $f=(int)$total-(int)$info->total;
          $total=$f+$request->amount;
          
          $discount=pharmaBill::find($info->bill_id)->discount;
          $discountamount=$discount*$total/100;
          $nettotal=$total-$discountamount;
          $total=$nettotal;
 
          $fin=FinanceLog::where('bill_id',$info->bill_id)->where('payment_type',"Pharmacy bill payment")
          ->update(['total'=>$total]);
 
          
          $mid=Midicines::find($request->medicine);
          $total=$mid->sold_quantity-$info->quanitity;
          $mid->sold_quantity =$total;
          $mid->save();
          $info->midi_id=$request->medicine;
          $info->expiry_date=$request->expiry_date;
          $info->quanitity=$request->quantity;
          $info->price=$request->sale_price;
          $info->total=$request->amount;
          $info->save();
         
      

          $mid1=Midicines::find($request->medicine);
          $mid1->sold_quantity +=(int)$request->quantity;
          $mid1->save();
          return  response()->json(['success'=>'Bill Medicine updated Successfully']);

        }
    }
    public function deletemedicine_to_bill($id)
    {
        $pharm=pharmaBill_info::find($id);

        $finance=FinanceLog::where('payment_type','Pharmacy bill payment')->where('bill_id',$pharm->bill_id)->get();
        if($finance[0]->total <= $pharm->total){
            $finance=FinanceLog::where('payment_type','Pharmacy bill payment')->where('bill_id',$pharm->bill_id)->delete();

        }else{
            $total=$finance[0]->total - $pharm->total;
            $finance=FinanceLog::where('payment_type','Pharmacy bill payment')
            ->where('bill_id',$pharm->bill_id)
            ->update(['total'=>$total]);

        }
    
        $mid=Midicines::find($pharm->midi_id);
        $total=$mid->sold_quantity-$pharm->quanitity;
        $mid->sold_quantity =$total;
        $mid->save();

        
        $pharm->delete();

    }

}
