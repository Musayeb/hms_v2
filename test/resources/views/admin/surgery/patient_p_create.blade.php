@extends('layouts.admin')
@section('css')
<link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
<link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
<link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />

<style>
    .select2{
        z-index: 1;
    }
</style>
@endsection
@section('title') Patients Operations Create @endsection
@section('directory')
<li class="breadcrumb-item " aria-current="page"><a href="{{url('surgery_registration')}}">Surgery & Delivery</a></li>
<li class="breadcrumb-item active" aria-current="page">Register</li>
@endsection
@section('direct_btn')
<a href="{{url('surgery_registration')}}"><button class="btn btn-outline-primary">Today's Bills</button>
@endsection
@section('content')
    
<form id="form_s" method="post" >
    <div class="card">
        <div class="alert alert-danger"><ul id="error"></ul></div>

        <div class="row pr-5 pl-5 pt-5">
            <div class="col-4">
                <div class="form-group bill_number" >
                    <label>Bill Number</label>
                        <input type="text" name="bill_number"  class="form-control" readonly value="@php $bill=helper::getSurgeryBillNumber()@endphp {{$bill}}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>Patient</label>
                    <select name="patient" class="select2">
                        <option value="" disabled selected>Select Patient</option>
                            @foreach ($patient as $row)
                                <option value="{{ $row->patient_id }}">{{ $row->f_name.' '.$row->l_name.' P-00'.$row->patient_idetify_number }}</option>
                            @endforeach
                    </select>
                </div>
            </div>
         
            <div class="col-4">
                <div class="form-group">
                    <label>Operation Type</label>
                    <select name="type" class="form-control type">
                        <option value="" selected disabled>select type</option>
                        <option value="surgery">Surgery</option>
                        <option value="procedure">Procedure</option>
                        <option value="normal delivery">Normal Delivery</option>
                    </select>
                </div>
            </div>
           
 
        </div>
        <div class="row pl-5 pr-5">
            <div class="col-4">
                <div class="form-group d-none" id="d">
                    <label>Department Name</label>
                    <select name="department" class="form-control deps">
                        <option value=""  selected>Select Department</option>
                        @foreach ($dep as $row)
                            <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group d-none" id="doc">
                    <label>Doctor</label>
                    <select name="docter" class="form-control pos">
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group  d-none" id="surgery">
                    <label>Surgery</label>
                    <select name="surgery" id="surg" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach ($surgery as $s)
                            <option value="{{$s->surgery_id}}">{{$s->surgery_name}}</option>                                
                        @endforeach
                    </select>
                </div>
                <div class="form-group d-none" id="procedure">
                    <label>Procedure</label>
                    <select name="procedure" id="pro" class="form-control">
                        <option value="" selected disabled>Select</option>
                       @foreach ($procedure as $p)
                        <option value="{{$p->procedure_id}}">{{$p->procedure_name}}</option>                                
                       @endforeach
                    </select>
                </div>
            </div>
   
        </div>
        <div class="row pl-5 pr-5 pb-4">
            <div class="col-4">
                <div class="form-group" >
                    <label>Fees</label>
                   <input type="number" name="fees" id="fees" class="form-control"  > 
                </div>
            </div>
               <div class="col-4">
                <div class="form-group">
                    <label>Date</label>
                   <input type="date" name="date" class="form-control"  >
                </div>
               </div>
               <div class="col-4">
                <div class="form-group">
                    <label>Time</label>
                   <input type="time" name="time" class="form-control "  >
                </div>
            </div> 
        </div>            
        <div class="modal-footer pr-3">
            <a href="{{url('bill-pharmacy')}}" class="btn btn-danger"><i class="fa fa-arrow-left "></i> Back to Bills </a>
            <button class="btn btn-success submit" form="form_s" type="submit" ><i class="fa fa-print text-bold"></i>  Print Bill</button>
        </div>            
    </div>
</form>
<div id="print_modal" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20" id="divtoprint">
               <img src="{{url('public/payslip.png')}}" width="100%" alt="">
               <h5>Surgery & Delivery Bill</h5>
                <div class="print_html">

                </div>
                <div class="Qrcode" style="text-align: center;margin-top:12px">
                    <div id="qrcode">
                    </div> 
                <p>Powerd By: PMS Medical Complex</p>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>

@endsection
@section('jquery')
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script>
    $(document).ready(function() {
       $(".select2").select2({width: '100%',color:'#384364'});
        $(".alert").css('display','none');
      
        $('.type').change(function () {
          var val= $(this).val();
          $('#d').removeClass('d-none');
          $('.deps').prop('selectedIndex',0);

        }); 
        $('.deps').change(function() {
           var dep=$(this).val();
           var type=$('.type').val();
           url = '{{ url("operation_reg_docters") }}/' +dep+'/'+type;
            var Hdata = "";
            var Hdata1 = "";
            var Hdata2 = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data.emp != '') {
                        $('#doc').removeClass('d-none');
                        Hdata = '<option value="" selected disabled>Select Doctor</option>';
                        for (var i = 0; i < data.emp.length; i++) {
                            Hdata += '<option value="' + data.emp[i].emp_id + '">' + data.emp[i]
                                .f_name + ' ' + data.emp[i]
                                .l_name + '</option>';
                            $(".pos").html(Hdata);
                        }
                    } else { $(".pos").html('<option value="" selected disabled>No Record Found</option>');}
                    var type=$('.type').val();
                 
                      if (type == 'surgery') {
                        $('#procedure').addClass('d-none');
                        $('#surgery').removeClass('d-none');

                    }
                    if (type == 'procedure') {
                        $('#procedure').removeClass('d-none');
                        $('#surgery').addClass('d-none');
                    }
                    if (type == 'normal delivery') {
                        $('#surgery').addClass('d-none');
                        $('#procedure').addClass('d-none');

                    }
                
                },
                error: function() {}
            })
        });
        $("#form_s").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                }
            });
            $.ajax({
                url: '{{ url("surgery_registration") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.bill_number').load(document.URL + ' .bill_number');
                    $('#form_s')[0].reset();
                     print(data.id);
                     $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                },
                error: function(data) {
                    $(".alert").find("ul").html('');
                    $(".alert").css('display', 'block');
                    $.each(data.responseJSON.errors, function(key, value) {
                        $(".alert").find("ul").append('<li>' + value + '</li>');
                    });
              
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        $('#surg').change(function(){
            var val=$(this).val();
            $.ajax({
                type: 'get',
                url: "{{url('surgery_fees')}}/"+val,
                success: function(data) {
                 $('#fees').val(data);
                
                },
                error: function() {}
            })
        });
        $('#pro').change(function(){
            var val=$(this).val();
            $.ajax({
                type: 'get',
                url: "{{url('procedure_fees')}}/"+val,
                success: function(data) {
                 $('#fees').val(data);
                                  
                },
                error: function() {}
            })
        });
        
        function print(id){
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
                        type: 'get',
                        url: "{{url('print_operation')}}/"+id,
                        success: function(data) {
                        $('.print_html').html(data.html);
                        $('#qrcode').html(data.qr);
                        $('.print_modal').modal('show');
                            printDiv();
                        $('.print_modal').modal('hide');

                        },
                        error: function() {}
                    });
                        
                }
                });

        }
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
        
    });
</script>
@endsection