@extends('layouts.admin')

@section('css')
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
<style>
 input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
.select2 {
  z-index: 0 !important;
}
/* #step1{
    pointer-events: none;  
} */
</style>
@endsection
@section('title') Generate Pharmacy Bill @endsection

@section('content')
    <div class="row" id="step1">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Generate Bill</h3>
                </div>
                <div class="card-body">
                    <form method="post" id="createform">
                    
                            {{-- <div class="col-md-2"> --}}
                                <div class="form-group billll">
                                    <label>Bill Number</label>
                                    <input type="text" readonly name="bill_number" class="form-control"
                                        value="@php $max=helper::getpharmacyBillNo()@endphp {{ $max }}">
                                </div>

                            {{-- <div class="col-md-2"> --}}
                                <div class="form-group">
                                    <label>Patient Type</label>
                                    <select name="patient_type" class="form-control patient_type">
                                        <option value="" disabled selected>select</option>
                                        <option value="Registred Patient">Registred Patient</option>
                                        <option value="OPD Patient">OPD Patient</option>
                                        <option value="Outside Patient">Outside Patient</option>
                                    </select>
                                    
                                </div>
                            {{-- </div> --}}
                            <div  id="p_default">
                                <div class="form-group">
                                    <label>Patient Name</label>
                                    <select class="form-control"></select>
                                </div>
                            </div>

                            <div  style="display:none" id="patient_group">
                                <div class="form-group">
                                    <label>Patient Name</label>
                                    <select id="select21" style="width: 100%" class="patient_search form-control"
                                        name="patient_name"></select>
                                </div>
                            </div>
                            <div  style="display:none" id="patient_group_opd">
                                <div class="form-group">
                                    <label>OPD Patient Name</label>
                                    <select id="select22" style="width: 100%" class="opd_search form-control"
                                        name="patient_name"></select>
                                </div>
                            </div>

                            <div  style="display:none" id="patient_group1">
                                <div class="form-group">
                                    <label>Patient Name</label>
                                    <input type="text" name="patient_name" id="patient_id1382" class="form-control"
                                        placeholder="Patient Name">
                                </div>
                            </div>
                            <div >
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <select name="department" class="form-control deps">
                                        <option value="" selected disabled>select</option>
                                        @foreach ($department as $row)
                                            <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label>Doctor Name</label>
                                    <select name="docter_name" class="form-control pos">
                                        <option value="" selected disabled>select</option>
                                    </select>
                                </div>
                            </div>
                            <div >
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea class="form-control" name="note" cols="30"></textarea>
                                </div>
                            </div>

                     
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-right bill_submit">Generate Bill</button>
                        </div>
                    </form>

                </div>
            </div>

        </div><!-- COL END -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Add Medicine to Bill </h3>
                </div>
                <div class="card-body">
                    <form method="post" id="editform">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label> Medicine Catagory </label>
                                    <select name="midicine_catagory" class="form-control midi_cat"
                                        onchange="getmedicine_name(this.value)">
                                        <option value="" selected disabled>select catagory</option>
                                        @foreach ($cat as $item)
                                            <option value="{{ $item->ph_main_cat_id }}">{{ $item->m_cat_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Medicine Name</label>
                                    <select name="medicine" width="100%" class="select2 form-control med medicine_name">
                                        <option value="" selected disabled>select medicine</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="month" name="expiry_date" readonly class="month form-control">
                                </div>
                            </div>


                            <div class="col-md-2">
                                <input type="hidden" name="bill_id" class="bill_id">
                                <div class="form-group">
                                    <label> Quantity| Available</label>
                                    <div class="d-flex ">
                                        <input type="number" step="0.1" name="quantity"
                                            class=" d-inline quantity form-control">
                                        <span class="avliableQty form-control"
                                            style="text-align:center;width:140px;font-size: 12pt;padding: 5px 14px;border-radius: 0;border-color: #d2d6de;background-color: rgb(156, 156, 156);"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" name="sale_price" readonly class="sale_price form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" name="amount" readonly class="amount form-control">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" disabled class="btn btn-primary add_submit float-right">ADD To Bill</button>
                        </div>
                    </form>

                </div>
            </div>

        </div><!-- COL END -->
    </div>
    {{-- <div class="row">
       
    </div> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="modal-body">
                        <img src="{{ url('public/payslip.png') }}" width="100%" height="200px" alt="">

                        {{-- @if (!empty(Helper::getpermission('_addpharmacyBillValue')))<button class="btn btn-blue float-right" data-toggle="modal" data-target="#editdept">Add Medicine</button>@endif --}}

                        <br><br>
                        <div class="row pl-2 pr-2">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Bill #: <strong id="bill_no"></strong></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group float-right">
                                    <label>Date: <strong id="bill_date"></strong></label>
                                </div>
                            </div>
                        </div>
                        <div class="row p-4 table-sm table">
                            <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="10%">Patient Name</th>
                                        <td width="15%"><strong id="patient_name"></strong></td>
                                        <th width="10%">Doctor Name</th>
                                        <td width="15%"><strong id="docter_name"></strong></td>
                                        <th width="10%">Department</th>
                                        <td width="15%"><strong id="department"></strong></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="row p-4">
                            <table class="printablea4 table" id="testreport" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20%">Medicine Name</th>
                                        <th>Expiry Date</th>
                                        <th>Quantity</th>
                                        <th> Price ($)</th>
                                        <th>Amount </th>
                                        <th style="text-align: center">Action</th>

                                    </tr>
                                <tbody id="hdata">

                                </tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex">
                            <div style="width:40%">
                                <form id="discount" method="post">
                                    <div class="input-group">
                                        <input type="number" name="discount" min="0" class="form-control"
                                            placeholder="Discount % ..." required max="{{ Auth()->user()->discountp }}">
                                        <input type="hidden" name="bill_id" class="bill_id">
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Discount %</button>
                                        </span>
                                    </div>
                                </form>

                            </div>
                            <div style="width:50%">
                                <table align="" class="printablea4" style="width: 60s%; float: right;">
                                    <tbody>
                                        <tr>
                                            <th style="width: 30%;">Total</th>
                                            <td align="right" id="totals"></td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td align="right" class="discount"></td>
                                        </tr>
                                        <th>Discount %</th>
                                        <td align="right" class="discountpre"></td>
                                        </tr>
                                        <tr>
                                            <th>Net Amount</th>
                                            <td align="right" class="netamount"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer pr-3">
                    <a href="{{url('bill-pharmacy')}}" class="btn btn-danger"><i class="fa fa-arrow-left "></i> Back to Bills </a>
                    <button id="relod" class="btn btn-info" ><i class="fa fa-plus"></i> Generate new bill</button>
                    <button class="btn btn-success print_slip"  data-toggle="modal" data-target="#print_modal" disabled><i class="fa fa-print text-bold"></i>  Print Bill</button>
                </div>
            </div><!-- modal-body -->
        </div>
    </div>

    </div><!-- COL END -->
    </div>
{{-- model --}}
    <div id="edit_medicine" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Bill Medicine</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="edit_medicine_form">
                        <div class="form-group">
                            <label>Medicine Catagory</label>
                            <select name="midicine_catagory" class="form-control midi_cat" id="midi_cat12" onchange="getmedicine_name(this.value)" >
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->ph_main_cat_id}}">{{$item->m_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Medicine Name</label>
                           <select name="medicine"  class="select2 form-control med medicine_name" id="medicine_name12">
                               <option value="" selected disabled>select medicine</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                          <input type="month" name="expiry_date" readonly class="month form-control" id="month12">
                        </div>
                        <input type="hidden" name="bill_id" class="bill_id" id="bill_id12">
                            <div class="form-group">
                                <label> Quantity| Available Qty</label>
                                <div class="d-flex ">
                                    <input type="number" step="0.1" name="quantity" class=" d-inline quantity form-control" id="quantity12">
                                    <span id="avliableQty12" class="avliableQty form-control" 
                                    style="text-align:center;width:140px;font-size: 12pt;padding: 5px 14px;border-radius: 0;border-color: #d2d6de;background-color: rgb(156, 156, 156);"></span>
                                </div>
                            </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                          <input type="text" name="sale_price" readonly class="sale_price form-control" id="sale_price12">
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="amount"  readonly class="amount form-control" id="amount12">
                        </div>
                        

                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Edit Medicine</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="print_modal" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Bill Medicine</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20" id="divtoprint">
                   <img src="{{url('public/payslip.png')}}" width="100%" alt="">
                   <h5>Pharmacy Bill</h5>
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
            <div class="row p-4 table-sm table " style="margin-top: 20px"> 
                <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
                    <tbody><tr>
                        <th width="15%">Patient Name</th>
                        <td width="15%"><label id="patient_name1"></label></td>
                        <th width="15%">Doctor Name</th>
                        <td width="15%"><label id="docter_name1"></label></td>
                        <th width="15%">Department</th>
                        <td width="15%"><label id="department1"></label></td>
                    </tr>
            
                </tbody>
              </table>
           </div>
           <div style="margin-top: 20px">
            <table class="printablea4 table"  width="100%">
                <tbody>
                <tr>
                    <th align="left">Medicine Name</th>
                    <th align="left">Expiry Date</th>
                    <th align="left">Quantity</th>
                    <th align="left">Price</th>
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
                    <th>Discount</th>
                    <td align="right" class="discount1"></td>
                    </tr>
                    <th>Discount %</th>
                    <td align="right" class="discountpre1"></td>
                    </tr>
                    <tr>
                    <th>Net Amount</th>
                    <td align="right" class="netamount1"></td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
           </div>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

@endsection
@section('jquery')
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>

    <script>
        $('.alert').hide();
        $('.patient_type').change(function() {
            if ($(this).val() == 'Registred Patient') {
                $('#patient_group').css('display', 'block');
                $('#patient_group1').css('display', 'none');
                $('#patient_id1382').attr('name', '');
                $('#select21').attr('name', 'patient_name');
                $('#select22').attr('name', '');
                $('#patient_group_opd').css('display', 'none');
                $('#p_default').css('display', 'none');

            } else if ($(this).val() == 'OPD Patient') {
                $('#patient_group_opd').css('display', 'block');
                $('#patient_group1').css('display', 'none');
                $('#patient_group').css('display', 'none');
                $('#patient_id1382').attr('name', '');
                $('#select21').attr('name', '');
                $('#select22').attr('name', 'patient_name');
                $('#p_default').css('display', 'none');
            } else {
                $('#patient_group').css('display', 'none');
                $('#patient_group1').css('display', 'block');
                $('#select21').attr('name', '');
                $('#patient_id1382').attr('name', 'patient_name');
                $('#select22').attr('name', '');
                $('#patient_group_opd').css('display', 'none');
                $('#p_default').css('display', 'none');

            }
        });
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.select2').select2({width: '100%',color:'#384364'});
               // opd serach
       $('.opd_search').select2({
         placeholder: 'Select an item',
         ajax: {
           url: '{{ url("search_opd_record")}}',
           dataType: 'json',
           delay: 10,
           processResults: function (data) {
             return {
               results:  $.map(data, function (item) {
                     return {
                         text: item.o_f_name +' '+item.o_l_name +' '+ 'opd-'+item.patient_id,
                         id:item.o_f_name +' '+item.o_l_name +'-'+ item.patient_id
                     }
                 })
             };
           },
           cache: true
         }
       });
       // patient serach
   $('.patient_search').select2({
         placeholder: 'Select an item',
         ajax: {
           url: '{{ url("search_patient_record")}}',
           dataType: 'json',
           delay: 10,
           processResults: function (data) {
             return {
               results:  $.map(data, function (item) {
                     return {
                         text: item.f_name +' '+item.l_name +' '+ 'p-'+item.patient_idetify_number,
                         id:item.patient_id+'-'+item.patient_idetify_number+ '-'+item.f_name +' '+item.l_name
                     }
                 })
             };
           },
           cache: true
         }
       });

    });
   </script>
   <script>
    //    department
       var Midicine="";
       var emp="";
        $('.deps').change(function() {       
            var dep=$(this).val();
            url = '{{ url("appoinments_get_position") }}/' +dep;
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
                         if(emp !=""){
                            $(".pos").val(emp);
                         }
                     } else { $(".pos").html('<option value="" selected disabled>No Record Found</option>');}
                 },
 
                 error: function() {}
             })
 
         });
   </script>
   <script>
    //  medicine part  
    function getmedicine_name(id) {
            $("#medicine_name").html("<option value=''>Select</option>");
            url = '{{url("medicineFiter")}}' +'/'+ id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        Hdata = '<option value="" selected disabled>select medicine</option>';
                        for (var i = 0; i < data.length; i++) {
                            Hdata += '<option value="' + data[i].midi_id + '">' + data[i]
                                .medicine_name + ' ' + data[i]
                                .company + '</option>';
                            // $(".med").html(Hdata);
                            $(".medicine_name").html(Hdata);
                            if(Midicine!==""){
                                $('.medicine_name').val(Midicine);
                            }
                        }
                    } else {
                        $(".medicine_name").html('<option value="" selected disabled>No Record Found</option>');
                    }
                },
                error: function() {}
            })
        };
    $('body').on('change','.med',function(){ 
      $.ajax({
          url:'{{ url("getMedicine_info")}}/'+$(this).val(),
          type: 'get',
          success: function (data) {
            //   console.log(data.total[0]['expiry_date']);
             $('.month').val(data.total[0]['expiry_date']);
             $('.avliableQty').html(data.avaliable);
             $('.quantity').attr('Max',data.avaliable);
             $('.sale_price').val(data.total[0]['sale_price']);
          },
          error:function(data){
              $(".alert1").find("ul").html('');
              $(".alert1").css('display','block');
          $.each( data.responseJSON.errors, function( key, value ) {
                  $(".alert1").find("ul").append('<li>'+value+'</li>');
              });     
  
          },
          cache: false,
          contentType: false,
          processData: false
      });
  });
  $('body').on('keyup','.quantity',function() {
        if($("#edit_medicine").data('bs.modal')?._isShown){
            var total= $(this).val()*$('#sale_price12').val();  
            $('#amount12').val(total);
        }else{
            var total= $(this).val()*$('.sale_price').val();  
            $('.amount').val(total);
        }
         
    });
   </script>

   <script>
    //  generate bill 
    var err="";
    var darray=[];

    $("#createform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('bill-pharmacy')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                darray.push(data.html);
                $('#createform')[0].reset();
                     $.growl.notice({
                    message: data.msg,
                    title: 'Success !',
                });
                $('.bill_id').val(darray[0].bill_id);
                $('#bill_no').html(darray[0].bill_no);
                $('#patient_name').html(darray[0].patient_name);
                $('#department').html(darray[0].department_name);
                $('#docter_name').html(darray[0].ef+' '+darray[0].el);
                $('#bill_date').html(darray[0].date);
                $('#data-total').html(darray[0].total);
                $('.bill_submit').attr('disabled',true);
                $('.add_submit').attr('disabled',false);
                $('.print_slip').attr('disabled',false);

       
            },
            error:function(data){
                err="";
            $.each( data.responseJSON.errors, function( key, value ) {
                   err+='<li class="text-danger">'+value+'</li>';
                });     
                Swal.fire(
                    "Hey there is a problem!",
                        err,
                      'error'
                );  
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    // add medicine 
    $("#editform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("pharmacy_add_medicine_bill")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                    var htmldata="";
                    $.ajax({
                        url:'{{ url("bill_pharmacy_detail")}}/'+$('.bill_id').val(),
                        type: 'get',
                        success: function (data) {
                            $('#hdata').html("");
                            for (let i = 0; i < data.info.length; i++) {
                                htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                                    <td>'+ data.info[i].quanitity+'</td>\
                                    <td>'+ data.info[i].price+'</td>\
                                    <td>'+ data.info[i].total+'</td>\
                                    <td align="center">\
                                        @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                        @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
                                        </td>\
                                    </tr>'; 
                                    
                                    $('#hdata').html(htmldata);
                            }
                            $('#totals').html(data.totals);
                            var discount=data.discount*data.totals/100;
                            $('.discount').html(discount);
                            $('.discountpre').html(data.discount+'%');
                            $('.netamount').html(data.totals-discount);
                        },                   
                    });

                $('#editform')[0].reset();
                 $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
            },
            error:function(data){
                err="";
            $.each( data.responseJSON.errors, function( key, value ) {
                   err+='<li class="text-danger">'+value+'</li>';
                });     
                Swal.fire(
                    "Hey there is a problem!",
                        err,
                      'error'
                ); 
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    // discount
    $('#discount').submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('pharmacy-bill-discount')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                var htmldata="";
                $.ajax({
                    url:'{{ url("bill_pharmacy_detail")}}/'+$('.bill_id').val(),
                    type: 'get',
                    success: function (data) {
                        $('#hdata').html("");
                        for (let i = 0; i < data.info.length; i++) {
                            htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                                <td>'+ data.info[i].quanitity+'</td>\
                                <td>'+ data.info[i].price+'</td>\
                                <td>'+ data.info[i].total+'</td>\
                                <td align="center">\
                                    @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                    @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
                                    </td>\
                                </tr>'; 
                                
                                $('#hdata').html(htmldata);
                        }
                        $('#totals').html(data.totals);
                        var discount=data.discount*data.totals/100;
                        $('.discount').html(discount);
                        $('.discountpre').html(data.discount+'%');
                        $('.netamount').html(data.totals-discount);

                    },
                
                });
                
                $('#createform')[0].reset();
                $('#discount')[0].reset();
                    return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
            },
            error:function(data){
                err="";
            $.each( data.responseJSON.errors, function( key, value ) {
                   err+='<li class="text-danger">'+value+'</li>';
                });     
                Swal.fire(
                    "Hey there is a problem!",
                        err,
                      'error'
                ); 
            },
            cache: false,
            contentType: false,
            processData: false
        }); 
    });
   </script>

