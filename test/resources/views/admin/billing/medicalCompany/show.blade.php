@extends('layouts.admin')   

@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
<link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
<style>
    a{
        cursor: pointer;
    }
    table.dataTable.table-sm>thead>tr>th{
        font-size: 11px;
    }
</style>
@endsection
@section('title') Medical Company Bill Information @endsection
@section('direct_btn')
<a href="{{url('medical_company_bill')}}"><button class="btn btn-outline-primary">Today's Bills</button>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-3 col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="wideget-user text-center p-3">
                    <div class="wideget-user-desc pt-5">
                        <div class="wideget-user-img">
                            <i class="fa fa-building fa-5x"></i>
                        </div>
                        <div class="user-wrap">
                            <h3 class="pro-user-username text-dark">{{$bill->company_name}}</h3>
                            <small class="">{{$bill->description}}</small>

                                       
                        </div>
                    </div>
                
                </div>
            </div>
        
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bill Info</h3>
            </div>
            <div class="card-body">
                <div class="media-list">
                    <div class="media mt-1 pb-2">
                        <div class="mediaicon">
                            <i class="fe fe-user" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Company Name</h6>
                            <span class="d-block">{{$bill->company_name}}</span>
                        </div>
                    </div>
                    <div class="media mt-1 pb-2">
                        <div class="mediaicon">
                            <i class="fe fe-clock" aria-hidden="true"></i>
                        </div>
                        <div class="media-body ml-5 mt-1">
                            <h6 class="mediafont text-dark mb-1">Registred Date</h6>
                            <span class="d-block">{{$bill->date}}</span>
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
                                <li class=""><a href="#tab-61" class="active show" data-toggle="tab">Finance</a></li>
                 
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
                                  <div class="all">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="staffprofile">
                                                    <h5>Total Amount</h5>
                                                    <h4>@if(empty($total[0]->total)) {{0}} @else {{$total[0]->total}} @endif</h4> 
                                                    <div class="icon mt12font40">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="staffprofile">
                                                    <h5>Due Amount</h5>
                                                    <h4>{{$total[0]->total-$sum}}</h4> 
                                                    <div class="icon mt12font40">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="staffprofile">
                                                    <h5>Paid Amount</h5>
                                                    <h4>{{$sum}}</h4> 
                                                    <div class="icon mt12font40">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </div>
                                            </div>
                            
                                        </div> 
                                    </div>
                                                        
                                </div>    
                    
                            </div>
                        </div>
 
                    </div>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <h6 class="card-title">Receive Company Bill</h6>
            <div class="btn-list ">
                @if (!empty(Helper::getpermission("_addmedicalcompanybillAmount")))
                <a href="javascript:viod();" data-toggle="modal" data-target="#create1" class="pull-right btn btn-primary d-inline">Add Payment</a>
                @endif
            </div>
                <div class="tab-pane" id="tab-71">

                    <div class="table-responsive pt0" >
                        <table class="table table-hover table-sm table-bordered around1022 " id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Receipt Number</th>
                                    <th>Total Amount</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Issue Date</th>
                                    <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php $counter=1; @endphp
                                @foreach ($bills1 as $row2)
                                <tr id="row{{$row2->id}}">
                                    <td>{{$counter++}}</td>
                                    <td>{{$row2->receipt_number}}</td>
                                    <td>{{$row2->amount}}</td>
                                    <td>{{$row2->email}}</td>
                                    <td>{{$row2->date}}</td>
                                    <td>{{date('Y-m-d h:i:s a', strtotime($row2->created_at)) }}</td>
                                    <td>
                                    @if (!empty(Helper::getpermission("_deletemedicalcompanybillAmount")))
                                    <a data-delete="{{ $row2->id }}"
                                        class="delete1"><span class="badge badge-danger"><i class="ti-trash"></i></span></a>
                                    @endif
                                    @if (!empty(Helper::getpermission("_editmedicalcompanybillAmount")))
                                        <a data-toggle="modal" data-target="#editcreate1" data-id="{{ $row2->id}}"
                                        class="edit_bills_1"><span class="badge badge-info"><i class="ti-pencil"></i></span></a>
                                    @endif        
                                </tr>     
                                @endforeach
                            
                            </tbody>
                        </table>
                    </div> 
                </div>

        </div>
        <div class="card p-4">
                <h6 class="card-title">Create Company Payment</h6>
                <div class="btn-list ">
                    @if (!empty(Helper::getpermission("_addmedicalcompanybillvalue")))
                    <a href="javascript:viod();" data-toggle="modal" data-target="#create" class="pull-right btn btn-primary d-inline">Add Payment</a>
                    @endif
                </div>
                        <div class="table-responsive around10 pt0">
                            <table class="table table-hover table-sm table-bordered table-main  tmb0" id="example1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bill Number</th>
                                        <th>Receipt Number</th>
                                        <th>Receiver Name</th>
                                        <th>Paid Amount</th>
                                        <th>Author</th>
                                        <th>Issue Date</th>
                                        <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php $counter=1; @endphp
                                    @foreach ($bills as $row)
                                    <tr id="row{{$row->company_bill_id}}">
                                        <td>{{$counter++}}</td>
                                        <td>{{$row->bill_number}}</td>
                                        <td>{{$row->receipt_number}}</td>
                                        <td>{{$row->receiver_name}}</td>
                                        <td>{{$row->paid_amount}}</td>
                                        <td>{{$row->email}}</td>
                                        <td>{{date('Y-m-d h:i:s a', strtotime($row->created_at)) }}</td>
                                        <td>
                                        <a data-toggle="modal" data-target="#print_modal"
                                        class=" print_slip " 

                                        data-id="{{$row->company_bill_id}}"
                                        > <span class="badge badge-success"><i class="fa fa-print"></i></span></a>

                                        @if (!empty(Helper::getpermission("_deletemedicalcompanybillvalue")))
                                        <a data-delete="{{ $row->company_bill_id }}"
                                            class=" delete"> <span class="badge badge-danger"><i class="ti-trash"></i></span></a>
                                        @endif
                                        @if (!empty(Helper::getpermission("_editmedicalcompanybillvalue")))
                                            <a data-toggle="modal" data-target="#edit_modal" data-id="{{ $row->company_bill_id}}"
                                            class=" edit_bills"> <span class="badge badge-info"><i class="ti-pencil"></i></span></a>
                                        @endif        
                                    </tr>     
                                    @endforeach
                                
                                </tbody>
                            </table>
                        </div>  
                {{--  --}}
        </div>
    </div><!-- COL-END -->
