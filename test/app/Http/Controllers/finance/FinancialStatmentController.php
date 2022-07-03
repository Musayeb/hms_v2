<?php

namespace App\Http\Controllers\finance;
use App\Http\Controllers\Controller;
use App\Models\FinanceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class FinancialStatmentController extends Controller
{
    public function index()
    { 
      $filter=DB::table('filter')->select('filter.percentage')->orderBy('filter.created_at','DESC')->limit(1)->get();
      if(empty($filter)){
        $statment=FinanceLog::select('users.email','finance_logs.*')
        ->join('users','users.id','finance_logs.author')
        ->whereMonth('finance_logs.created_at',Date('m'))
        ->whereMonth('finance_logs.status','!=','Pending')
        ->orderBy('finance_logs.created_at',"DESC")->get();
   
        $totalEx=FinanceLog::select('finance_logs.*')
        ->whereMonth('finance_logs.created_at',Date('m'))
        ->where('finance_logs.type','Expense')
        ->sum('total');
        
        $totalin=FinanceLog::select('finance_logs.*')
        ->whereMonth('finance_logs.created_at',Date('m'))
        ->where('finance_logs.type',null)
        ->sum('total');
      }else{
        $precentage=100-$filter[0]->percentage;
        $precentage=$precentage/100;
        $date=Date('m');
    
        $statment10=DB::select("SELECT *
              FROM(
                  SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
                  FROM (select @counter:=0) initvar,finance_logs 
                  INNER JOIN users on id=author
                  where month(finance_logs.created_at)=".$date."
                  and status !='Pending'
                  AND type IS null
                  and (f_id%2)!=0
                  ORDER BY created_at ASC    
              ) X
              WHERE X.counter <= (".$precentage." * @counter)");


              $statment11=DB::select("SELECT *
              FROM(
                  SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
                  FROM (select @counter:=0) initvar,finance_logs 
                  INNER JOIN users on id=author
                  where month(finance_logs.created_at)=".$date."
                  AND type IS null
                  and status !='Pending'
                  and (f_id%2)=0
                  ORDER BY created_at ASC    
              ) X
              WHERE X.counter <= (".$precentage." * @counter)");
               $statment=array_merge($statment10,$statment11);
            
              $statment1=DB::table('finance_logs')
              ->select('users.email','finance_logs.*')
              ->join('users','users.id','finance_logs.author')
              ->whereMonth('finance_logs.created_at',Date('m'))
              ->where('type',"Expense")
              ->where('finance_logs.status','!=','Pending')
              ->orderBy('finance_logs.created_at',"DESC")->get();

              $totalEx=FinanceLog::select('finance_logs.*')
              ->whereMonth('finance_logs.created_at',Date('m'))
              ->where('finance_logs.type','Expense')
              ->where('finance_logs.status','!=','Pending')
              ->sum('total');
              
             
                $income1=DB::select("SELECT *,sum(total)as total1
                FROM(
                    SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
                    FROM (select @counter:=0) initvar,finance_logs 
                    INNER JOIN users on id=author
                    where month(finance_logs.created_at)=".$date."
                    AND type IS null
                    and status !='Fees Returned'
                    and (f_id%2)!=0
                    ORDER BY created_at ASC    
                ) X
                WHERE X.counter <= (".$precentage." * @counter)");

                $income2=DB::select("SELECT *,sum(total)as total1
                FROM(
                    SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
                    FROM (select @counter:=0) initvar,finance_logs 
                    INNER JOIN users on id=author
                    where month(finance_logs.created_at)=".$date."
                    AND type IS null
                    and status !='Fees Returned'
                    and (f_id%2)=0
                    ORDER BY created_at ASC    
                ) X
                WHERE X.counter <= (".$precentage." * @counter)");
                

              
          $statment=array_merge($statment1->toArray(),$statment);
           $t=array_column($statment, 'f_id');
           $final=array_multisort($t, SORT_ASC, $statment);



      }
    
    return view('admin.finance.financial_statment',compact('statment','income2','income1','totalEx'));
    }
    public function filter(Request $request)
    {
      $filter=DB::table('filter')->select('filter.percentage')
      ->orderBy('filter.created_at','DESC')
      ->limit(1)
      ->get();
      $precentage=100-$filter[0]->percentage;
      $precentage=$precentage/100;

        $date=explode('-',$request->date);
        $date1=$date[0];
        $date2=$date[1];
        $date1 = date("Y-m-d",strtotime($date[0]));
        $date2 = date("Y-m-d",strtotime($date[1]));


        $evenstatment=DB::select("SELECT *
        FROM(
            SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
            FROM (select @counter:=0) initvar,finance_logs 
            INNER JOIN users on id=author
            WHERE   date(finance_logs.created_at) between '" .$date1 . "' AND  '" .$date2 . "'
          AND type IS null
          and status !='Fees Returned'
          and (f_id%2)=0
          ORDER BY created_at ASC    
      ) X
      WHERE X.counter <= (".$precentage." * @counter)");
    
    $oddstatment=DB::select("SELECT *
        FROM(
            SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
            FROM (select @counter:=0) initvar,finance_logs 
            INNER JOIN users on id=author
            WHERE   date(finance_logs.created_at) between '" .$date1 . "' AND  '" .$date2 . "'
            AND type IS null
            and status !='Pending'
            and (f_id%2)!=0
            ORDER BY created_at DESC    
        ) X
        WHERE X.counter <= (".$precentage." * @counter)");

        $statment=array_merge($evenstatment,$oddstatment);

        $statment1=DB::table('finance_logs')
        ->select('users.email','finance_logs.*')
        ->join('users','users.id','finance_logs.author')
        ->whereBetween('finance_logs.created_at', [$date1, $date2])
        ->where('finance_logs.type',"Expense")
        ->where('finance_logs.status','!=','Pending')
        ->orderBy('finance_logs.created_at',"DESC")->get();
  
        $income1=DB::select("SELECT *,sum(total)as total1
        FROM(
            SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
            FROM (select @counter:=0) initvar,finance_logs 
            INNER JOIN users on id=author
            WHERE   date(finance_logs.created_at) between '" .$date1 . "' AND  '" .$date2 . "' 
            AND type IS null
            and (f_id%2)!=0
            and status !='Fees Returned'
            ORDER BY created_at DESC    
        ) X
        WHERE X.counter <= (".$precentage." * @counter)");

        $income2=DB::select("SELECT *,sum(total)as total1
        FROM(
            SELECT   finance_logs.* ,users.email,@counter := @counter +1 counter
            FROM (select @counter:=0) initvar,finance_logs 
            INNER JOIN users on id=author
            WHERE   date(finance_logs.created_at) between '" .$date1 . "' AND  '" .$date2 . "'        
            AND type IS null
            and status !='Pending'
            and status !='Fees Returned'
            and (f_id%2)=0
            ORDER BY created_at ASC   
        ) X
        WHERE X.counter <= (".$precentage." * @counter)");

              // dd($income2[0]->total1+$income1[0]->total1);
        $statment=array_merge($statment1->toArray(),$statment);
        $t=array_column($statment, 'f_id');
        $final=array_multisort($t, SORT_ASC, $statment);



      $totalEx=FinanceLog::select('finance_logs.*')
      ->whereBetween('finance_logs.created_at', [$date1, $date2])
      ->where('finance_logs.type','Expense')
      ->where('finance_logs.status','!=','Pending')
      ->sum('total');

      return view('admin.finance.financial_statment',compact('statment','income1','income2','totalEx'));
        
    }
   
}