{{-- <script>
const foo = document.querySelector('#step1')  
foo.addEventListener('click', (event) => {  
  event.preventDefault();  
});
</script> --}}
<script>
$('body').on('click','.edit_medicine',function() {
  
  $.ajax({
      url: "{{url('getMedicine_info_edit')}}/"+$(this).attr('data-data'),
      type: 'get',
      success: function (data) {
          $('#midi_cat12').val(data.mid_cat);
          $('#midi_cat12').trigger('change');
          $('#medicine_name12').val(data.info['midi_id']);
          if(data.info['midi_id']!==""){
              Midicine=data.info['midi_id'];
          }
          $('#month12').val(data.info['expiry_date']);
          $('#quantity12').val(data.info['quanitity']);
          $('#sale_price12').val(data.info['price']);
          $('#amount12').val(data.info['total']);   
          $('#bill_id12').val(data.info['pharma_bill_ifo_id']);                 
          $('#avliableQty12').html(data.avaliable);    
                
      },
      error:function(data){
          console.log('server Error');
      },
      cache: false,
      contentType: false,
      processData: false
  }); 
});
$("#edit_medicine_form").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("pharmacy_update_medicine_bill")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                    var htmldata="";
 
                    $.ajax({
                        url:'{{ url("bill_pharmacy_detail")}}/'+$('.bill_id').val(),
                        type: 'get',
                        success: function (data) {
                            $('#hdata').html("");
                            for (let i = 0; i < data.info.length; i++) {
                                htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                                    <td>'+ data.info[i].quanitity+'</td>\
                                    <td>'+ data.info[i].price+'</td>\
                                    <td>'+ data.info[i].total+'</td>\
                                    <td align="center">\
                                        @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                            @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
                                        </td>\
                                    </tr>'; 
                                    
                                    $('#hdata').html(htmldata);
                            }
                            $('#totals').html(data.totals);
                            var discount=data.discount*data.totals/100;
                            $('.discount').html(discount);
                            $('.discountpre').html(data.discount+'%');
                            $('.netamount').html(data.totals-discount);

                        },
                    
                    
                    });

                $('#edit_medicine').modal('hide');
                $('#edit_medicine_form')[0].reset();
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
                    type:'get',
                    url:'{{url("pharmacy_delete_medicine_bill")}}/'+id,
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
                      'Medicine has related Data please first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
