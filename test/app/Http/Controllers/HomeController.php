<?php

namespace App\Http\Controllers;

use App\Mail\ApprovalEmail;
use App\Models\Appoinments;
use App\Models\companyBill;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\FinanceLog;
use App\Models\Opd;
use App\Models\Patients;
use App\Models\Pettycash;
use App\Models\PurchaselabMaterial;
use App\Models\PurchaseMidicines;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Laravel\Jetstream\Rules\Role;

use Helper;

class HomeController extends Controller
{

  public function index1()
  {
    return view('index');
  }
  public function index()
  {

    $app = Appoinments::select('employees.f_name', 'employees.l_name', 'departments.department_name', 'date', 'appoinments.emp_id')
      ->join('employees', 'employees.emp_id', 'appoinments.emp_id')
      ->join('departments', 'departments.dep_id', 'appoinments.dep_id')
      ->where('date', date('y-m-d'))
      ->groupBy('employees.emp_id')
      ->orderBy('patient_id', 'DESC')->get();

      $patient=Patients::count('patient_id');
      $opd=Opd::count('opd_id');
      $employee=Employee::where('fees','!=','')->count('emp_id');
      $department=Departments::count('dep_id');

      $patient_s=Patients::select(DB::raw('Month(created_at) as month'),
      DB::raw('COUNT(patient_id)as total'),DB::raw('Year(created_at)as year'))
     ->whereYear('created_at',date('Y'))->groupBy('year','month')->get();

     $chart_data="";
     foreach ($patient_s as $query) {
      $month=date("F", mktime(0,0,0, $query->month, 10));       
     $chart_data.="{month:'".$month."',Patient:".$query->total."},";
    }
     $chart_data=substr($chart_data, 0,-2); 
  //2
  
    $app_s=Appoinments::select(DB::raw('Month(date) as month'),
    DB::raw('COUNT(app_id)as total'),DB::raw('Year(date)as year'))
  ->whereYear('date',date('Y'))->groupBy('year','month')->get();
    $chart_data1="";
    foreach ($app_s as $query1) {
    $month1=date("F", mktime(0,0,0, $query1->month, 10));       
    $chart_data1.="{month:'".$month1."',Appoinment:".$query1->total."},";
  }
    $chart_data1=substr($chart_data1, 0,-2); 
// 3
  $opd_s=Opd::select(DB::raw('Month(created_at) as month'),
  DB::raw('COUNT(opd_id)as total'),DB::raw('Year(created_at)as year'))
  ->whereYear('created_at',date('Y'))->groupBy('year','month')->get();
  $chart_data2="";
  foreach ($opd_s as $query2) {
  $month2=date("F", mktime(0,0,0, $query2->month, 10));       
  $chart_data2.="{month:'".$month2."',OPD:".$query2->total."},";
  }
  $chart_data2=substr($chart_data2, 0,-2); 

  $date=date('Y');

  $finacne_s=DB::select("SELECT Month(created_at)as month,
  (select Sum(total) from finance_logs where type is null and Month(created_at)=month)as income ,
  (select Sum(total) from finance_logs where type='Expense' and Month(created_at)=month) as expense FROM finance_logs where Year(created_at) = '$date'  GROUP by month");
  $chart_data3="";
  foreach ($finacne_s as $query3) {
  $month3=date("F", mktime(0,0,0, $query3->month, 10));    
  if(empty($query3->expense)){
    $ex=1;
  }
  else{
    $ex=$query3->expense;
  }   
  $chart_data3.="{month:'".$month3."',Expense:".$ex.",Income:".$query3->income."},";
  }
  $chart_data3=substr($chart_data3, 0,-2); 
  // 
// dd( $chart_data3);
    return view('dashboard', compact(
    'chart_data',
    'chart_data1',
    'chart_data2',
    'chart_data3',
    'app',
    'patient'
    ,'opd'
    ,'employee'
    ,'department'));
  }

  public function settings()
  {

    $sessions = DB::table('sessions')->where('user_id', Auth::user()->id)->get();
    return view('admin.settings', compact('sessions'));
  }

  public function deleteothersession()
  {
    if (config('session.driver') !== 'database') {
      return;
    }

    DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
      ->where('user_id', Auth::user()->getAuthIdentifier())
      ->where('id', '!=', request()->session()->getId())
      ->delete();
    return back();
  }

  public function avetar_change(Request $request)
  {
    $folderPath = "public";
    $image_parts = explode(";base64,", $request->image);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = uniqid() . '.png';
    $path1 = '/' . $file;
    $path = Storage::disk('avatar')->put($file, $image_base64);

    $user = User::find(Auth::id());
    $user->profile_photo_path = $file;
    $user->save();


    echo json_encode(["image uploaded successfully."]);
  }

  public function password_change(Request $request)
  {

    $datava = $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|min:8',
      'password_confirmation' => 'required|same:new_password',
    ]);
    if ($datava) {
      if (Hash::check($request->current_password , Auth()->user()->password)) {
        $pass=Hash::make($request->new_password);
        $user=User::find(Auth::id());
        $user->password=$pass;
        $user->save();
        session()->flash('success','Password successfully changed !');
        return back();
       
      }else{
        session()->flash('error','Current Password is wrong !');
        return back();

      }
    }
  }

  public function change_detail(Request $request)
  {
    $datava = $request->validate([
      'username' => 'required',
      'email_address' => 'required',
    ]);
    if($datava){
      $user=User::find(Auth::id());
      $user->name=$request->username;
      $user->email=$request->email_address;
      $user->save();
      session()->flash('success','Detail successfully changed !');
      return back();
    }

  }
  public function connect()
  {
    
  }



  public function payrollApproval()
  {
    $role=DB::table('roles')->where('name','Super Admin')->get();
    
    $recever=User::where('role_id',$role[0]->id)->get();
    $sender=User::find(Auth::id());

    foreach ($recever as $row) {
      $details=[
        'Receiver'=>$row->name,
        'sender'=>$sender->name,
        'link'=>url('payroll'),
        'relate_to'=>'Payrolls'
      ];
       Mail::to($row->email)->send(new ApprovalEmail($details));
    }
  }
  public function pharmacyApproval()
  {
    $role=DB::table('roles')->where('name','Super Admin')->get();
    $recever=User::where('role_id',$role[0]->id)->get();
    $sender=User::find(Auth::id());

    foreach ($recever as $row) {
      $details=[
        'Receiver'=>$row->name,
        'sender'=>$sender->name,
        'link'=>url('purchase-mediciens'),
        'relate_to'=>'Purchase Medicines'
      ];
       Mail::to($row->email)->send(new ApprovalEmail($details));
    }
  }
  public function medicineaproval(Request $request)
  {
    $role=DB::table('roles')->where('name','Super Admin')->get();
    $recever=User::where('role_id',$role[0]->id)->get();
    $sender=User::find(Auth::id());

    foreach ($recever as $row) {
      $details=[
        'Receiver'=>$row->name,
        'sender'=>$sender->name,
        'link'=>url('purchase-mediciens'),
        'relate_to'=>'Purchase Medicines'
      ];
       Mail::to($row->email)->send(new ApprovalEmail($details));
    }
  }
  public function medicinechangeStatus(Request $request)
  {
   PurchaseMidicines::where('purchase_m_id',$request->id)->update(['status'=>$request->status]);
   return response()->json(['success'=>'Purchase Medicine status changed successfully']);

  }


  public function labaproval()
  {
    $role=DB::table('roles')->where('name','Super Admin')->get();
    $recever=User::where('role_id',$role[0]->id)->get();
    $sender=User::find(Auth::id());

    foreach ($recever as $row) {
      $details=[
        'Receiver'=>$row->name,
        'sender'=>$sender->name,
        'link'=>url('lab-purchase-materials'),
        'relate_to'=>'Purchase Laboratory Materials'
      ];
       Mail::to($row->email)->send(new ApprovalEmail($details));
    }
  }

  public function labmaterialchangeStatus(Request $request)
  {
   PurchaselabMaterial::where('lab_purchase_id',$request->id)->update(['status'=>$request->status]);
   return response()->json(['success'=>'Purchase Laboratory status changed successfully']);

  }
  // daily Expense
  
  public function dailyexpensesaproval()
  {
    $role=DB::table('roles')->where('name','Super Admin')->get();
    $recever=User::where('role_id',$role[0]->id)->get();
    $sender=User::find(Auth::id());

    foreach ($recever as $row) {
      $details=[
        'Receiver'=>$row->name,
        'sender'=>$sender->name,
        'link'=>url('finance/daily_expenses'),
        'relate_to'=>'Daily Expenses'
      ];
       Mail::to($row->email)->send(new ApprovalEmail($details));
    }
  }

  public function dailyexpenseschangeStatus(Request $request)
  {
   Pettycash::where('cash_id',$request->id)->update(['status'=>$request->status]);
   if($request->status=='Approved'){
    FinanceLog::where('Bill_id',$request->id)->where('payment_type','Daily Expenses Payment')->update(['status'=>"Paid"]);
   }
   return response()->json(['success'=>'Daily expenses status changed successfully']);
  }


  public function filter_show()
  {
  	
  	if (!empty(Helper::getpermission('access_filter'))){
    $filter=DB::table('filter')->select('users.email','filter.*')->join('users','users.id','filter.author')->orderBy('filter.created_at','DESC')->limit(1)->get();
    return view('admin.filter',compact('filter'));
  	}else{
  	    abort(403);
  	}
  }

  public function filter_add(Request $request)
  {
    
    DB::table('filter')->insert([
      'author' => Auth::id(),
      'percentage'=>$request->percentage,
      'created_at'=>now(),
      ]);

      session()->flash('notif','Filter percentage set successfully');
      return back();
  }

  // Report
  public function opd_report()
  {
    
    $docter=Employee::where('fees','!=',null)->get();
    return view('admin.report.opd',compact('docter'));
  }

  public function opd_report_filter(Request $request)
  {
    $date=explode('-',$request->date);

    $date1 = date("Y-m-d",strtotime($date[0]));
    $date2 = date("Y-m-d",strtotime($date[1]));

    $opd=DB::table('visit')
    ->join('departments','departments.dep_id','visit.dep_id')
    ->join('employees','employees.emp_id','visit.emp_id')
    ->join('opds','opds.opd_id','visit.opd_id')


    ->whereBetween('visit.date',[$date1,$date2])
    
    ->where('visit.emp_id',$request->docter)
    ->get();
    $docter=Employee::where('fees','!=',null)->get();
    return view('admin.report.opd',compact('docter','opd'));
  }

  public function pharmacy_report()
  { 
    return view('admin.report.pharmacy');
  }


  public function pharmacy_report_filter(Request $request)
  {
    $date=explode('-',$request->date);

    $date1 = date("Y-m-d",strtotime($date[0]));
    $date2 = date("Y-m-d",strtotime($date[1]));

    $ph=FinanceLog::select('users.email','finance_logs.*')
    ->join('users','users.id','finance_logs.author')
    ->where('payment_type','Pharmacy bill payment')
    ->whereDate('finance_logs.created_at', '>=', $date1)
    ->whereDate('finance_logs.created_at', '<=', $date2)
    ->get();
    return view('admin.report.pharmacy',compact('ph'));
  }



  public function lab_report()
  {
    return view('admin.report.lab');

  }
  
  public function lab_report_filter(Request $request)
  {
    $date=explode('-',$request->date);

    $date1 = date("Y-m-d",strtotime($date[0]));
    $date2 = date("Y-m-d",strtotime($date[1]));

    $ph=FinanceLog::select('users.email','finance_logs.*')->join('users','users.id','finance_logs.author')->where('payment_type','Laboratory bill payment')->whereDate('finance_logs.created_at', '>=', $date1)->whereDate('finance_logs.created_at', '<=', $date2)->get();
    return view('admin.report.lab',compact('ph'));
  }

}
