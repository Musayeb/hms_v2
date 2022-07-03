@extends('layouts.admin')
@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css    " rel="stylesheet" />



@endsection
@section('title') Payrolls @endsection

@section('content')

    <div class="card">
        <div class="ml-auto d-block  m-3">
            <div class="float-right btn-list ">
                
               {{-- @if (!empty(Helper::getpermission('_payroll--create')))
                <a data-toggle="modal" data-backdrop="static" data-target="#modal"
                    class="pull-right btn btn-primary d-inline "><i class="ti-plus"></i> &nbsp;Process Payroll</a>
               @endif
            
                <a class="pull-right btn btn-primary d-inline mr-1 approval">Send Approval Request</a> --}}

            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table  table-sm table-bordered " id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Tax %</th>
                            <th>Tax</th>
                            <th>Net Salary</th>
                            <th>Deduction</th>
                            <th>Description</th>
                            <th>Year & Month</th>
                            <th>Issue Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter=1; @endphp
                        @foreach ($payroll as $row)
                            <tr id="row{{$row->pay_id}}">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $row->f_name . ' ' . $row->l_name }}</td>
                                <td>{{ $row->tax_precentage . '%' }}</td>
                                <td>{{ $row->tax_amount }}</td>
                                <td>{{ $row->net_salary }}</td>
                                <td>
                                    @if (empty($row->deduction)){{ 'N/A' }}
                                        @else{{ $row->deduction }} @endif
                                </td>
                                <td>
                                    @if (empty($row->deduction_description))
                                    {{ 'N/A' }} @else {{ $row->deduction_description }} @endif
                                </td>
                                <td>{{ $row->month_year }}</td>
                                <td><span data-toggle="tooltip" title="
                                    {{Carbon\Carbon::parse($row->created_at)->diffForHumans()  }}" >
                                {{date('Y-m-d h:i:s a', strtotime($row->created_at)) }}
                                </span>
                                </td>       
                                <td>
                                    <a  href="javascript:void(0);" data-id="{{ $row->pay_id }}" data-target="#print_modal" data-toggle="modal" class="btn btn-success btn-sm print_slip">print<a>
                                        @if (!empty(Helper::getpermission('_payroll-edit')))
                                        <a  href="javascript:void(0);" data-id="{{ $row->pay_id }}" data-target="#modal" data-toggle="modal" class="btn btn-primary btn-sm edit_p">Edit<a>
                                         @endif 
                                         @if (!empty(Helper::getpermission('_payroll-delete-info')))  
                                           <a  href="javascript:void(0);" data-id="{{ $row->pay_id }}" class="btn btn-danger btn-sm delete">Delete<a>
                                        @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- LARGE MODAL -->
    <div id="modal" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content h-100">
                <div class="modal-header pd-x-20">
                    <h5 class="modal-title">Edit Payroll</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-danger">
                    <ul id="error"></ul>
                </div>
                <form method="post" id="formpayroll">
                    @csrf
      
                    <div class="data p-4">
                        <div class="row pl-4 pr-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Basic Salary</label>
                                    <input type="text" class="form-control " readonly name="basic_salary" id="basicsalary">
                                    <input type="hidden" name="payroll_id" id="payroll_id">
                                </div>
                            </div>
                        </div>
                        <div class="row pl-4 pr-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tax %</label>
                                    <input type="text" class="form-control " readonly name="tax_precentage"
                                        id="tax_precentage">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tax</label>
                                    <input type="number" class="form-control " readonly name="tax_amount" id="tax_amount">
                                </div>
                            </div>
                        </div>
                        <div class="row pl-4 pr-4">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label>Deduction</label>
                                    <input type="number" class="form-control " name="deduction" id="deduction">
                                </div>
                            </div>
                        </div>
                        <div class="row pl-4 pr-4">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label>Deduction Description</label>
                                    <input type="text" class="form-control " name="deduction_description">
                                </div>
                            </div>
                        </div>
                        <div class="row pl-4 pr-4">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label>Net Salary</label>
                                    <input type="text" class="form-control " readonly name="net_salary" id="net_salary"
                                        min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-primary" >Process</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- MODAL DIALOG -->
    </div>




    <!-- LARGE MODAL CLOSED -->
    <div id="print_modal" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Print Slip</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20" id="divtoprint">
                    <img src="{{url('public/payslip.png')}}" width="100%" alt="">
                    <div class="print">

                    </div>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Payroll</li>
