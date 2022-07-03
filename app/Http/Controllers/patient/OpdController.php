<?php

namespace App\Http\Controllers\patient;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use Illuminate\Http\Request;
use App\Models\Appoinments;
use App\Models\Employee;
use App\Models\FinanceLog;
use App\Models\LabBill;
use App\Models\Test;

use Illuminate\Support\Facades\Auth;
use App\Models\Opd;
use App\Models\pharmaBill;
use Carbon\Carbon;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Support\Facades\DB;
class OpdController extends Controller
{
    public function index()
    {
        // $dep=Departments::all();
        $app=Appoinments::select('app_number','app_id','p_f_name','p_l_name','f_name','l_name')
        ->join('employees','employees.emp_id','appoinments.emp_id')
        ->where('date',date('Y-m-d'))
        ->get();

         $opd=Opd::select('users.email','opds.*')
             ->join('users','users.id','opds.author')
             ->orderBy('opds.created_at','DESC')
             ->paginate(100);
        
       return view('admin.patients.opd.index',compact('opd','app'));
    }

    public function store(Request $request){
      if($request->type=="app"){
      $datavalidate=$request->validate([
        'patient'=>'required',
        'gender'=>'required',
      ]);
      if($datavalidate){
      $check=Opd::select(DB::raw('Max(patient_id)as max'))->get();
      $app=Appoinments::find($request->patient);

      $opd=new Opd;
      $opd->patient_id=$check[0]->max+1;
      $opd->o_f_name=$app->p_f_name;
      $opd->o_l_name=$app->p_l_name;
      $opd->age=$app->age;
      $opd->date=$app->date;
      $opd->gender=$request->gender;
      $opd->phone=$app->phone;
      $opd->author=Auth::id();
      $opd->save();

      $opd=Opd::select('users.email','opds.*')
      ->join('users','users.id','opds.author')
      ->orderBy('opds.created_at','DESC')
      ->first();
      if(empty( $opd->referred)){ 
        $ref='N/A'; 
      }else{
        $ref=$opd->referred; 
      } 

  $html='<tr id="row'.$opd->opd_id.'">
      <td>1</td>
      <td>OPD-'.$opd->patient_id.'</td>
      <td>'.$opd->o_f_name.' '.$opd->o_l_name.'</td>
      <td>'.$opd->age.'</td>
      <td>'. $opd->phone.'</td>  
      <td>'.$ref.'</td>  
      <td>'.$opd->email.'</td>
     <td><span data-toggle="tooltip" title="'.Carbon::parse($opd->created_at)->diffForHumans().'" >
         '.date('Y-m-d h:i:s a', strtotime($opd->created_at)).'
      </span>
    </td>  
      <td>
          <a data-delete="'.$opd->opd_id.'"
              class="btn btn-danger btn-sm text-white mr-2 delete d-none">Delete</a>
          <a data-toggle="modal" data-target="#edit" data-id="'.$opd->opd_id.'"
              class="btn btn-info btn-sm text-white mr-2 edit d-none">Edit</a>
              <a 
                  class="btn btn-primary btn-sm text-white mr-2 print"  
                  data-patent="'.$opd->o_f_name.' '.$opd->o_l_name.'"
                  data-age="'.$opd->age.'"
                  data-phone="'.$opd->phone.'"
                  data-no="OPD-'. $opd->patient_id.'"
                  data-date="'.date('Y-m-d h:i:s a', strtotime($opd->created_at)).'"
                  data-department="'.$opd->department_name.'" >Print
              </a>
          <a href="'.route('opd.show',$opd->opd_id).'" class="btn btn-success btn-sm text-white mr-2 d-none show_a"  >Show</a>
      </td>
  </tr>';

  $res=array(
    'html'=>$html,
    'msg'=>'OPD Patient Created Successfully',
   );
      return response()->json($res);   								
    
      }
    }else{
        $datavalidate=$request->validate([
          'first_name'=>'required',
          'last_name'=>'required',
          'phone'=>'required',
          'age'=>'required',
          'date'=>'required',
          'gender'=>'required',
        ]);
          if($datavalidate){
          $check=Opd::select(DB::raw('Max(patient_id)as max'))->get();
            $opd=new Opd();
            $opd->patient_id=$check[0]->max+1;
            $opd->o_f_name=$request->first_name;
            $opd->o_l_name=$request->last_name;
            $opd->age=$request->age;
            $opd->date=$request->date;
            $opd->gender=$request->gender;
            $opd->phone=$request->phone;
            $opd->referred=$request->referred_by;
            $opd->author=Auth::id();
            $opd->save();

            $opd=Opd::select('users.email','opds.*')
            ->join('users','users.id','opds.author')
            ->orderBy('opds.created_at','DESC')
            ->first();
            if(empty( $opd->referred)){ 
              $ref='N/A'; 
            }else{
              $ref=$opd->referred; 
            } 
      
        $html='<tr id="row'.$opd->opd_id.'">
            <td>1</td>
            <td>OPD-'.$opd->patient_id.'</td>
            <td>'.$opd->o_f_name.' '.$opd->o_l_name.'</td>
            <td>'.$opd->age.'</td>
            <td>'. $opd->phone.'</td>  
            <td>'.$ref.'</td>  
            <td>'.$opd->email.'</td>
           <td><span data-toggle="tooltip" title="'.Carbon::parse($opd->created_at)->diffForHumans().'" >
               '.date('Y-m-d h:i:s a', strtotime($opd->created_at)).'
            </span>
          </td>  
            <td>
                <a data-delete="'.$opd->opd_id.'"
                    class="btn btn-danger btn-sm text-white mr-2 delete d-none">Delete</a>
                <a data-toggle="modal" data-target="#edit" data-id="'.$opd->opd_id.'"
                    class="btn btn-info btn-sm text-white mr-2 edit d-none">Edit</a>
                    <a 
                        class="btn btn-primary btn-sm text-white mr-2 print"  
                        data-patent="'.$opd->o_f_name.' '.$opd->o_l_name.'"
                        data-age="'.$opd->age.'"
                        data-phone="'.$opd->phone.'"
                        data-no="OPD-'. $opd->patient_id.'"
                        data-date="'.date('Y-m-d h:i:s a', strtotime($opd->created_at)).'"
                        data-department="'.$opd->department_name.'" >Print
                    </a>
                <a href="'.route('opd.show',$opd->opd_id).'" class="btn btn-success btn-sm text-white mr-2 d-none show_a"  >Show</a>
            </td>
        </tr>';
      
        $res=array(
          'html'=>$html,
          'msg'=>'OPD Patient Created Successfully',
         );
            return response()->json($res);   								
          
      }
    }

    }
    public function show ($id)
    {
      
      $dep=Departments::all();
      $opd=Opd::find($id);
      $finance=[];
      $log=[];


      $visit=DB::table('visit')
      ->select('*','visit.created_at as date')
      ->join('employees','employees.emp_id','visit.emp_id')
      ->join('departments','departments.dep_id','visit.dep_id')
      ->join('users','visit.author','users.id')
      ->where('opd_id',$id)->orderBy('visit.created_at','DESC')->get();

      $patient=DB::table('patient_test')->select('patient_test_id')
      ->where('opd_id',$id)
      ->get();

      $vist=DB::table('visit')->select('visit_id')
      ->where('opd_id',$id)
      ->get();
      
      foreach ($patient as $row) {
        $log[]=DB::table('finance_logs')
        ->where('bill_id',$row->patient_test_id)
        ->where('payment_type','OPD patient test payment')
        ->get();
      }
      foreach ($vist as $rows) {
        $log[]=DB::table('finance_logs')
        ->where('bill_id',$rows->visit_id)
        ->where('payment_type','OPD patient revisit payment')
        ->get();
      }
      $finance=$log;

    //   dd($finance);
     
      $test=DB::table('patient_test')
      ->select('*','patient_test.created_at as date')
      ->join('departments','departments.dep_id','patient_test.dep_id')
      ->join('tests','tests.test_id','patient_test.test_id')
      ->join('users','patient_test.author','users.id')
      ->where('opd_id',$id)->orderBy('patient_test.created_at','Desc')->get();


      return view('admin.patients.opd.show',compact('opd','dep','id','visit','finance','test'));
    }

