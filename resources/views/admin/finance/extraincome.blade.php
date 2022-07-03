@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection
@section('title') Extra Income @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add Income</a>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($extra as $row)
                        <tr id="row{{ $row->extra_id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->receiver }}</td>
                            <td>{{ $row->description }}</td>
                            <td>{{ $row->pay_amount }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->created_at }}</td>

                            <td>
                    
                                <a data-delete="{{ $row->extra_id }}"
                                    class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                                <a data-toggle="modal" data-target="#edit_modal" data-id="{{ $row->extra_id }}"
                                    class="btn btn-info btn-sm text-white mr-2 edit_bills">Edit</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="float-right">
            <span >{{$extra->links()}}</span>
        </div>

        </div>
    </div>
    <div id="createdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Income</h6>
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
                            <label>Receiver Person</label>
                            <input type="text" name="receiver" class="form-control" placeholder="Receiver Person">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5" class="form-control" placeholder="Description"></textarea>
                        </div>
                        <div class="form-group">
                            <label> Amount</label>
                            <input type="number" min="1" name="amount" class="form-control" placeholder="Amount">
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
                            <input type="text" name="receiver" id="person" class="form-control" placeholder="Receiver Person">
                            <input type="hidden" name="extra_id" id="cash_id">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5" id="description" class="form-control" placeholder="Description"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" min="1" name="amount" id="request_amount" class="form-control" placeholder="Amount">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-left">Update</button>

                        </div>
                    </form>

                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Finance</li>
    <li class="breadcrumb-item active" aria-current="page">Extra Income</li>
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
                        columns: [0, 1, 2, 3, 4, 5],
                    }
                },
                {
                    extend: 'print',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
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
                url: "{{ url('finance/extra-income') }}",
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
                url: "{{ url('finance/extra-income')}}/" + $(this).attr('data-id') + '/edit',
                type: 'get',
                success: function(data) {
                    $('#cash_id').val(data.extra_id);
                    $('#person').val(data.receiver);
                    $('#description').val(data.description);
                    $('#request_amount').val(data.pay_amount);
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
                url: "{{ url('finance/extra-income/update') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table-main').load(document.URL + ' .table-main');
                    
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
                        url: '{{ url("finance/extra-income") }}/' + id,
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

@endsection
