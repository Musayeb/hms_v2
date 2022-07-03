@extends('layouts.admin')   
@section('title') Patients Information @endsection

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
                            <h6 class="text-muted mb-2">Patient ID:{{'P-'.$emp->patient_idetify_number}}</h6>                    
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
                            <i class="fe fe-map-pin" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Current Address</h6>
                            <span class="d-block">{{$emp->address}}</span>
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
                                <li><a href="#tab-81" data-toggle="tab" class="">Finance</a></li>
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
                                                    <td>Patient ID</td>
                                                    <td>{{'P-'.$emp->patient_idetify_number}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Full Name</td>
                                                    <td>{{$emp->f_name.' '.$emp->l_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Age</td>
                                                    <td>{{$emp->age}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Gender</td>
                                                    <td>{{$emp->gender}}</td>
                                                </tr>

                                                <tr>
                                                    <td>Blood Group</td>
                                                    <td>{{$emp->blood_g}}</td>
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
                                                    <td>Date Of Birth</td>
                                                    <td>{{$emp->dob}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Marital Status</td>
                                                    <td>{{$emp->marital_status}}</td>
                                                </tr>
                                                <tr>
                                                    <td >Allergies</td>
                                                    <td >{{$emp->allergies}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Remark</td>
                                                    <td>{{$emp->remark}}</td>
                                                </tr>   
                                                <tr>
                                                    <td>Referred By</td>
                                                    <td>@if(empty($emp->referred)) {{'N/A'}} @else {{$emp->referred}} @endif</td>
                                                </tr>   
                                                <tr>
                                                    <td>Registred Date</td>
                                                    <td>{{$emp->created_at}}</td>
                                                </tr>                                              
                                           </tbody>
                                        </table>
                                    </div> 
                                </div> 
              
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-81">
                            <div class="mt-5 table-responsive">
                                <table class="table table-sm table-hover table-bordered ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Payment Type</th>
                                            <th>Total</th>
                                            <th>status</th>
                                            <th>Create Date</th>
                                            <th>Update Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter=1;  @endphp
                                        @if (!empty($finance))
                                            
                                        @foreach ($finance as $item)
                                        @foreach ($item as $ro )
                                        <tr id="row{{ $ro->f_id }}">
                                            <td>{{ $counter++ }}</td>
                                           <td>{{ $ro->payment_type }}</td>

                                            <td>{{ $ro->total }}</td>
                                            <td>{{ $ro->status }}</td>
                                            <td>{{ $ro->created_at }}</td>
                                            <td>{{ $ro->updated_at }}</td> 
                                        </tr> 
                                        @endforeach
                                     
                                        @endforeach
                                        @endif
                                       
                    
                                    </tbody>
                                </table>
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
    <li class="breadcrumb-item active" aria-current="page">Patients</li>
    <li class="breadcrumb-item active" aria-current="page">Patient Info</li>

@endsection