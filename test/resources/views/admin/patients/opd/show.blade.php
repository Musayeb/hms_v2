@extends('layouts.admin')
@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />

@endsection
@section('title') OPD information @endsection
@section('content')
<div class="row">
    <div class="col-lg-3 col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="wideget-user text-center p-3">
                    <div class="wideget-user-desc pt-5">
                        <div class="wideget-user-img">
                         <i class="fa fa-user-plus fa-5x"></i>
                        </div>
                        <div class="user-wrap">
                            <h3 class="pro-user-username text-dark">{{$opd->o_f_name.' '.$opd->o_l_name}}</h3>
                            <h6 class="text-muted mb-2">Patient ID:{{'OPD-'.$opd->patient_id}}</h6>                    
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
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item listnoback">
                        <b>Patient Id</b> <a class="pull-right text-aqua">{{'OPD-'.$opd->patient_id}}</a>
                    </li>
                    <li class="list-group-item listnoback">
                        <b>Gender</b> <a class="pull-right text-aqua">{{$opd->gender}}</a>
                    </li>

                    <li class="list-group-item listnoback">
                        <b>Phone</b> <a class="pull-right text-aqua">{{$opd->phone}}</a>
                    </li>

                    <li class="list-group-item listnoback">
                        <b>Age</b> <a class="pull-right text-aqua">{{$opd->age}}</a>
                    </li>
                    <li class="list-group-item listnoback">
                        <b>Registred Date</b> <a class="pull-right text-aqua" data-toggle="tooltip" 
                        title="{{Carbon\Carbon::parse($opd->created_at)->diffForHumans()  }}" >{{date('Y-m-d h:m A', strtotime($opd->created_at)) }} </a>
                    </li>
                    <li class="list-group-item listnoback">
                        <b>Referred By</b> <a class="pull-right text-aqua">@if(empty($opd->referred)) {{'N/A'}} @else  {{$opd->referred}} @endif</a>
                    </li>
                </ul>
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
                                <li><a href="#tab-70" class="active show" data-toggle="tab">Visits</a></li>
                                <li><a href="#tab-71" data-toggle="tab" class="">Test</a></li>
                                <li><a href="#tab-72" data-toggle="tab" class="">Finances</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="border-0">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab-70">
                            <div class="tab-pane active" id="activity">
                                <a  class="pull-right btn btn-primary d-inline mr-1 mb-3" data-toggle="modal" data-target="#revisit"><i class="fa fa-plus text-white"></i>&nbsp; Revisit</a>
                                <div class="mt-5 table-responsive table1">
                                    <table class="table table-striped table-bordered  table-sm text-nowrap w-100 dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Department</th>
                                                <th>Doctor</th>
                                                <th>Fees</th>
                                                <th>status</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter=1; @endphp
                                            @foreach ($visit as $row)
                                                <tr id="row{{ $row->visit_id }}">
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $row->department_name }}</td>
                                                    <td>{{ $row->f_name . ' ' . $row->l_name }}</td>   
                                                    <td>{{ $row->fees }}</td>
                                                    <td><span class="badge badge-success">{{ $row->status }}</span></td>
                                                    <td>{{ $row->email }}</td>
                                                    <td>{{ $row->date}}</td>
                        
                                                    <td>
                                                        <a 
                                                            class="btn btn-success btn-sm text-white mr-2 print_slip" onclick="print('visit',{{$row->visit_id}})">Print</a>

                                                        <a data-toggle="modal" data-target="#editrevisit" data-id="{{ $row->visit_id }}"
                                                            class="btn btn-info btn-sm text-white mr-2 visitedit">Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-71">
                            <a  class="pull-right btn btn-primary d-inline mr-1 mb-3" data-toggle="modal" data-target="#test"><i class="fa fa-plus text-white"></i>&nbsp; Test</a>
                           
                            <div class="mt-5 table-responsive table2">
                                <table class="table table-striped table-bordered table-mid  table-sm text-nowrap w-100 dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Test Type</th>
                                            <th>Department</th>
                                            <th>Fees</th>
                                            <th>status</th>
                                            <th>Author</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter=1; @endphp
                                        @foreach ($test as $rows)
                                            <tr id="row">
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $rows->test_type }}</td>
                                                <td>{{ $rows->department_name }}</td>
                                                <td>{{ $rows->fees }}</td>
                                                <td><span class="badge badge-success">{{ $rows->status }}</span></td>
                                                <td>{{ $rows->email }}</td>
                                                <td>{{ $rows->date }}</td>
                                                <td> 
                                                  
                                                        <a  class="btn btn-success btn-sm text-white mr-2 print_slip" onclick="print('test',{{$rows->patient_test_id}})">Print</a>
                                                    <a data-toggle="modal" data-target="#testEdit" data-id="{{$rows->patient_test_id}}"
                                                        class="btn btn-info btn-sm text-white mr-2 testEditbtn">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                    
                                    </tbody>
                                </table>
                            </div>
                   
                        </div>
                        <div class="tab-pane" id="tab-72">
                            <div class="mt-5 table-responsive table3">
                                <table class="table table-striped table-bordered  table-sm text-nowrap w-100 dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Payment Type</th>
                                            <th>Total</th>
                                            <th>status</th>
                                            <th>Create Date</th>
                                            <th>Update Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter=1; @endphp
                                        @foreach ($finance as $item)
                                            <tr id="row{{ $item[0]->f_id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $item[0]->payment_type }}</td>
                                                <td>{{ $item[0]->total }}</td>
                                                <td><span class="badge badge-success">{{ $item[0]->status }}</span></td>
                                                <td>{{ $item[0]->created_at }}</td>
                                                <td>{{ $item[0]->updated_at }}</td>
                                                <td>                               
                                                  @if($item[0]->status!="Fees Returned") <a href="{{url('opd/return_fees')}}/{{$item[0]->f_id}}/{{$item[0]->payment_type}}" class="btn btn-success btn-sm text-white mr-2">Return Fees</a> @endif
                                                </td>
                                            </tr>
                                        @endforeach
                    
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
<div id="revisit" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Revist OPD Patient</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
        
                <form method="post" class="p-3 createform1">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>
                 
                    <div class="form-group">
                        <label>Departments</label>
                        <select name="department" class="form-control deps">
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($dep as $row)
                                <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doctor</label>
                        <select name="doctor"  class="form-control pos">
                            <option value="" selected disabled>Select Doctor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doctor Fees</label>
                        <input name="doctor_fees"  readonly class="form-control fees">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input name="description"  class="form-control">
                        <input name="opd_id" type="hidden" class="form-control" value="{{$id}}">
                    </div>
            
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Revisit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
<div id="editrevisit" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Edit Revist OPD Patient</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
        
                <form method="post" class="p-3 EditVisit">
                    <div class="alert alert3 alert-danger">
                        <ul id="error"></ul>
                    </div>
                 
                    <div class="form-group">
                        <label>Departments</label>
                        <select name="department" class="form-control deps" id="dep1">
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($dep as $row)
                                <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doctor</label>
                        <select name="doctor"  class="form-control pos" id="doc1">
                            <option value="" selected disabled>Select Doctor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Docter Fees</label>
                        <input name="doctor_fees"  readonly class="form-control fees" id="fee1">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input name="description"  class="form-control" id="des1">
                        <input name="opd_id" type="hidden" class="form-control" value="{{$id}}" >
                        <input name="visit_id" type="hidden" class="form-control" id="visit_id" >                    
                    </div>
            
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit visit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
<div id="test" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Test OPD Patient</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
        
                <form method="post" class="p-3 test">
                    <div class="alert alert2 alert-danger">
                        <ul id="error"></ul>
                    </div>
                 
                    <div class="form-group">
                        <label>Departments</label>
                        <select name="department" class="form-control depss">
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($dep as $row)
                                <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Test</label>
                        <select  class="form-control test_name" name="test_type">
                            <option value="" selected disabled>Select Test</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Test Fees</label>
                        <input readonly class="test_fees form-control" name="test_fees">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input name="description"  class="form-control">
                        <input name="opd_id" type="hidden" class="form-control" value="{{$id}}">
                    </div>
            
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Test</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
<div id="testEdit" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Edit Test OPD Patient</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
        
                <form method="post" class="p-3 test_edit">
                    <div class="alert alert4 alert-danger">
                        <ul id="error"></ul>
                    </div>
                 
                    <div class="form-group">
                        <label>Departments</label>
                        <select name="department" class="form-control depss" id="dep_test">
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($dep as $row)
                                <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Test</label>
                        <select  class="form-control test_name "  name="test_type">
                            <option value="" selected disabled>Select Test</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Test Fees</label>
                        <input   readonly class="form-control test_fees" id="test_fees" name="test_fees">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input name="description" id="test_description" class="form-control">
                        <input name="opd_id" type="hidden" class="form-control" value="{{$id}}">
                        <input name="test_id" type="hidden" class="form-control" id="patient_test">

                    </div>
            
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit Test</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
   {{-- Print Modal --}}
   <div id="print_modal" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Print</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20" id="divtoprint">
                <img src="{{ url('public/payslip.png') }}" width="100%" alt="">
                <h5>OPD Bill</h5>
                    <div class="printData">
                    </div>
                    <div class="Qrcode" style="text-align: center;margin-top:12px">
                        <div id="qrcode">
                        </div> 
                    <p>Powerd By: PMS Medical Complex</p>
                </div>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>

