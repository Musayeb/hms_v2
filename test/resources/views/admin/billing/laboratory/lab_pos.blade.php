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
@section('direct_btn')
<a href="{{url('bill-lab')}}"><button class="btn btn-outline-primary">Today's Bills</button>
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
                    <select  class="form-control test_search" >
                    </select>

                </div>
            </div>
         
            <div class="col-6">
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
                        <th>Test Name <span class="text-danger">*</span></th>
                        <th>Amount</th>
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
                <a href="{{url('bill-lab')}}" class="btn btn-danger"><i class="fa fa-arrow-left "></i> Back to Bills </a>
                <button class="btn btn-success submit" form="form_medicine" type="submit"  disabled><i class="fa fa-print text-bold"></i>  Print Bill</button>
            </div>
            
    </div>
</form>
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
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script>
    $(document).ready(function(){
        var checkMedicine = [];
    $('.test_search').select2({
      placeholder: 'Serach Test',
      ajax: {
        url: '{{ url("get_all_test")}}',
        dataType: 'json',
        delay: 10,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
                  return {
                      text: item.test_type,
                      id:item.test_id
                  }
              })
          };
        },
        cache: true
      }
    });
    // add test
    $('.test_search').on("change", function() { 
        var data =$(this).val();
           addTesttoBill(data); 
    });
        // add medicine to bill automatic
        function addTesttoBill(val){
        // alert(val);
        if(checkMedicine.indexOf(val) != -1) {
            $.growl.error({
                message: "Test already exist !",
            });
        }else{
            $.ajax({
                type: 'get',
                url: "{{url('add_test_detail')}}/"+val,
                success: function(data) {
                    if (data != '') {
                    $('#body_invoice').append(data.html);
                    $('.submit').attr('disabled',false);
                    checkMedicine.push(data.id);
                    checkalltotal();
                    caclculatetotalBYprecent($('.discountpre').val(),$('#totals').val());
                    }
                },
            });
        }
    }
    // calculate
    function checkalltotal(){
            var arr = document.getElementsByClassName("total_price_final");
            var tot=0;
            for(var i=0;i<arr.length;i++){
                if(parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            document.getElementById('totals').value = tot;
    }
    function caclculatetotalBYprecent(val1,val2){
            var final= val1 * val2/100;
            $('.netamount').val($('#totals').val()-final)
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

        // delete
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
            url:'{{ url("bill-lab")}}',
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
   

    // end
});
</script>
@endsection