</div>
</div>
{{-- Add User Modal  --}}
<div id="create" class="modal fade">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Add Medical Company Payment</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert1 alert-danger">
                    <ul id="error"></ul>
                </div>
                <form method="post" id="createform">
                    <div class="form-group" id="refresh1212">
                        <label>Bill Number</label>
                        <input name="bill_number" readonly type="text" class="form-control" value="<?php echo Helper::getcompanyBillNo() ?>" autocomplete="off" >
                    </div>
                    <div class="form-group mt-2">
                        <label>Receiver Name</label>
                        <input name="receiver_name" type="text" class="form-control" placeholder="Receiver Name"  autocomplete="off">
                    </div>

                    <div class="form-group mt-2">
                        <label>Pay Amount</label>
                        <input name="pay_amount" min="1" type="number" class="form-control" placeholder="Pay Amount"  autocomplete="off">
                    </div>
                    <input type="hidden" name="company_bill" value="{{$id}}">
                    <div class="form-group mt-2">
                        <label>Receipt Number</label>
                        <input name="receipt_number" type="number" class="form-control" placeholder="Receipt Number"  autocomplete="off">
                    </div>
                    
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">ADD</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>{{--End Add User Modal  --}}
<div id="create1" class="modal fade">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Medical Company Payment Create</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert21 alert-danger">
                    <ul id="error"></ul>
                </div>
                <form method="post" id="createform1">
          

                    <div class="form-group mt-2">
                        <label>Total Amount</label>
                        <input name="total_amount" min="1" type="number" class="form-control" placeholder="Total Amount"  autocomplete="off">
                    </div>

                    <input type="hidden" name="company_bill" value="{{$id}}">

                    <div class="form-group mt-2">
                        <label>Receipt Number</label>
                        <input name="receipt_number" type="number" class="form-control" placeholder="Receipt Number"  autocomplete="off">
                    </div>
                    <div class="form-group mt-2">
                        <label>Date</label>
                        <input name="date" type="date" class="form-control"  autocomplete="off">
                    </div>
                    
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">ADD</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>{{--End Add User Modal  --}}
<div id="editcreate1" class="modal fade">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Medical Company Payment Create</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert22 alert-danger">
                    <ul id="error"></ul>
                </div>
                <form method="post" id="editcreateform1">
                    <div class="form-group mt-2">
                        <label>Total Amount</label>
                        <input name="total_amount" min="1" type="number" class="form-control" placeholder="Total Amount"  autocomplete="off" id="total_amount">
                    </div>
                    <div class="form-group mt-2">
                        <label>Receipt Number</label>
                        <input name="receipt_number" type="number" class="form-control" placeholder="Receipt Number"  autocomplete="off" id="receipt_number1">
                    </div>
                    <div class="form-group mt-2">
                        <label>Date</label>
                        <input name="date" type="date" class="form-control"  autocomplete="off" id="date">
                        <input name="bill_id" type="hidden" class="form-control"  autocomplete="off" id="bill_id">

                    </div>
                    
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">ADD</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>{{--End Add User Modal  --}}