    public function edit($id)
    {
      $data=Opd::find($id);
      return response()->json($data);
    }

    public function update(Request $request)
    {
      $datavalidate=$request->validate([
        'first_name'=>'required',
        'last_name'=>'required',
        'phone'=>'required',
        'age'=>'required',
        'date'=>'required',
        'gender'=>'required',
      ]);
        if($datavalidate){
          $opd=Opd::find($request->opd_id);
          $opd->o_f_name=$request->first_name;
          $opd->o_l_name=$request->last_name;
          $opd->age=$request->age;
          $opd->date=$request->date;
          $opd->gender=$request->gender;
          $opd->phone=$request->phone;
          $opd->referred=$request->referred_by;
          $opd->save();

          $opd=Opd::select('users.email','opds.*')
          ->join('users','users.id','opds.author')
          ->where('opd_id',$request->opd_id)
          ->orderBy('opds.created_at','DESC')
          ->first();

          if(empty( $opd->referred)){ 
            $ref='N/A'; 
          }else{
            $ref=$opd->referred; 
          } 
    
      $html='<tr  id="row'.$opd->opd_id.'">
          <td>1</td>
          <td>OPD-'.$opd->patient_id.'</td>
          <td>'.$opd->o_f_name.' '.$opd->o_l_name.'</td>
          <td>'.$opd->age.'</td>
          <td>'. $opd->phone.'</td>  
          <td>'.$ref.'</td>  
          <td>'.$opd->email.'</td>
         <td><span data-toggle="tooltip" title="'.Carbon::parse($opd->created_at)->diffForHumans().'" >
             '.date('Y-m-d h:i:s a', strtotime($opd->created_at)).'
          </span>
        </td>  
          <td>
              <a data-delete="'.$opd->opd_id.'"
                  class="btn btn-danger btn-sm text-white mr-2 delete d-none">Delete</a>
              <a data-toggle="modal" data-target="#edit" data-id="'.$opd->opd_id.'"
                  class="btn btn-info btn-sm text-white mr-2 edit d-none">Edit</a>
                  <a 
                      class="btn btn-primary btn-sm text-white mr-2 print"  
                      data-patent="'.$opd->o_f_name.' '.$opd->o_l_name.'"
                      data-age="'.$opd->age.'"
                      data-phone="'.$opd->phone.'"
                      data-no="OPD-'. $opd->patient_id.'"
                      data-date="'.date('Y-m-d h:i:s a', strtotime($opd->created_at)).'"
                      data-department="'.$opd->department_name.'" >Print
                  </a>
              <a href="'.route('opd.show',$opd->opd_id).'" class="btn btn-success btn-sm text-white mr-2 d-none show_a"  >Show</a>
          </td>
      </tr>';
    
      $res=array(
        'html'=>$html,
        'msg'=>'OPD patient record updated successfully !',
       );
          return response()->json($res);   		

          // return response()->json(['success'=>"OPD patient record updated successfully !"]);
        }

    }

