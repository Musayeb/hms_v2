@extends('layouts.admin')
@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css    " rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/multipleselect/multiple-select.css') }}">

@endsection
@section('title') Process Payrolls @endsection

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
                            <th>Payroll Month</th>
                            <th>Author</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter=1; @endphp
                        @foreach ($pay as $row)
                            <tr id="row{{ $row->payroll_id }}">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $row->month_year }}</td>
                                <td>{{ $row->email }}</td>
                                <td><span data-toggle="tooltip" title="
                                    {{ Carbon\Carbon::parse($row->created_at)->diffForHumans() }}">
                                        {{ date('Y-m-d h:i:s a', strtotime($row->created_at)) }}
                                    </span></td>
                                <td>
                                    @if ($row->status == 'Pending')
                                        <span class="badge badge-danger">{{ $row->status }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $row->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!empty(Helper::getpermission('_payroll-status-change')))
                                        <a href="javascript:void(0);" data-backdrop="static" data-target="#edit"
                                            data-toggle="modal" data-id="{{ $row->payroll_id }}"
                                            class="btn btn-purple btn-sm edit_pay">Change Status<a>
                                    @endif

                                    <a href="{{ url('payroll') }}/{{ $row->payroll_id }}"
                                        class="btn btn-success btn-sm ">Show Details<a>
                                        @if (!empty(Helper::getpermission('_payroll-delete')))
                                            <a href="javascript:void(0);" data-id="{{ $row->payroll_id }}"
                                                class="btn btn-danger btn-sm  delete">Delete<a>
                                        @endif

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
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
                                <input type="month" name="month" class="form-control" id="month" required>
                            </div>
                        </div>
                        <div class="col-md-6 " id="emp">
                            <div class="form-group">
                                <label>Employee</label>
                                <select multiple="multiple" class="filter-multi" name="employee[]" required>
                                    <option value="" selected disabled>Select Employee</option>
                                    @foreach ($emp as $item)
                                        <option value="{{ $item->emp_id }}">
                                            {{ $item->f_name . ' ' . $item->l_name . ' ' . $item->position }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-primary">Process</button>
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
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Process Payroll</li>
@endsection
@section('jquery')
    <script src="{{ asset('public/assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/multipleselect/multi-select.js') }}"></script>
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
        $('.alert').hide();
    </script>
    <script>
        $('#example').DataTable({
            dom: 'Blfrtip',
            "bLengthChange": false,
            "pageLength": 50,
            "paging": false,
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.defaultStyle.width = "*";

                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'print',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },

            ],
        });

        $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
    </script>
    <script>
        $('body').on('click', '.edit_pay', function() {
            $('#id').val($(this).attr('data-id'));
        });
        $('body').on('change', '#status', function() {
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


        $('body').on('click', '.approval', function() {
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
                    $('.approval').attr('disabled', true);

                    $.ajax({
                        url: "{{ url('payroll_approval') }}",
                        type: 'get',

                        success: function(data) {
                            $('.approval').html('Send Approval Request');
                            $('.approval').attr('disabled', false);


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
                        type: 'DELETE',
                        url: '{{ url('payroll') }}/' + id,
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
