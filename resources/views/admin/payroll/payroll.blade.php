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
                
               @if (!empty(Helper::getpermission('_payroll--create')))
                <a data-toggle="modal" data-backdrop="static" data-target="#modal"
                    class="pull-right btn btn-primary d-inline "><i class="ti-plus"></i> &nbsp;Process Payroll</a>
               @endif
            
                <a class="pull-right btn btn-primary d-inline mr-1 approval">Send Approval Request</a>

            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table  table-sm table-bordered " id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee FullName</th>
                            <th>Tax %</th>
                            <th>Tax</th>
                            <th>Net Salary</th>
                            <th>Deduction</th>
                            <th>Deduction Description</th>
                            <th>Year & Month</th>
                            <th>Author</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter=1; @endphp
                        @foreach ($pay as $row)
                            <tr>
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
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->created_at }}</td>
                                <td>
                                    @if ($row->status == 'Pending')
                                        <span class="badge badge-danger">{{ $row->status }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $row->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!empty(Helper::getpermission('_payroll-status-change')))
                                    <a  href="javascript:void(0);" data-backdrop="static" data-target="#edit"
                                    data-toggle="modal" data-id="{{ $row->pay_id }}"   class="btn btn-purple btn-sm edit_pay">Change Status<a>
                                     @endif   

                                    <a  href="javascript:void(0);" data-id="{{ $row->pay_id }}" data-target="#print_modal" data-toggle="modal" class="btn btn-success btn-sm print_slip">print<a>


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <span class="float-right"> {{$pay->links()}}</span>

            </div>
        </div>
    </div>

    <!-- LARGE MODAL -->
    <div id="modal" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content h-100">
                <div class="modal-header pd-x-20">
                    <h5 class="modal-title">Process Payroll</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-danger">
                    <ul id="error"></ul>
                </div>
                <form method="post" id="formpayroll">
                    @csrf
                    <div class="row mt-1 pl-4 pr-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Month</label>
                                <input type="month" name="month" class="form-control" id="month">
                            </div>
                        </div>
                        <div class="col-md-6 d-none" id="emp">
                            <div class="form-group">
                                <label>Employee</label>
                                <select name="employee" class="form-control" id="employee">
                                    <option value="" selected disabled>Select Employee</option>
                                    @foreach ($emp as $item)
                                        <option value="{{ $item->emp_id }}">
                                            {{ $item->f_name . ' ' . $item->l_name . ' ' . $item->position }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="data d-none">
                        <div class="row pl-4 pr-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Basic Salary</label>
                                    <input type="text" class="form-control " readonly name="basic_salary" id="basicsalary">
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
                        <button type="submit" id="submit" class="btn btn-primary" disabled="true">Process</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- MODAL DIALOG -->
    </div>


    <!-- MESSAGE MODAL -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">Payroll Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="status_form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Change Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="" selected disabled>Select Status</option>
                                <option value="Approved">Approved</option>
                                <option value="Pending">Pending</option>
                            </select>
                            <input type="hidden" id="id" name="id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MESSAGE MODAL CLOSED -->

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
            columns: [0,1,2,3,4,5,6,7,8,9,10]
          }
       },
       {
           extend: 'print',
           footer: false,
           exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10]
            }
       },
               
    ],
    } );
    
    $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
</script>
    <script>
        var amount = "";
        $('#month').change(function() {
            $('#emp').removeClass('d-none');
        });
        $('#employee').change(function() {
            var emp_id = $(this).val();
            $('#submit').attr('disabled', false);
            var tax = "";
            var Taxamount = "";
            $.ajax({
                url: "{{ url('payroll') }}" + '/' + emp_id,
                type: 'get',
                success: function(data) {
                    if (data.salary <= 5000) {
                        tax = 0;
                    } else if (data.salary > 5000 && data.salary < 12500) {
                        tax = 2;
                    } else if (data.salary > 12500 && data.salary < 100000) {
                        tax = 10;
                    } else {
                        tax = 20;
                    }
                    Taxamount = data.salary * tax / 100;
                    amount = data.salary - Taxamount;
                    $('.data').removeClass('d-none');
                    $('#basicsalary').val(data.salary);
                    $('#tax_precentage').val(tax + '%');
                    $('#tax_amount').val(Taxamount);
                    $('#net_salary').val(amount);
                    $('#deduction').attr('max', amount);

                },
                error: function(data) {

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        $('#deduction').keyup(function(event) {
            var ded = $(this).val();
            var net = $('#net_salary').val();
            var total = amount - ded;
            $('#net_salary').val(total);
            if (event.keyCode == 8) {
                $('#net_salary').val(amount);
                if (!ded == "") {
                    var total = amount - ded;
                } else {
                    var total = amount;
                }
                $('#net_salary').val(total);
            }
        });

    </script>
    <script>
        $(".alert").css('display', 'none');
        $("#formpayroll").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                }
            });
            $.ajax({
                url: "{{ url('payroll') }}",
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
    
          $('body').on('click','.edit_pay',function() {
            $('#id').val($(this).attr('data-id'));
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
@endsection