{{-- Add User Modal  --}}
<div id="edit_modal" class="modal fade">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Edit Medical Company Payment</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert1 alert-danger">
                    <ul id="error"></ul>
                </div>
                <form method="post" id="updateform">
                    <div class="form-group" id="refresh1212">
                        <label>Bill Number</label>
                        <input name="bill_number" readonly type="text" class="form-control" value="" autocomplete="off" id="bill_num">
                    </div>
                    <div class="form-group mt-2">
                        <label>Receiver Name</label>
                        <input name="receiver_name" type="text" class="form-control" placeholder="Receiver Name" id="receiver_name"  autocomplete="off">
                    </div>

                    <div class="form-group mt-2">
                        <label>Pay Amount</label>
                        <input name="pay_amount" min="1" type="number" class="form-control" placeholder="Pay Amount"  id="pay_amount" autocomplete="off">
                    </div>
                    <input type="hidden" name="company_bill" id="bill_id1">
                    <div class="form-group mt-2">
                        <label>Receipt Number</label>
                        <input name="receipt_number" type="number" class="form-control" id="receipt_number" placeholder="Receipt Number"  autocomplete="off">
                    </div>
                    
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Edit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>{{--End Add User Modal  --}}
<div id="print_modal" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Print Slip</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20" id="divtoprint">
                <img src="{{ url('public/payslip.png') }}" width="100%" alt="">
                <h5>Medical Company Bill</h5>
                <div class="print_bill12">
                    
                </div>


            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>

@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('medical_company_bill') }}">Medical Company Billing</a></li>
    <li class="breadcrumb-item active" aria-current="page">Profile</li>
@endsection

@section('jquery')
<script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js')}}"></script>
<script src="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js')}}"></script>

<script>$('.alert').hide();
    $('.table').DataTable({"bFilter":false,"bLengthChange": false, "paging": false});
</script>

