@extends('layouts.admin')
@section('title') Laboratory Bill Update @endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Laboratory Biling Update</li>
@endsection

@section('css')
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }
    .table thead th{
        padding-top: 0px !important;
        padding-bottom: 0px !important;

    }
    .table-bordered{
        border: 1px solid #e4e5e7 !important;
    }
    .select2 {
    z-index: 0 !important;
    }
  
    .text_filed_sm{
        height: 30px !important;
    }
    #totals{
        font-size: 18px;
        font-weight: 800;
        text-align: center;
        height:50px;
        width:68%;
        background-color: #cbd0d6;
    }
    .discountpre{
        font-size: 14px;
        font-weight: 800;
        text-align: center;
        height:35px;
        width:68%;
    }
    .netamount{
        font-size: 14px;
        font-weight: 800;
        text-align: center;
        height:35px;
        width:68%;
        background-color: #cbd0d6;
    }
    </style>
@endsection
@section('direct_btn')
<a href="{{url('bill-lab')}}"><button class="btn btn-outline-primary">Today's Bills</button>
@endsection
@section('content')
<form id="form_medicine" method="post" >
    <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">Invoice Update </h6>
                </div>
                
            </div>
        </div>
        <div class="row p-5 ">
        
            <div class="col-6">
                <div class="row">
                    <div class="col-4  text-right">
                    <label class=" text-right ">Invoice Number :</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="bill_number" readonly="" value="{{$bill->bill_no}}" class="form-control" tabindex="1">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-4  text-right">
                        <label for="customer_name">Customer Name <i class="text-danger"> * </i>:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" required="" name="patient_names" value="{{$bill->patient_name}}" class="form-control" tabindex="1">
                        <input type="hidden" name="bill_id" id="bill_id_no" value="{{$bill->bill_id}}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-4 text-right">
                        <label>Details:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="note" class="form-control" value="{{$bill->note}}" tabindex="1">
                    </div>
                </div>
               
            </div>
    
        </div>
        <div class="add pl-5 pr-5 text-right">
            <button type="button" class="btn btn-success " data-toggle="modal" data-target="#addtest"><strong>+</strong></button>
        </div>

         {{-- table --}}
        <div class="table-responsive p-5">
          
            <table class="table table-bordered table-sm table-sm text-nowrap main-t">
                <thead>
                    <tr>
                        <th>No #</th>
                        <th>Test Name <span class="text-danger">*</span></th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="body_invoice">
                    @php $c=1; @endphp
                   @foreach ($bill_info as $item)
                        <tr id="row{{$item->lab_bill_ifo_id}}">
                           <td>{{$c++}}</td> 
                           <td>{{$item->test_type}}</td> 
                           <td>{{$item->total}}</td> 
                            <td align="center">
                                 @if (!empty(Helper::getpermission('_deletelabBillValue')))
                                 <a data-delete="{{$item->lab_bill_ifo_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                                 @endif
                                @if (!empty(Helper::getpermission('_editlabBillValue')))
                                <a data-data="{{$item->lab_bill_ifo_id}}" data-lab="{{$item->test_id}}" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>
                                @endif
                            </td>
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>    
        </div> 
        <div class="p-5">
            <div class="d-flex">
                <div style="width:50%"></div>
               <div style="width:50%">
                 <table align="" class="total_pay" style=" float: right;">
                 <tbody>
                    @php $bill_total=Helper::getTotallabBill($bill->bill_id);
                    $total1=$bill->discount*$bill_total/100;   
                     $final=$bill_total-$total1;
                   @endphp 

                     <tr>
                     <th style="font-size: 16px">Total</th>
                     <td align="right" > <input type="number" form="form_medicine"  name="total" readonly id="totals" value="{{$bill_total}}"></td>
                     </tr>

                     <tr>
                         <th style="font-size: 16px">Discount %</th>
                     <td align="right" ><input type="number" form="form_medicine" name="discount" max="{{Auth()->user()->discountp}}" value="{{$bill->discount}}" class="discountpre"></td>
                     </tr>
                     <tr>
                   
                     <th style="font-size: 16px">Net Amount</th>
                     <td align="right" ><input type="number"  form="form_medicine" name="netamount" value="{{$final}}" readonly class="netamount"></td>
                     </tr>
                     
                     </tbody>
                 </table>
             </div>
            </div>
        </div>

        <div class="modal-footer pr-3">
            <a href="{{url('bill-pharmacy')}}" class="btn btn-danger"><i class="fa fa-arrow-left "></i> Back to Bills </a>
            <button class="btn btn-success submit" form="form_medicine" type="submit"  ><i class="fa fa-print text-bold"></i>  Update & print</button>
        </div>
            
    </div>
</form>
<div id="addtest" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Add test to bill</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert1234 alert-danger"><ul id="error"></ul></div>

                <form method="post" id="editform">
                  
                    <div class="form-group">
                        <label>Test Type</label>
                       <select name="test"  class="form-control  test_name  select2">
                           <option value="" selected disabled>select</option>
                           @foreach ($test as $testrow)
                               <option value="{{$testrow->test_id}}">{{$testrow->test_type}}</option>
                           @endforeach
                       </select>
                    </div>
                    <input type="hidden" name="bill_id" class="bill_id" value="{{$bill->bill_id}}">
                    <div class="modal-footer">
                       <button type="submit" class="btn btn-primary">Add Test</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
<div id="edit_medicine" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Edit Test Bill </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                <form method="post" id="edit_bill_form">

                    <div class="form-group">
                        <label>Test Type</label>
                       <select name="test"  class="form-control  test_name test_id12 select2">
                           <option value="" selected disabled>select</option>
                           @foreach ($test as $testrow)
                           <option value="{{$testrow->test_id}}">{{$testrow->test_type}}</option>
                         @endforeach

                       </select>
                    </div>
                    <input type="hidden" name="bill_ids" class="bill_ids1">
                  
                    <div class="modal-footer">
                       <button type="submit" class="btn btn-primary">Edit Test</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
