@extends('layouts.admin')
@section('title') Employee Info @endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="wideget-user text-center p-3">
                    <div class="wideget-user-desc pt-5">
                        <div class="wideget-user-img">
                            @if(!empty($emp->photo)) 
                            <img src="{{url('storage/app')}}/{{$emp->photo}}" alt="img"  style="height:100px;width:100px">
                            @else
                            <img src="{{asset('public/empty.png')}}" alt="img" style="height:100px;width:100px">
                            @endif
                        </div>
                        <div class="user-wrap">
                            <h3 class="pro-user-username text-dark">{{$emp->f_name.' '.$emp->l_name}}</h3>
                            <h6 class="text-muted mb-2">{{$emp->position}}</h6>
                            <h6 class="text-muted mb-2">{{$emp->department_name}}</h6>
                            <h6 class="text-muted mb-2">Employee ID:{{'PMS-EMP00'.$emp->emp_identify_id}}</h6>                    
                        </div>
                    </div>
                
                </div>
            </div>
        
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Personal Info</h3>
            </div>
            <div class="card-body">
                <div class="media-list">
                    <div class="media mt-1 pb-2">
                        <div class="mediaicon">
                            <i class="fe fe-user" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Full Name</h6>
                            <span class="d-block">{{$emp->f_name.' '.$emp->l_name}}</span>
                        </div>
                    </div>
                    <div class="media mt-1 pb-2">
                        <div class="mediaicon">
                            <i class="fe fe-mail" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Email Address</h6>
                            <span class="d-block">{{$emp->email}}</span>
                        </div>
                    </div>
                    <div class="media mt-1 pb-2">
                        <div class="mediaicon">
                            <i class="fe fe-map-pin" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Current Address</h6>
                            <span class="d-block">{{$emp->current_address}}</span>
                        </div>
                    </div>
                    <div class="media mt-1 pb-2">
                        <div class="mediaicon">
                            <i class="fe fe-phone" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Phone Number</h6>
                            <span class="d-block">{{$emp->phone_number}}</span>
                        </div>
                    </div>
            
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-12">

        <div class="card w-100">
            <div class="card-header p-0">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-61" class="active show" data-toggle="tab">Profile</a></li>
                                <li><a href="#tab-71" data-toggle="tab" class="">Payroll</a></li>
                                <li><a href="#tab-81" data-toggle="tab" class="">Document</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="border-0">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab-61">
                            <div class="tab-pane active" id="activity">
                                <div class="tshadow mb25 bozero">
                                    <div class="table-responsive around10 pt0">
                                        <table class="table table-sm table-hover table-bordered ">
                                            <tbody>
                                                <tr>
                                                    <td>Full Name</td>
                                                    <td>{{$emp->f_name.' '.$emp->l_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ID/Passport</td>
                                                    <td>{{$emp->passport_id}}</td>
                                                </tr>
                                                  <tr>
                                                    <td>Tin Number</td>
                                                    <td>@if(!empty($emp->tin_number)) {{$emp->tin_number}} @else N/A @endif  </td>
                                                </tr>
                                                <tr>
                                                    <td>Emergency Contact Number</td>
                                                    <td>{{$emp->emergency_contact}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Relationship</td>
                                                    <td>{{$emp->relationship}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Gender</td>
                                                    <td>{{$emp->gender}}</td>
                                                </tr>

                                                <tr>
                                                    <td>Date Of Birth</td>
                                                    <td>{{$emp->date_of_birth}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Marital Status</td>
                                                    <td>{{$emp->m_status}}</td>
                                                </tr>
                                                <tr>
                                                    <td >Father Name</td>
                                                    <td >{{$emp->father_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Mother Name</td>
                                                    <td>@if(!empty($emp->mother_name)) {{$emp->mother_name}} @else N/A @endif </td>
                                                </tr> 
                                                @if(!empty($emp->fees))
                                                 <tr>
                                                    <td>Fees</td>
                                                    <td>{{$emp->fees}}</td>
                                                </tr> 
                                                @endif
                                           </tbody>
                                        </table>
                                    </div> 
                                </div> 
                                <div class="tshadow mb25 bozero">   
                                    <h3 class="pagetitleh2">Address </h6>
                                    <div class="table-responsive around10 pt0">  
                                        <table class="table table-bordered table-hover table-sm">
                                            <tbody>
                                                <tr>
                                                    <td >Current Address</td>
                                                    <td class="text-right">{{$emp->current_address}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Permanent Address</td>
                                                    <td class="text-right">{{$emp->permanent_address}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                    
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-71">
                            <div class="row">
                                <div class="col-md-5 col-sm-6">
                                    <div class="staffprofile">
                                        <h5>Total Net Salary Paid</h5>
                                        <h4>{{$sum}}</h4> 
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-6">
                                    <div class="staffprofile">
                                        <h5>Salary</h5>
                                        <h4>{{$emp->salary}}</h4> 
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive around10 pt0">
                                <table class="table table-hover table-sm table-bordered  tmb0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tax %</th>
                                            <th>Tax</th>
                                            <th>Net Salary</th>
                                            <th>Deduction</th>
                                            <th>Deduction Description</th>
                                            <th>Year & Month</th>
                                            <th>Issue Date</th>
                               
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter=1; @endphp
                                        @foreach ($pay as $row)
                                        <tr>
                                            <td>{{$counter++}}</td>
                                            <td>{{$row->tax_precentage.'%'}}</td>
                                            <td>{{$row->tax_amount}}</td>
                                            <td>{{$row->net_salary}}</td>
                                            <td>@if(empty($row->deduction)){{'N/A'}} @else{{$row->deduction}} @endif</td>
                                            <td>@if(empty($row->deduction_description)) {{'N/A'}} @else {{$row->deduction_description}} @endif</td>
                                            <td>{{$row->month_year}}</td>
                                            <td>{{$row->created_at}}</td>
                                      
                                        
                                        </tr>     
                                        @endforeach
                                       
                                    </tbody>
                                </table>
                                {{$pay->links()}}
                            </div> 
                        </div>
                        <div class="tab-pane" id="tab-81">
                            <div class="row">
                                                                              
                                <div class="col-md-5 col-sm-6">
                                    <div class="staffprofile">
                                        <h5>CV</h5>
                                        @if(!empty($emp->contract_file))
                                        <a href="{{url('employees/doc/download')}}/{{$emp->emp_id}}/cv_file" class="btn btn-default btn-xs" data-toggle="tooltip" title="Download">
                                            <i class="fa fa-download"></i></a>
                                        @else Empty
                                        @endif

                                    </div>
                                </div><!--./col-md-3-->
                                                                     
                                <div class="col-md-5 col-sm-6">
                                    <div class="staffprofile">
                                        <h5>Contract </h5>
                                        @if(!empty($emp->cv_file))
                                        <a href="{{url('employees/doc/download')}}/{{$emp->emp_id}}/contract_file" class="btn btn-default btn-xs" data-toggle="tooltip" title="Download">
                                            <i class="fa fa-download"></i></a>
                                        </a> 
                                        @else Empty
                                        @endif

                                    </div>
                                </div><!--./col-md-3-->
                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- COL-END -->
</div>
</div>
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('employees') }}">Employees</a></li>
    <li class="breadcrumb-item active" aria-current="page">Profile</li>
@endsection