<script >
    $("#createform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('medical_company_bill_info')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                $(".alert").css('display','none');
                $('.table-main').load(document.URL +  ' .table-main');
                $('.refresh1212').load(document.URL +  ' .refresh1212');
                $('.all').load(document.URL +  ' .all');               
                $('#tab-61').load(document.URL +  ' #tab-61');   
                $('#create').modal('hide');
                $('#createform')[0].reset();
                    return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
            },
            error:function(data){
                $(".alert").find("ul").html('');
                $(".alert").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>

<script >
$("#createform1").submit(function(e) {
    e.preventDefault();   
    var formData = new FormData(this);
    $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
    $.ajax({
        url: "{{url('medical_company_bill_info1')}}",
        type: 'POST',
        data: formData,
        success: function (data) {
            $(".alert").css('display','none');
            $('.around1022').load(document.URL +  ' .around1022');   
            $('#tab-61').load(document.URL +  ' #tab-61');              
            $('#create1').modal('hide');
            $('#createform1')[0].reset();
                return $.growl.notice({
                message: data.success,
                title: 'Success !',
            });
        },
        error:function(data){
            $(".alert21").find("ul").html('');
            $(".alert21").css('display','block');
        $.each( data.responseJSON.errors, function( key, value ) {
                $(".alert21").find("ul").append('<li>'+value+'</li>');
            });     

        },
        cache: false,
        contentType: false,
        processData: false
    });
});
</script>
<script>
    $('body').on("click",'.print_slip',function() {
        $.ajax({
            url:'{{ url("medical_company_bill_info/get")}}/'+$(this).attr('data-id'),
            type: 'get',
            success: function (data) {
                $('.print_bill12').html(data);
                setTimeout(function(){ printDiv(); },500);
            },
        });    
  });

  function printDiv() {
        var divToPrint=document.getElementById('divtoprint');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head><style>@media  print { body{hight: 100%;} th, td { border: 0.5px solid black; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        $('.modal').modal('hide');
        setTimeout(function(){newWin.close();},10);
    }
</script>

<script>
    $('body').on("click",'.edit_bills',function() {
            var id=$(this).attr('data-id');
        $.ajax({
            url:'{{ url("medical_company_bill_info")}}/'+id+'/'+'edit',
            type: 'get',
            success: function (data) {
                $('#bill_num').val(data[0].bill_number);
                $('#receiver_name').val(data[0].receiver_name);
                $('#pay_amount').val(data[0].paid_amount);
                $('#receipt_number').val(data[0].receipt_number);
                $('#bill_id1').val(data[0].company_bill_id);
            },
        });      
  });
     $("#updateform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("medical_company_bill_info_update")}}',
            type: 'post',
            data:formData,
            success: function (data) {
                $(".alert").css('display','none');
                $('.table-main').load(document.URL +  ' .table-main');
                $('.refresh1212').load(document.URL +  ' .refresh1212');
                $('.all').load(document.URL +  ' .all');               
                $('#tab-61').load(document.URL +  ' #tab-61');   
            
                $('#edit_modal').modal('hide');
                $('#updateform')[0].reset();
                    return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
            },
            error:function(data){
                $(".alert").find("ul").html('');
                $(".alert").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });      
  });

</script>
<script>
        $('body').on('click','.delete',function(){  
           var id =$(this).attr('data-delete');
        Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.value) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  

            $.ajax({
                    type:'DELETE',
                    url:'{{url("medical_company_bill_info")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your record has been deleted.',
                      'success'
                    )
                    $('#row'+id).hide(1500);
                    },
                    error:function(error){
                    Swal.fire(
                      'Faild!',
                      'Server Error',
                      'error'
                    )
                    }
                });
            }
          })
              
});
</script>

<script>
     $('body').on("click",'.edit_bills_1',function() {
         id=$(this).attr('data-id');
        $.ajax({
            url:'{{ url("medical_company_bill_info1_edit")}}/'+id,
            type: 'get',
            success: function (data) {
                $('#bill_id').val(data.id);
                $('#receipt_number1').val(data.receipt_number);
                $('#total_amount').val(data.amount);
                $('#date').val(data.date);
            },
        }); 
     });

  $("#editcreateform1").submit(function(e) {
    e.preventDefault();   
    var formData = new FormData(this);
    $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
    $.ajax({
        url: "{{url('medical_company_bill_info1update')}}",
        type: 'POST',
        data: formData,
        success: function (data) {
            $(".alert").css('display','none');
            $('.around1022').load(document.URL +  ' .around1022');
            $('#tab-61').load(document.URL +  ' #tab-61');   
            $('#editcreate1').modal('hide');
            $('#editcreateform1')[0].reset();
                return $.growl.notice({
                message: data.success,
                title: 'Success !',
            });
        },
        error:function(data){
            $(".alert22").find("ul").html('');
            $(".alert22").css('display','block');
        $.each( data.responseJSON.errors, function( key, value ) {
                $(".alert22").find("ul").append('<li>'+value+'</li>');
            });     

        },
        cache: false,
        contentType: false,
        processData: false
    });
});
    $('body').on('click','.delete1',function(){  
           var id =$(this).attr('data-delete');
        Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.value) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  

            $.ajax({
                    type:'get',
                    url:'{{url("medical_company_bill_info1delete")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your record has been deleted.',
                      'success'
                    )
                    $('#row'+id).hide(1500);
                    },
                    error:function(error){
                    Swal.fire(
                      'Faild!',
                      'Server Error',
                      'error'
                    )
                    }
                });
            }
          })
              
});
    
</script>
@endsection