<div id="print_modal" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Edit Bill Medicine</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20" id="divtoprint">
                <img src="{{ url('public/payslip.png') }}" width="100%" alt="">
                <h5>Laboratory Bill</h5>
                <div style="display:flex;margin-top: 20px">
                    <div style="width:50%;text-align: left">
                        <div class="form-group ">
                            <label>Bill #:  <strong id="bill_no1"></strong></label>
                        </div>
                    </div>
                    <div style="width: 50%;text-align:right">
                        <div class="form-group float-right">
                            <label>Date:   <strong id="bill_date1"></strong></label>
                        </div>
                    </div>
                </div>
                <div style="display:flex;margin-top: 20px">
                    <div style="width:50%;text-align: left">
                        <div class="form-group ">
                            <label>Patient Name :  <strong id="patient_name222"></strong></label>
                        </div>
                    </div>
                  
                </div>
                <div style="margin-top: 20px">
                    <table class="printablea4 table" width="100%">
                        <tbody>
                            <tr>
                                <th align="left">Test Name</th>
                                <th align="left">Amount </th>
                            </tr>
                        <tbody id="hdata1">

                        </tbody>

                        </tbody>
                    </table>
                </div>
                <div class="d-flex" style="margin-top: 20px">
                    <div style="width:100%">
                      <table align="" class="printablea4" style="width: 40%; float: right;">
                      <tbody>
                          <tr>
                          <th >Total</th>
                          <td align="right" id="totals1"></td>
                          </tr>
                          <tr>
                          <th>Discount %</th>
                          <td align="right" class="discountpre1"></td>
                          </tr>
                          </tbody>
                      </table>
                  </div>
                 </div>
                 <div class="Qrcode" style="text-align: center">
                    <div id="qrcode">
        
                    </div> 
                      <p>Powerd By: PMS Medical Complex</p>
        
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
@endsection

@section('jquery')
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.alert').hide();
            $('.select2').select2({'width':'100%'});
        });
    </script>

    <script>
    $(document).ready(function(){
        //calculate
        $('body').on('keypress', '.discountpre' ,function(){
            caclculatetotalBYprecent($(this).val(),$('#totals').val());
        });
        $('body').on('change', '.discountpre' ,function(){
            caclculatetotalBYprecent($(this).val(),$('#totals').val());
        });
        $('body').on('keyup', '.discountpre' ,function(){
            caclculatetotalBYprecent($(this).val(),$('#totals').val());
        });
        function caclculatetotalBYprecent(val1,val2){
            var final= val1 * val2/100;
            $('.netamount').val($('#totals').val()-final)
        } 
    });
  //calculate
    </script>
    <script>
 $('body').on('click','.edit_medicine',function () {
    var id=$(this).attr('data-lab');
    var id2=$(this).attr('data-data');
    $('.test_id12').val(id).change();
    $('.bill_ids1').val(id2);
});

// update full page

$('#form_medicine').submit(function(e){
    e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("bill-lab_update")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $('#form_medicine')[0].reset();
                $.growl.notice({
                    message: data.msg,
                    title: 'Success !',
                });
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
                    // var val=$('#bill_id_no').val();
                    printDivData(data.id);

                }
            });
            },
            error:function(data){
          
            },
            processData: false,
            contentType: false,
        });

});
// print
      function printDivData(id) {
        var htmldata="";
        $.ajax({
            url:'{{ url("bill_lab_info_detail")}}/'+id,
            type: 'get',
            success: function (data) {
                $('#print_modal').modal('show');
                if(data.info !=""){
                for (let i = 0; i < data.info.length; i++) {
                    
                     htmldata+='<tr id="row'+data.info[i].lab_bill_ifo_id+'">\
                        <td>'+ data.info[i].test_type+'</td>\
                        <td>'+ data.info[i].total+'</td>\
                        </tr>'; 
                        
                        $('#hdata1').html(htmldata);
                }
 
                $('#totals1').html(data.total);             
                $('.discountpre1').html(data.bill.discount);
                $('#qrcode').html(data.qr);
                $('#bill_no1').html(data.bill.bill_no);

                $('#bill_date1').html(data.date);
                $('#patient_name222').html(data.bill.patient_name);
    
                setTimeout(function(){ printDiv(); },500);
 
                }

            },
            error:function(data){
              
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
        
      }
        
      function printDiv() {
            var divToPrint=document.getElementById('divtoprint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}  th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            $('.modal').modal('hide');
            setTimeout(function(){newWin.close();},10);
        }
   

  
// add
$("#editform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("bill_lab_info")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert1234").css('display','none');
                $('.main-t').load(document.URL +  ' .main-t');
                $('.total_pay').load(document.URL +  ' .total_pay');

                $('#addtest').modal('hide');
                $('#editform')[0].reset();
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });

            },
            error:function(data){
                $(".alert1234").find("ul").html('');
                $(".alert1234").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert1234").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
// update
    $("#edit_bill_form").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("lab_update_test_bill")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                $('.main-t').load(document.URL +  ' .main-t');
                $('.total_pay').load(document.URL +  ' .total_pay');
                $('#edit_medicine').modal('hide');
                $('#edit_bill_form')[0].reset();
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });

            },
            error:function(data){
                $(".alert12").find("ul").html('');
                $(".alert12").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert12").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
   // delete
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
                    url:'{{url("bill_lab_info")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    )
                    $('#row'+id).hide(1500);
                    },
                    error:function(error){
                    Swal.fire(
                      'Faild!',
                      'Test has related Data please first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
// delete

 
    </script>
@endsection