    public function destroy($id)
    {
      Opd::find($id)->delete();
    }
    public function revisitcreate(Request $request)
    {
      $datavalidate=$request->validate([
        'department'=>'required',
        'doctor'=>'required',
        'doctor_fess'=>'required',
      ]);
      if($datavalidate==true){
       $visit=DB::table('visit')->insertGetId([
          'dep_id'=>$request->department,
          'emp_id'=>$request->doctor,
          'opd_id'=>$request->opd_id,
          'fees'=>$request->doctor_fess,
          'description'=>$request->description,
          'date'=>date('Y-m-d'),
          'status'=>"Paid",      
          'author'=>Auth::id(),   
          'created_at'=>date("Y-m-d h:i:s"),    
        ]);

        $id=DB::getPdo()->lastinsertid();
        $fin= new FinanceLog();
        $fin->payment_type="OPD patient revisit payment";
        $fin->bill_id=$id;
        $fin->total=$request->doctor_fess;  
        $fin->status="Paid";
        $fin->author=Auth::id();
        $fin->save();
        
      return response()->json(['success'=>"OPD Patient Revisited  Successfully !"]);   								
      }
    }
    public function testcreate(Request $request)
    {
      $datavalidate=$request->validate([
        'department'=>'required',
        'test_type'=>'required',
        'test_fees'=>'required',
      ]);
      if($datavalidate==true){
         $test=DB::table('patient_test')->insert([
          'dep_id'=>$request->department,
          'opd_id'=>$request->opd_id,
          'test_id'=>$request->test_type,
          'fees'=>$request->test_fees,
          'description'=>$request->description,
          'status'=>"Paid",      
          'author'=>Auth::id(),   
          'created_at'=>date("Y-m-d h:i:s"),    
        ]);

        $id=DB::getPdo()->lastinsertid();
        $fin= new FinanceLog();
        $fin->payment_type="OPD patient test payment";
        $fin->bill_id=$id;
        $fin->total=$request->test_fees;    
        $fin->status="Paid";
        $fin->author=Auth::id();
        $fin->save();


      return response()->json(['success'=>"OPD Patient Revisited  Successfully !"]);   					
      }
    }

