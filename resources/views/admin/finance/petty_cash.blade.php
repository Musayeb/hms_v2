@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection
@section('title') Daily Expenses @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">

            
            @if (!empty(Helper::getpermission('_dailyExpenses--create')))
                 <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                    class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add Expense</a>
            @endif

                <a class="pull-right btn btn-primary d-inline mr-1 approval">Send Approval Request</a>

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main"
                id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Person Name</th>
                        <th>Description</th>
                        <th>Total Amount</th>
                        <th>Author</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($cash as $row)
                        <tr id="row{{ $row->cash_id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->person_name }}</td>
                            <td>{{ $row->description }}</td>
                            <td>{{ $row->amount }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                                @if ($row->status=="Pending")
                                    <span class="badge badge-danger">{{$row->status}}</span>
                                @else
                                <span class="badge badge-success">{{$row->status}}</span>
                                @endif
                            </td>
                            <td>
                               
                                @if (!empty(Helper::getpermission('_dailyExpenses--edit')) || !empty(Helper::getpermission('_dailyExpenses--delete')))
                                    @if (!empty(Helper::getpermission('_dailyExpenses--delete')))
                                        <a data-delete="{{ $row->cash_id }}"
                                            class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                                    @endif
                                 @if (!empty(Helper::getpermission('_dailyExpenses--status-change')))
                                 <a href="javascript:void(0);" class="btn btn-success btn-sm status" data-backdrop="static" data-target="#status"
                                 data-toggle="modal" data-id="{{ $row->cash_id }}">Status</a>
 
                                 @endif
                                    @if (!empty(Helper::getpermission('_dailyExpenses--edit')))
                                        <a data-toggle="modal" data-target="#edit_modal" data-id="{{ $row->cash_id }}"
                                            class="btn btn-info btn-sm text-white mr-2 edit_bills">Edit</a>
                                    @endif
                                
                            @endif

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="float-right">
            <span >{{$cash->links()}}</span>
        </div>

        </div>
    </div>
    <div id="createdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Expense</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="createform">
                    

                        <div class="form-group">
                            <label>Person Name</label>
                            <input type="text" name="person_name" class="form-control" placeholder="Person Name">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5" class="form-control" placeholder="Withdrawal description"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Request Amount</label>
                            <input type="number" min="1" name="request_amount" class="form-control" placeholder="Amount">
                        </div>                            
                
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-left">ADD</button>
                        </div>
                    </form>

                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="edit_modal" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title"> Edit Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger">
                        <ul id="error"></ul>
                    </div>
              
                    <form method="post" id="editform">
              
                        <div class="form-group">
                            <label>Person Name</label>
                            <input type="text" name="person_name" id="person" class="form-control" placeholder="Person Name">
                            <input type="hidden" name="cash_id" id="cash_id">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5" id="description" class="form-control" placeholder="Withdrawal description"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Request Amount</label>
                            <input type="number" min="1" name="request_amount" id="request_amount" class="form-control" placeholder="Amount">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-left">Update</button>

                        </div>
                    </form>

                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div class="modal fade" id="status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">Purchase Medicines Status</h5>
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
                            <input type="hidden" id="idss" name="id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Finance</li>
    <li class="breadcrumb-item active" aria-current="page">Daily Expenses</li>
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

    <script>
        $('#example').DataTable({
            dom: 'Blfrtip',
            "bLengthChange": false,
            "pageLength": 50,
            'paging':false,
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.defaultStyle.width = "100%";

                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7],
                    }
                },
                {
                    extend: 'print',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7]
                    }
                },

            ],
        });
        $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
        $('.select2').select2({width: '100%',color: '#384364'});
        $(".alert").css('display', 'none');
    </script>

    <script>
    
        $('body').on('submit','#createform',function(e) {  

            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
            $.ajax({
                url: "{{ url('finance/daily_expenses') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table-main').load(document.URL + ' .table-main');
        
                    $('#bill1212').load(document.URL + ' #bill1212');
                    $('#createdept').modal('hide');
                    $('#createform')[0].reset();
                    return $.growl.notice({
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
        $('body').on("click",'.edit_bills',function() {
            $.ajax({
                url: "{{ url('finance/daily_expenses')}}/" + $(this).attr('data-id') + '/edit',
                type: 'get',
                success: function(data) {
                    $('#cash_id').val(data.cash_id);
                    $('#person').val(data.person_name);
                    $('#description').val(data.description);
                    $('#request_amount').val(data.amount);
                },
                error: function(data) {


                },
            });
        });

        $("#editform").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp'}});
            $.ajax({
                url: "{{ url('finance/daily_expenses_update') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table-main').load(document.URL + ' .table-main');
                    $('#bill1212').load(document.URL + ' #bill1212');

                    
                    $('#edit_modal').modal('hide');
                    $('#editform')[0].reset();
                    return $.growl.notice({
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

        $('body').on('click', '.delete', function() {
            var id = $(this).attr('data-delete');
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
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url("finance/daily_expenses") }}/' + id,
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
                                'Server Error',
                                'error'
                            )
                        }
                    });
                }
            })

        });

    </script>
<script>
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
                $(this).html('Sending....').prop('disabled', true);

                $.ajax({
                    url: "{{ url('dailyexpense_aproval') }}",
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
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
        $.ajax({
            url: "{{ url('dailyexpense_aproval_status') }}",
            type: 'POST',
            data: formData,
            success: function(data) {
                $(".alert").css('display', 'none');
                $('.table').load(document.URL + ' .table');
                $('#status').modal('hide');
                $('#status_form')[0].reset();

                Swal.fire(
                    'Successfull!',
                    data.success,
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
        $('body').on('click','.status',function() {
            $('#idss').val($(this).attr('data-id'));
        });
</script>
@endsection
