@extends('layouts.admin')

@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
<link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />

@endsection
@section('title') Patients Operations @endsection

@section('content')
<div class="card p-3">
    <div class="btn-list ">
        @if (!empty(Helper::getpermission('_surgery&Delivery--create')))
            <a href="{{url('create_surgery_record')}}"  class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New</a>
           @endif

    </div>
    <div class="mt-5 table-responsive tables">
        <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
            <thead>
                <tr>
                    <th>#</th>
                    <th> Bill No </th>
                    <th>Patient Name</th>
                    <th>Operation type</th>
                    <th>Operation Name</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $counter=1; @endphp
                @foreach ($operate as $row)
                    <tr id="row{{$row->patient_s_del_pro_id}}">
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row->operate_no }}</td>

                        <td>{{ $row->f_name.' '. $row->l_name }}</td>
                
                        <td>{{$row->type}}</td>
                        <td>
                            @if(!empty($row->surgery_id))
                            @php $surg=helper::getsurgery($row->patient_s_del_pro_id) @endphp
                            {{$surg[0]->surgery_name}}
                            @endif
                            @if (!empty($row->procedure_id))
                            @php $proc=helper::getprocedure($row->patient_s_del_pro_id) @endphp
                            {{$proc[0]->procedure_name}}
                            @endif
                            @if (empty($row->surgery_id) && empty($row->procedure_id))
                                @if($row->type=="normal delivery")
                                {{'Normal Delivery'}}
                                @else
                                {{$row->type}}
                                @endif
                            @endif
                        </td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->date }}</td>
                        <td>{{$newDateTime = date('h:i A', strtotime($row->time))}}
                        </td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                          @if (!empty(Helper::getpermission('_surgery&Delivery--delete')) )
                            <a data-delete="{{$row->patient_s_del_pro_id}}"  data-toggle="tooltip" title="Delete Bill" class="btn btn-danger btn-sm text-white mr-2 delete"><i class="fa fa-trash fa-lg"></i></a>
                        @endif
                        <a data-id="{{$row->patient_s_del_pro_id}}" data-toggle="tooltip" title="Print Bill" class="btn btn-primary btn-sm print_slip_b"><i class="fa fa-print fa-lg"></i></a>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <span class="float-right"> {{$operate->links()}}</span>

    </div>
</div>

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
@section('directory')
<li class="breadcrumb-item active" aria-current="page">Surgery & Delivery</li>

@endsection
@section('jquery')
<script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>


    <script >

$('#example').DataTable( {
        dom: 'Blfrtip',
        "bLengthChange": false,
        "pageLength": 50,
        "paging":false,  
        buttons: [
       {
           extend: 'pdf',
           footer: true,
           customize: function(doc) {
           doc.defaultStyle.fontSize = 8;
           doc.defaultStyle.width = "*";

           },     
           exportOptions: {
            columns: [0,1,2,3,4,5,6,7,8]
          }
       },
       {
           extend: 'print',
           footer: false,
           exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8]
            }
       },
               
    ],
    } );
    
    $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');

   $(document).ready(function() {

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
                    url:'{{url("surgery_registration")}}/'+id,
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
                      'Record has related data first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
    });
    $('body').on('click','.print_slip_b',function(){
        print_bill($(this).attr('data-id'));
    });
    function print_bill(id){
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