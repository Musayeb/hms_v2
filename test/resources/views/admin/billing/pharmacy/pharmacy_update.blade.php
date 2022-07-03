@extends('layouts.admin')
@section('title') Pharmacy Bill Update @endsection

@section('css')
<link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    } 
    #totals{
        font-size: 14px;
        font-weight: 800;
        text-align: center;
        height:50px;
        width:68%;
        background-color: #cbd0d6;
    }
    .discountpre{
        font-size: 12px;
        font-weight: 800;
        text-align: center;
        height:35px;
        width:68%;
    }
    .netamount{
        font-size: 12px;
        font-weight: 800;
        text-align: center;
        height:35px;
        width:68%;
        background-color: #cbd0d6;
    }
    </style>
@endsection
@section('direct_btn')
<a href="{{url('bill-pharmacy')}}"><button class="btn btn-outline-primary">Today's Bills</button>
@endsection
@section('content')
<form id="update_form">
<div class="card p-2">
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
                <label  class=" text-right ">Invoice Number :</label>
                </div>
                <div class="col-8">
                    <input type="text" name="bill_number" readonly value="{{$bill->bill_no}}"  class="form-control"   tabindex="1">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4  text-right">
                    <label for="customer_name" >Customer Name <i class="text-danger"> * </i>:</label>
                </div>
                <div class="col-8">
                    <input type="text" required name="patient_names" value="{{$bill->patient_name}}" class="form-control"   tabindex="1">
                    <input type="hidden" name="bill_id" id="bill_id_no" value="{{$bill->bill_id}}" >
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-4 text-right">
                    <label >Details:</label>
                </div>
                <div class="col-8">
                    <input type="text" name="note" class="form-control"  value="Thank You" tabindex="1">
                </div>
            </div>
           
        </div>

    </div>
    <div class="add pl-5 pr-5 text-right">
        @if (!empty(Helper::getpermission('_addpharmacyBillValue')))
        <button class="btn btn-success " type="button"  data-toggle="modal" data-target="#editdept"><strong>+</strong></button>
        @endif

    </div>
    <div class="row p-5 justify-content-center">
        <div class="col-12">
            <table class="main-t table" id="testreport" style="width: 100%">
                <tbody>
                    <tr>
                        <th>No #</th>
                        <th width="20%">Medicine Name</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th> Price ($)</th>
                        <th>Amount </th>
                        <th style="text-align: center">Action</th>
    
                    </tr>
                <tbody id="hdata">
                    @php $c=1; @endphp
                    @foreach ($bill_info as $item)
                        <tr id="row{{$item->pharma_bill_ifo_id}}">
                           <td>{{$c++}}</td> 
                           <td>{{$item->medicine_name}}</td> 
                           <td>{{$item->expiry_date}}</td> 
                           <td>{{$item->quanitity}}</td> 
                           <td>{{$item->price}}</td> 
                           <td>{{$item->total}}</td> 
                            <td align="center">
                                 @if (!empty(Helper::getpermission('_deletepharmacyBillValue')))
                                 <a data-delete="{{$item->pharma_bill_ifo_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                                 @endif
                                @if (!empty(Helper::getpermission('_editpharmacyBillValue')))
                                <a data-data="{{$item->pharma_bill_ifo_id}}" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
    
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-5">
        <div class="d-flex">
            <div style="width:50%"></div>
           <div style="width:50%">
             <table class="total_area" style=" float: right;">
             <tbody>
                 <tr>
                 <th style="font-size: 16px">Total</th>
                 <td align="right" > 
                    @php $bill_total=Helper::getTotalpharmacyBill($bill->bill_id);
                         $total1=$bill->discount*$bill_total/100;   
                          $final=$bill_total-$total1;
                    @endphp 
                    <input type="number" value="{{$bill_total}}"  name="total" readonly id="totals" ></td>
                 </tr>

                 <tr>
                     <th style="font-size: 16px">Discount %</th>
                 <td align="right" ><input type="number" value="{{$bill->discount}}" name="discount" max="{{Auth()->user()->discountp}}" class="discountpre"></td>
                 </tr>
                 <tr>
                 <th style="font-size: 16px">Net Amount</th>
                 <td align="right" ><input type="number"  name="netamount" value="{{$final}}" readonly class="netamount"></td>
                 </tr>
                 
                 </tbody>
             </table>
         </div>
        </div>
    </div>
    <div class="modal-footer pr-3">
        <a href="{{url('bill-pharmacy')}}" class="btn btn-danger"><i class="fa fa-arrow-left "></i> Back to Bills </a>
        <button class="btn btn-success submit" form="update_form" type="submit" ><i class="fa fa-print text-bold"></i>  Update & print</button>
    </div>