    public function getEditData($id)
    {
      $visit=DB::table('visit')->where('visit_id',$id)->get();
      $emp=Employee::where('dep_id',$visit[0]->dep_id)->get();
      return Response()->json(array(
          'visit' => $visit,
          'emp' => $emp,
      ));
    }
    public function getTestEditData($id)
    {
      $p_test=DB::table('patient_test')->where('patient_test_id',$id)->get();
      $tests=Test::where('dep_id',$p_test[0]->dep_id)->get();
      return Response()->json(array(
          'p_test' => $p_test,
          'tests' => $tests,
      ));
    }

    
    public function updateopdvisit(Request $request)
    {
      $datavalidate=$request->validate([
        'department'=>'required',
        'doctor'=>'required',
        'doctor_fess'=>'required',
      ]);
      if($datavalidate==true){
        DB::table('visit')->where('visit_id',$request->visit_id)
        ->update([
          'dep_id'=>$request->department,
          'emp_id'=>$request->doctor,
          'opd_id'=>$request->opd_id,
          'fees'=>$request->doctor_fess,
          'description'=>$request->description,
          'status'=>"Paid", 
          'updated_at'=>date("Y-m-d h:i:s"), 
          ]);     
        

          FinanceLog::where('bill_id',$request->visit_id)
          ->where('payment_type',"OPD patient revisit payment")
          ->update(['total'=>$request->docter_fess]);


            return response()->json(['success'=>"OPD patient record updated successfully !"]);   					
      }
    }
    
    public function updateopdtest(Request $request)
    {
      $datavalidate=$request->validate([
        'department'=>'required',
        'test_type'=>'required',
        'test_fees'=>'required',
      ]);
      if($datavalidate==true){
        DB::table('patient_test')->where('patient_test_id',$request->test_id)
        ->update([
          'dep_id'=>$request->department,
          'opd_id'=>$request->opd_id,
          'test_id'=>$request->test_type,
          'fees'=>$request->test_fees,
          'description'=>$request->description,
          'updated_at'=>date("Y-m-d h:i:s"),     
          ]);     
  
          FinanceLog::where('bill_id',$request->test_id)
          ->where('payment_type',"OPD patient test payment")
          ->update(['total'=>$request->test_fees]);

            return response()->json(['success'=>"OPD patient record updated successfully !"]);   					
      }
    }