{{-- Print Modal --}}

@endsection
@section('directory')
<li class="breadcrumb-item active" aria-current="page"><a href="{{url('opd')}}">OPD</a></li>
<li class="breadcrumb-item active" aria-current="page">OPD Profile</li>
@endsection
@section('jquery')
<script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script>
    // $('.table').DataTable({"bFilter":false,"bLengthChange": false, "paging": false});
    $('.alert').hide();
</script>
<script>
 $('.deps').change(function() {
            id = ($(this).val());
            url = '{{ url("appoinments_get_position") }}/' + id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        Hdata = '<option value="" selected disabled>Select Doctor</option>';
                        for (var i = 0; i < data.length; i++) {
                            Hdata += '<option value="' + data[i].emp_id + '">' + data[i]
                                .f_name + ' ' + data[i]
                                .l_name + '</option>';
                            $(".pos").html(Hdata);
                        }
                    } else {
                        $(".pos").html(
                            '<option value="" selected disabled>No Record Found</option>');
                    }
                },
                error: function() {}
            })

 });

 $('.depss').change(function() {
    id = ($(this).val());
    url = '{{ url("get_test_via_department") }}/' + id;
    var Hdata = "";
    $.ajax({
        type: 'get',
        url: url,
        success: function(data) {
            if (data != '') {
                Hdata = '<option value="" selected disabled>Select Test</option>';
                for (var i = 0; i < data.length; i++) {
                    Hdata += '<option value="' + data[i].test_id + '">'+data[i].test_type+ '</option>';
                    $("#test_name").html(Hdata);
                    $(".test_name").html(Hdata);                    
                }
            } else {
                $("#test_name").html('<option value="" selected disabled>No Record Found</option>');
                $(".test_name").html('<option value="" selected disabled>No Record Found</option>');
            }
        },
        error: function() {}
    })

 });
 $('.test_name').change(function() {
        id = ($(this).val());
        url = '{{ url("get_test_fee") }}/' + id;
        var Hdata = "";
        $.ajax({
            type: 'get',
            url: url,
            success: function(data) {
                if (data != '') {
                    $(".test_fees").val(data[0].fees);  
                } else {
                    $(".test_fees").val('Fees is Empty');
                }
            },
            error: function() {}
        })

 });
 
    $('.pos').change(function() {
            id = ($(this).val());
            url = '{{ url("getDocterFees") }}/' + id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        $(".fees").val(data[0].fees);  
                    } else {
                        $(".fees").val('Fees is Empty');
                    }
                },
                error: function() {}
            })
    });
    
    $(".createform1").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
            $.ajax({
                url: '{{ url("createRevisitRecord") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                $(".alert1").css('display', 'none');
                $('.table1').load(document.URL + ' .table1');
                $('.table2').load(document.URL + ' .table2');
                $('.table3').load(document.URL + ' .table3');

                $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
                 
                    $('#revisit').modal('hide');
                    $('.createform1')[0].reset();
                    print('visit',data.id);
                },
                error: function(data) {
                    $(".alert").find("ul").html('');
                        $(".alert1").css('display', 'block');
                    $.each(data.responseJSON.errors, function(key, value) {
                        $(".alert1").find("ul").append('<li>' + value + '</li>');
                    });
                    $('.modal').animate({
                        scrollTop: 0
                    }, '500');

                },
                cache: false,
                contentType: false,
                processData: false
            });
    });
        
    $(".test").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
        $.ajax({
            url: '{{ url("createtestRecord") }}',
            type: 'post',
            data: formData,
            success: function(data) {
                $(".alert2").css('display', 'none');
                $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
                $('.table3 ').load(document.URL + ' .table3');
                $('.table2').load(document.URL + ' .table2');
                $('#test').modal('hide');
                $('.test')[0].reset();
                print('test',data.id);

            
            },
            error: function(data) {
                $(".alert").find("ul").html('');
                    $(".alert2").css('display', 'block');
                $.each(data.responseJSON.errors, function(key, value) {
                    $(".alert2").find("ul").append('<li>' + value + '</li>');
                });
                $('.modal').animate({
                    scrollTop: 0
                }, '500');

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

  $('body').on('click', '.visitedit', function() {
        id = $(this).attr('data-id');
        url = '{{url("opdEditvisit")}}' + '/' + id;
        var Hdata = "";
        $.get(url, function(data) {
            if (data.emp != '') {
                Hdata = '<option value="" selected disabled>Select Docter</option>';
                for (let i = 0; i < data.emp.length; i++) {
                    Hdata += '<option value="' +data.emp[i].emp_id + '">' + data.emp[i]
                        .f_name + ' ' + data.emp[i]
                        .l_name + '</option>';
                    $("#doc1").html(Hdata);
                }
            }

            if (data.visit != '') {
                $('#dep1').val(data.visit[0]['dep_id']);
                $('#doc1').val(data.visit[0]['emp_id']);
                $('#fee1').val(data.visit[0]['fees']);
                $('#des1').val(data.visit[0]['description']);       
                $('#visit_id').val(data.visit[0]['visit_id']);            

            }
        });
 });
 $('body').on('click', '.testEditbtn', function() {
        id = $(this).attr('data-id');
        url = '{{url("opdEdittest")}}' + '/' + id;
        var Hdata = "";
        $.get(url, function(data) {
            if (data.tests != '') {
                Hdata = '<option value="" selected disabled>Select Test</option>';
                for (let i = 0; i < data.tests.length; i++) {
                    Hdata += '<option value="' +data.tests[i].test_id + '">' + data.tests[i].test_type + '</option>';
                    $(".test_name").html(Hdata);
                }
            }
            if (data.p_test != '') {
                $('#dep_test').val(data.p_test[0]['dep_id']);
                $('.test_name').val(data.p_test[0]['test_id']);
                $('.test_fees').val(data.p_test[0]['fees']);
                $('#test_description').val(data.p_test[0]['description']);
                $('#patient_test').val(data.p_test[0]['patient_test_id']);                
            }
        });
 });
 
 $(".EditVisit").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
        $.ajax({
            url: '{{ url("Updateopdvist") }}',
            type: 'post',
            data: formData,
            success: function(data) {
                $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
                $(".alert").css('display', 'none');
                $('.table1').load(document.URL + ' .table1');
                $('.table3').load(document.URL + ' .table3');
                $('#editrevisit').modal('hide');
                $('#EditVisit')[0].reset();
                print('visit',data.id);
       
            },
            error: function(data) {
                   $(".alert4").find("ul").html('');
                    $(".alert4").css('display', 'block');
                $.each(data.responseJSON.errors, function(key, value) {
                    $(".alert4").find("ul").append('<li>' + value + '</li>');
                });
                $('.modal').animate({
                    scrollTop: 0
                }, '500');

            },
            cache: false,
            contentType: false,
            processData: false
        });
 });
 
    $(".test_edit").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
        $.ajax({
            url: '{{ url("Updateopdtest") }}',
            type: 'post',
            data: formData,
            success: function(data) {
                $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
                $(".alert4").css('display', 'none');
                $('.table3').load(document.URL + ' .table3');
                $('.table2').load(document.URL + ' .table2');
                $('#testEdit').modal('hide');
                $('.test_edit')[0].reset();
                print('test',data.id);
            },
            error: function(data) {
                   $(".alert4").find("ul").html('');
                    $(".alert4").css('display', 'block');
                $.each(data.responseJSON.errors, function(key, value) {
                    $(".alert4").find("ul").append('<li>' + value + '</li>');
                });
                $('.modal').animate({
                    scrollTop: 0
                }, '500');

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>

<script>
        function print(type,id){
                  Swal.fire({
                        title: 'Success!',
                        text: "Do You Want To Print ?",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#218838',
                        cancelButtonColor: '#4b4b4b',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                            type:'GET',
                            url:'{{url("opd/bill/print")}}/'+type+'/'+id,
                            success:function(data){ 
                            $('.printData').html(data.html);
                            $('#qrcode').html(data.qr); 

                            setTimeout(function(){ printDiv(); },500);

                            },
                            error:function(error){
                                console.log('Server Error');
                            }
                            });
                            
                    }
                    });
   
        };
    
       
        function printDiv() {
            var divToPrint = document.getElementById('divtoprint');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">' +
                divToPrint.innerHTML + '</body></html>');
            newWin.document.close();
            setTimeout(function() {
                newWin.close();
            }, 10);
        }
  </script>
  @if(session()->has('notif'))
  <script>
        $.growl.notice({
          message: "{{session()->get('notif')}}",
          title: 'Success !',
          position: {
              from: "top",
              align: "left"
          },
      });
    
  </script>       
  @endif
@endsection