@endsection

@section('jquery')
<script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>

<script>
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
</script>

    <script>
        $(".alert").css('display', 'none');
        $("#formpayroll").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
            $.ajax({
                url: "{{ url('payroll_edit') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    if (!data.error == "") {
                        $(".alert").find("ul").html('');
                        $(".alert").css('display', 'block');
                        $(".alert").find("ul").append('<li>' + data.error + '</li>');
                    } else {
                        $(".alert").css('display', 'none');
                        $('.table').load(document.URL + ' .table');
                        $('#modal').modal('hide');
                        $('#formpayroll')[0].reset();
                        return $.growl.notice({
                            message: data.notif,
                            title: 'Success !',
                        });
                    }

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

    </script>
    <script>
    
        $('body').on('click','.edit_p',function() {
             var id=$(this).attr('data-id');
            $.ajax({
                url: "{{ url('payroll_edit_second') }}/"+id,
                type: 'get',
                success: function(data) {
                  $('#basicsalary').val(data.salary);
                  $('#tax_precentage').val(data.pay.tax_precentage);
                  $('#tax_amount').val(data.pay.tax_amount);
                  $('#deduction').val(data.pay.deduction);
                  $('#deduction_description').val(data.pay.deduction);
                  $('#net_salary').val(data.pay.net_salary);
                  $('#payroll_id').val(data.pay.pay_id);

                  
                },
            });
            
        });
        
        $('body').on('change','#status',function() {
            $value = $(this).val();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    $("#status_form").submit();
                }
            })
        });
        $("#status_form").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                }
            });
            $.ajax({
                url: "{{ url('payroll_status') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table').load(document.URL + ' .table');
                    $('#edit').modal('hide');
                    $('#status_form')[0].reset();

                    Swal.fire(
                        'Successfull!',
                        'Payroll status changed successfully.',
                        'success'
                    );
                    return $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                },
                error: function(data) {

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


     $('body').on('click','.approval',function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    $('.approval').html('Sending...');
                    $('.approval').attr('disabled',true);

                    $.ajax({
                        url: "{{ url('payroll_approval') }}",
                        type: 'get',

                        success: function(data) {
                            $('.approval').html('Send Approval Request');
                            $('.approval').attr('disabled',false);

                         

                            Swal.fire(
                                'Successfull!',
                                'Approval Request Sent successfully.',
                                'success'
                            );

                        },
                        error: function(data) {

                        },

                    });


                }
            });


        });

    </script>

<script>
    $('body').on("click",'.print_slip',function() {
        $.ajax({
            url:'{{ url("payroll_print")}}/'+$(this).attr('data-id'),
            type: 'get',
            success: function (data) {
                $('.print').html(data);
                setTimeout(function(){printDiv(); },5);          
            },
            error:function(){
              console.log('server error');
                
            },
        });
        
      });
        
      function printDiv() {
            $('.modal').hide();
            $('.modal-backdrop').hide();
            var divToPrint=document.getElementById('divtoprint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},4);
        }

  </script>

  <script>
              $('body').on('click', '.delete', function() {
            var id = $(this).attr('data-id');
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
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                        }
                    });

                    $.ajax({
                        type: 'get',
                        url: '{{ url("delete_payroll_detail_record") }}/' + id,
                        success: function(data) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            $('#row' + id).hide(1500);
                        },
                        error: function(error) {
                            Swal.fire(
                                'Faild!',
                                'Operation faild !',
                                'error'
                            )
                        }
                    });
                }
            })

        });
  </script>
@endsection