    public function print($type,$id)
    {
     if($type=="visit"){
       $visit=DB::table('visit')
       ->join('employees','employees.emp_id','visit.emp_id')
       ->join('departments','departments.dep_id','visit.dep_id')
       ->where('visit_id',$id)
       ->get();
       $opd=Opd::where('opd_id',$visit[0]->opd_id)
            ->orderBy('opds.created_at','DESC')
            ->get();


       $show='
       <div style="display:flex;margin-top: 20px">
       <div style="width:50%;text-align: left">
           <div class="form-group ">
               <label>OPD Number #: <strong id="bill_no1">OPD-'.$opd[0]->patient_id.'</strong></label>
           </div>
       </div>
       <div style="width: 50%;text-align:right">
           <div class="form-group float-right">
               <label>Register Date: <strong id="bill_date1">'.$opd[0]->date.'</strong></label>
           </div>
       </div>
     </div>
     <div class="row p-4 table-sm table " style="margin-top: 20px">
     <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
         <tbody>
             <tr>
               
                <th align="left">Patient Name</th>
                 <td ><small>'.$opd[0]->o_f_name.' '.$opd[0]->o_l_name.'</small></td>
                                                                        
             </tr>
         </tbody>
     </table>
 </div>
 <p>Docter Visits</p>
 <div class="row p-4 table-sm table " style="margin-top: 20px">
 <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
     <tbody>

         <tr>
             <th>Docter Name:</th>
             <td ><small>'.$visit[0]->f_name.' '.$visit[0]->l_name.'</small></td>
             <th>Fees:</th>
             <td ><small>'.$visit[0]->fees.'</small></td>                                                          
         </tr>
         <tr>
         <th >Description</th>
         <td colspan="4"><small>'.$visit[0]->description.'</small></td>      
         </tr>
         
     </tbody>
 </table>
</div>
   </div>';
return response()->json($show);

     }else if($type=="test"){
      $test=DB::table('patient_test')
      ->join('tests','tests.test_id','patient_test.test_id')
      ->join('departments','departments.dep_id','tests.dep_id')
      ->where('patient_test_id',$id)
      ->get();
      $opd=Opd::where('opd_id',$test[0]->opd_id)
      ->orderBy('opds.created_at','DESC')
      ->get();
      
      $show='
      <div style="display:flex;margin-top: 20px">
      <div style="width:50%;text-align: left">
          <div class="form-group ">
              <label>OPD Number #: <strong id="bill_no1">OPD-'.$opd[0]->patient_id.'</strong></label>
          </div>
      </div>
      <div style="width: 50%;text-align:right">
          <div class="form-group float-right">
              <label>Register Date: <strong id="bill_date1">'.$opd[0]->date.'</strong></label>
          </div>
      </div>
      </div>
      <div class="row p-4 table-sm table " style="margin-top: 20px">
      <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
        <tbody>
            <tr>

                <th align="left">Patient Name</th>
                <td ><small>'.$opd[0]->o_f_name.' '.$opd[0]->o_l_name.'</small></td>
                                                                  
            </tr>
        </tbody>
      </table>
      </div>
      <p>Test</p>
      <div class="row p-4 table-sm table " style="margin-top: 20px">
      <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
      <tbody>

        <tr>
            <th>Test Type:</th>
            <td ><small>'.$test[0]->test_type.'</small></td>
            <th>Fees:</th>
            <td ><small>'.$test[0]->fees.'</small></td>                                                            
        </tr>
        <tr>
        <th >Department</th>
        <td colspan="4"><small>'.$test[0]->department_name.'</small></td>    
        </tr>
        
      </tbody>
      </table>
      </div>
      </div>';
      return response()->json($show);

     }else{

     }

    }

    public function return_fees($id,$type){
      $finance=FinanceLog::find($id);
      if($finance->payment_type=="OPD patient test payment"){
        DB::table('patient_test')->where('patient_test_id',$finance->bill_id)
        ->update(['status'=>'Fees Returned']);

      }
      if($finance->payment_type=="OPD patient revisit payment"){
        DB::table('visit')->where('visit_id',$finance->bill_id)
        ->update(['status'=>'Fees Returned']);
      }
      $finance->status='Fees Returned';
      $finance->save();
      
      session()->flash('notif','Fees Returned Successfully');
      return back();


    }
}