</script>
<script>
    $('#relod').click(function(){
        $(window).scrollTop(0);
        window.location.reload();

    });
</script>
{{-- print --}}
<script>
    $('body').on("click",'.print_slip',function() {
        $('.bill_id').val(darray[0].bill_id);
        $('#bill_no1').html(darray[0].bill_no);
        $('#patient_name1').html(darray[0].patient_name);
        $('#department1').html(darray[0].department_name);
        $('#docter_name1').html(darray[0].ef+' '+darray[0].el);
        $('#bill_date1').html(darray[0].date);
        $('#data-total1').html(darray[0].total);

        var htmldata="";
        $.ajax({
            url:'{{ url("bill_pharmacy_detail")}}/'+darray[0].bill_id,
            type: 'get',
            success: function (data) {
                if(data.info !=""){
                for (let i = 0; i < data.info.length; i++) {
                    
                     htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td>'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                        <td>'+ data.info[i].quanitity+'</td>\
                        <td>'+ data.info[i].price+'</td>\
                        <td>'+ data.info[i].total+'</td>\
                        </tr>'; 
                        
                        $('#hdata1').html(htmldata);
                }
                $('#totals1').html(data.totals);
                var discount=data.discount*data.totals/100;
                $('.discount1').html(discount);
                $('.discountpre1').html(data.discount+'%');
                $('.netamount1').html(data.totals-discount); 
                setTimeout(function(){ printDiv(); },500);
 
                }else{
                    $('#hdata1').html('<tr><td>No record data found</td></tr>');
                    $('#totals1').html("N/A");
                    $('.discountpre1').html("N/A");
                    $('.netamount1').html("N/A");
                    $('.discount1').html("N/A");
                    setTimeout(function(){ printDiv(); },500);
                }
            },
            error:function(data){
              
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
        
      });
        
      function printDiv() {
            var divToPrint=document.getElementById('divtoprint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}  th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            $('.modal').modal('hide');
            setTimeout(function(){newWin.close();},10);
        }

  </script>
@endsection