</div>
</form>
<div id="editdept" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Add Medicine to Bill</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert12 alert-danger">
                    <ul id="error"></ul>
                </div>

                <form method="post" id="editform">
             
                    <div class="form-group">
                        <label>Medicine Name</label>
                        <select name="medicine" class="select2 form-control med medicine_name">
                            <option value="" selected disabled>select medicine</option>
                             @foreach ($mid as $med1)
                               <option value="{{$med1->midi_id}}">{{$med1->medicine_name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="month" name="expiry_date" readonly class="month1 form-control">
                    </div>
                    <input type="hidden" name="bill_id" class="bill_id" value="{{$bill->bill_id}}">
                    <div class="form-group">
                        <label> Quantity| Available Qty</label>
                        <div class="d-flex ">
                            <input type="number" step="0.1" name="quantity" class=" d-inline quantity form-control">
                            <span class="avliableQty1 form-control"
                                style="text-align:center;width:140px;font-size: 12pt;padding: 5px 14px;border-radius: 0;border-color: #d2d6de;background-color: rgb(156, 156, 156);"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sale Price</label>
                        <input type="text" name="sale_price" readonly class="sale_price1 form-control">
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" name="amount" readonly class="amount form-control">
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Medicine</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>
<div id="edit_medicine" class="modal fade" style="z-index:100000">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Edit Bill Medicine</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert123 alert-danger">
                    <ul id="error"></ul>
                </div>

                <form method="post" id="edit_medicine_form">
                  
                    <div class="form-group">
                        <label>Medicine Name</label>
                        <select name="medicine" class="select2 form-control med medicine_name" id="medicine_name12">
                            <option value="" selected disabled>select medicine</option>
                            @foreach ($mid as $med1)
                                <option value="{{$med1->midi_id}}">{{$med1->medicine_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="month" name="expiry_date" readonly class="month form-control" id="month12">
                    </div>
                    <input type="hidden" name="bill_id" id="bill_id12">
                    <div class="form-group">
                        <label> Quantity| Available Qty</label>
                        <div class="d-flex ">
                            <input type="number" step="0.1" name="quantity" class=" d-inline quantity form-control"
                                id="quantity12">
                            <span id="avliableQty12" class="avliableQty form-control"
                                style="text-align:center;width:140px;font-size: 12pt;padding: 5px 14px;border-radius: 0;border-color: #d2d6de;background-color: rgb(156, 156, 156);"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sale Price</label>
                        <input type="text" name="sale_price" readonly class="sale_price form-control"
                            id="sale_price12">
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" name="amount" readonly class="amount form-control" id="amount12">
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
                <div style="display:flex;margin-top: 20px">
                    <div style="width:50%;text-align: left">
                        <div class="form-group ">
                            <label>Patient Name :  <strong id="patient_name"></strong></label>
                        </div>
                    </div>
                  
                </div>

               <div class="t">
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
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Pharmacy Biling Update</li>
@endsection

@section('jquery')
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script>
    $('.alert').hide();
    $('.select2').select2({'width':'100%'});
</script>
<script>
    $('body').on('change','.med',function(){

        $.ajax({
            url:'{{ url("getMedicine_info") }}/'+$(this).val(),
            type: 'get',
            success: function (data) {
            if($("#edit_medicine").data('bs.modal')?._isShown){
            $('.month').val(data.total[0]['expiry_date']);
            $('.avliableQty').html(data.avaliable);
            $('.quantity').attr('Max',data.avaliable);
            $('.sale_price').val(data.total[0]['sale_price']);
            }else{
            $('.month1').val(data.total[0]['expiry_date']);
            $('.avliableQty1').html(data.avaliable);
            $('.quantity1').attr('Max',data.avaliable);
            $('.sale_price1').val(data.total[0]['sale_price']);
            }

           
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

        $('body').on('keyup','.quantity',function(el) {
            if($("#edit_medicine").data('bs.modal')?._isShown){
                    var total= $(this).val()*$('.sale_price').val();  
                    $('#amount12').val(total);
            }else{
                var total= $(this).val()*$('.sale_price1').val();  
                    $('.amount').val(total);
            }
        });
        $('body').on('keypress','.quantity',function(el) {
            if($("#edit_medicine").data('bs.modal')?._isShown){
                    var total= $(this).val()*$('.sale_price').val();  
                    $('#amount12').val(total);
            }else{
                var total= $(this).val()*$('.sale_price1').val();  
                    $('.amount').val(total);
            } 
        });
        $('body').on('change','.quantity',function(el) {
            if($("#edit_medicine").data('bs.modal')?._isShown){
                    var total= $(this).val()*$('.sale_price').val();  
                    $('#amount12').val(total);
            }else{
                var total= $(this).val()*$('.sale_price1').val();  
                    $('.amount').val(total);
            }
        });



// send data
    $("#editform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url:'{{ url("pharmacy_add_medicine_bill") }}',
                type: 'post',
                data: formData,
                success: function (data) {
                    $(".alert12").css('display','none');
                    $('.main-t').load(document.URL +  ' .main-t');
                    $('.total_area').load(document.URL +  ' .total_area');
                    $('#editdept').modal('hide');
                    $('#editform')[0].reset();

                    $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                    checkalltotal();
                    
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
// update

    $('body').on('click','.edit_medicine',function() {

        $.ajax({
            url: "{{ url('getMedicine_info_edit') }}/"+$(this).attr('data-data'),
            type: 'get',
            success: function (data) {
          
                $('#medicine_name12').val(data.info['midi_id']).change();
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
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url:'{{ url("pharmacy_update_medicine_bill") }}',
                type: 'post',
                data: formData,
                success: function (data) {
                    $(".alert123").css('display','none');
                    $('.main-t').load(document.URL +  ' .main-t');
                    $('.total_area').load(document.URL +  ' .total_area');
                   
                    $('#edit_medicine').modal('hide');
                    $('#edit_medicine_form')[0].reset();
                     $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                    checkalltotal();

                },
                error:function(data){
                    $(".alert123").find("ul").html('');
                    $(".alert123").css('display','block');
                $.each( data.responseJSON.errors, function( key, value ) {
                        $(".alert123").find("ul").append('<li>'+value+'</li>');
                    });     

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });        

        // update



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

  //calculate
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
                    type:'get',
                    url:'{{ url("pharmacy_delete_medicine_bill") }}/'+id,
                    success:function(data){ 
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                        $('.total_area').load(document.URL +  ' .total_area');
                        checkalltotal();
                        $('#row'+id).hide();
                    },
                    error:function(error){
                    Swal.fire(
                        'Faild!',
                        'faild to delete record ',
                        'error'
                    )
                    }
                });
            }
            })

});  
// delete



// print
$('#update_form').submit(function(e){
    e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("bill-pharmacy_update")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $('#update_form')[0].reset();
                $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
                $('.total_area').load(document.URL +  ' .total_area');
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
                    var val=$('#bill_id_no').val();
                    printDivData(val);

                }
            });
            },
            error:function(data){
          
            },
            processData: false,
            contentType: false,
        });

});

function printDivData(id) {
        var htmldata="";
        $.ajax({
            url:'{{ url("bill_pharmacy_print")}}/'+id,
            type: 'get',
            success: function (data) {
                $('#print_modal').modal('show');
                if(data.info !=""){
                for (let i = 0; i < data.info.length; i++) {
                    
                     htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'">\
                        <td>'+ data.info[i].medicine_name+'</td>\
                        <td>'+ data.info[i].expiry_date +'</td>\
                        <td>'+ data.info[i].quanitity+'</td>\
                        <td>'+ data.info[i].price+'</td>\
                        <td>'+ data.info[i].total+'</td>\
                        </tr>'; 
                        
                        $('#hdata1').html(htmldata);
                }
 
                $('#totals1').html(data.total);             
                $('.discountpre1').html(data.bill.discount);
                $('#qrcode').html(data.qr);
                $('#bill_no1').html(data.bill.bill_no);

                $('#bill_date1').html(data.date);
                $('#patient_name').html(data.bill.patient_name);

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

</script>
@endsection