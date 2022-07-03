@extends('layouts.admin')
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
@section('title') Pharmacy Bill @endsection
@section('direct_btn')
<a href="{{url('bill-pharmacy')}}"><button class="btn btn-outline-primary">Today's Bills</button>
@endsection
@section('content')
<form id="form_medicine" method="post" >
    <div class="card">
        <div class="row p-5">
            <div class="col-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                    <select  class="form-control medicine_search" >
                    </select>
                    <label class="mt-2 ml-2 font-weight-bold">OR</label>       

                </div>
            </div>
            <div class="col-3">
                <div class="d-flex align-items-center ">
                    <div class="form-group mb-0" style="width:100%">
                        <input type="text" id="add_item" class="form-control barcode" placeholder="Barcode or QR-code scan here">
                    </div>
              
                </div>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                    <input type="text" form="form_medicine" class="form-control" required name="patient_name" id="customer_name" placeholder="Customer name" required>
                </div>
            </div>
        </div>
         {{-- table --}}
        <div class="table-responsive p-5">
          
            <table class="table table-bordered table-sm table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>Medicine Information <span class="text-danger">*</span></th>
                        <th>Expiry Date</th>
                        <th>Quantity<span class="text-danger">*</span></th>
                        <th>Avaliable</th>
                        <th> Price <span class="text-danger">*</span></th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="body_invoice">
                
                </tbody>
            </table>    
        </div> 
        <div class="p-5">
            <div class="d-flex">
                <div style="width:50%"></div>
               <div style="width:50%">
                 <table align="" class="printablea4" style=" float: right;">
                 <tbody>
                     <tr>
                     <th style="font-size: 16px">Total</th>
                     <td align="right" > <input type="number" form="form_medicine"  name="total" readonly id="totals" ></td>
                     </tr>

                     <tr>
                         <th style="font-size: 16px">Discount %</th>
                     <td align="right" ><input type="number" form="form_medicine" name="discount" max="{{Auth()->user()->discountp}}" class="discountpre"></td>
                     </tr>
                     <tr>
                     <th style="font-size: 16px">Net Amount</th>
                     <td align="right" ><input type="number"  form="form_medicine" name="netamount" readonly class="netamount"></td>
                     </tr>
                     
                     </tbody>
                 </table>
             </div>
            </div>
        </div>

            <div class="modal-footer pr-3">
                <a href="{{url('bill-pharmacy')}}" class="btn btn-danger"><i class="fa fa-arrow-left "></i> Back to Bills </a>
                <button class="btn btn-success submit" form="form_medicine" type="submit"  disabled><i class="fa fa-print text-bold"></i>  Print Bill</button>
            </div>
            
    </div>
</form>
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
    <li class="breadcrumb-item active" aria-current="page">Pharmacy Biling</li>
@endsection

@section('jquery')
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="https://rawgit.com/kabachello/jQuery-Scanner-Detection/master/jquery.scannerdetection.js"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>


<script>

    $(document).ready(function(){
        var checkMedicine = [];

    $(document).scannerDetection({
        timeBeforeScanTest: 200, // wait for the next character for upto 200ms
        avgTimeByChar: 100, // it's not a barcode if a character takes longer than 100ms
        onComplete: function(barcode, qty){
        $('.barcode').val(barcode).change();    
        } // main callback function	
    });
    
    $('body').on('change', '.barcode' ,function(){
        $.ajax({
            url:'{{ url("serach_medicine_barcode")}}/'+$(this).val(),
            type: 'get',
            success: function (data) {
                if(data!=""){
                    var a=data[0].midi_id;
                    addMedicinetoBill(a.toString());
                    $('.barcode').val(""); 

                }else{
                    $.growl.error({
                    message: "Medicine doesn't exist !" ,
                });
                }
            },
            error:function(data){
        
            },
            cache: true,
        });
    });
    $('.medicine_search').select2({
      placeholder: 'Serach Medicine',
      ajax: {
        url: '{{ url("search_medicine_name")}}',
        dataType: 'json',
        delay: 10,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
                  return {
                      text: item.medicine_name,
                      id:item.midi_id
                  }
              })
          };
        },
        cache: true
      }
    });
        // change the medicine
        $('.medicine_search').on("change", function() { 
        var data =$(this).val();
           addMedicinetoBill(data); 
        });
        // add medicine to bill automatic
        function addMedicinetoBill(val){
            // alert(val);
            if(checkMedicine.indexOf(val) != -1) {
                $.growl.error({
                    message: "Medicine already exist !",
                });
            }else{
                $.ajax({
                    type: 'get',
                    url: "{{url('add_medicine_detail')}}/"+val,
                    success: function(data) {
                        if (data != '') {
                        $('#body_invoice').append(data.html);
                        $('.submit').attr('disabled',false);
                        checkMedicine.push(data.id);
                        // console.log(checkMedicine);
                        }
                    },
                });
            }
        }
        // addQuant 

        $('body').on('keypress', '.invoice_q' ,function(){
            var id = $(this).attr('data-d');
            var data=$(this).val();
            caclculateQuant(id,data);
        });
   
        $(document.body).on('change', '.invoice_q' ,function(){
            var id = $(this).attr('data-d');
            var data=$(this).val();
            caclculateQuant(id,data);
        });
        $(document.body).on('keyup', '.invoice_q' ,function(){
            var id = $(this).attr('data-d');
            var data=$(this).val();
            caclculateQuant(id,data);
        });      
  
        function caclculateQuant(id,data){
            var total=$('#price_item'+id).val()*data;  
            $("#total_price"+id).val(total);
            checkalltotal();
            caclculatetotalBYprecent($('.discountpre').val(),$('#totals').val());
        }
        function checkalltotal(){
            var arr = document.getElementsByClassName("total_price_final");
            var tot=0;
            for(var i=0;i<arr.length;i++){
                if(parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            document.getElementById('totals').value = tot;
        }
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

        // medicine delete
        $('body').on('click', '.del-medicine' ,function(){
            deleteMedicine($(this).attr('data-ref'));
        });
       function deleteMedicine(id){
           $('#row'+id).remove();
           checkalltotal();
           caclculatetotalBYprecent($('.discountpre').val(),$('#totals').val());

       }

    // send data

       $('#form_medicine').submit(function(e){
        e.preventDefault();   
        $('.submit').prop('disabled', true);

        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("bill-pharmacy")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $('#body_invoice').html("");
                checkMedicine=[];
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
                    printDivData(data.bill_id);
                }
            });
            },
            error:function(data){
          
            },
            processData: false,
            contentType: false,

        });

       });
    //   print slip

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


 
});

    
    </script>
@endsection