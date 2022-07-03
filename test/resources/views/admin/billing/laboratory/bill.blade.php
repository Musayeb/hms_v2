@extends('layouts.admin')

@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #example_processing{
     z-index: 100000;
 }
</style>

@endsection
@section('title') Laboratory Bill @endsection
@section('direct_btn')
<a href="{{url('bill-lab')}}"><button class="btn btn-outline-primary">Today's Bills</button>
@endsection
@section('content')
    <div class="card p-3">
        <div class="btn-list ">

            
            @if (!empty(Helper::getpermission('_laboratoryBilling--create')))
                   <a href="{{url('laboratory_pos')}}" 
                  class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Generate Bill</a>
            @endif

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bill No</th>
                        <th>Patient Name</th>
                        <th>Author</th>
                        <th>Discount</th>                     
                        <th>Total</th>    
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_date12">
                </tbody>
            </table>

        </div>
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

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Laboratory Biling</li>
@endsection


@section('jquery')
    <script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>


    {{-- end permission --}}

<script>
     $(document).ready(function() {
         $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
          var table=  $('#example').DataTable({
                dom: 'Blfrtip',
                processing: true,
                serverSide: true,
                "pageLength": 400,
                "bLengthChange": false,
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 8;
                            doc.defaultStyle.width = "*";

                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'print',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                  

                ],
                "order": [[ 1, 'desc' ]],
              
                ajax: "{{ url('bill-lab') }}",
                columns: [
                    {
                        data:null
                    },
                    {
                        data: 'bill_no'
                    },
                    {
                        data: 'patient_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'discount',
                        "render": function (data, type, row) {
                            if (row.discount <= 0) {
                                return 'N/A';
                            }else {
                                return row.discount;
                            }
                    }
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: 'date',
                        render: function (data, type, row) {//data
                        return moment(row.date).format('YYYY/MM/DD h:m:s a');
                    },
                    },
                    {
                        "mData": null,
                        "bSortable": false,
                        "mRender": function(o) {
                            return '<a data-id="' + o.bill_id +
                                '" class="btn btn-danger btn-sm text-white mr-2 delete2 " style="display:none"><i class="fa fa-trash fa-lg"></i></a>\
                               <a href="{{url("bill-lab")}}/'+o.bill_id +'/update" style="display:none" class="btn btn-info btn-sm text-white mr-2 edit_bi " data-toggle="tooltip" Title="Update"><i class="fa fa-edit fa-lg"></i></a>\
                                <a class="print_slip btn btn-sm btn-warning " data-toggle="modal" data-target="#print_modal" data-id="'+o.bill_id +'"  title="Print"><i class="fa fa-print"></i></a>';
                        }
                    }

                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    var index = iDisplayIndex +1;
                    $('td:eq(0)',nRow).html(index);
                    return nRow;
                },
                "drawCallback": function( settings ) {
                    @if (!empty(Helper::getpermission("_laboratoryBilling--delete")))
                    $('.delete2').show();
                   @endif
                   @if (!empty(Helper::getpermission("_laboratoryBilling--edit")))
                   $('.edit_bi').show();
                   @endif
                },
                
            });
            
            $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
   
            $('body').on('click','.delete2',function(){  
                var id =$(this).attr('data-id');
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
                                url:'{{url("bill-lab")}}/'+id,
                                success:function(data){ 
                                Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success');
                                $('#example').DataTable().ajax.reload()

                                // $('#row'+id).hide(1500);
                                },
                                error:function(error){
                                Swal.fire(
                                'Faild!',
                                'Bill has related data please delete related data first',
                                'error'
                                )
                                }
                            });
                        }
                    })
                        
            });
            $('body').on('click','.print_slip',function(){
                var val= $(this).attr('data-id');
                printDivData(val);
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
   
    });

</script>

@endsection
