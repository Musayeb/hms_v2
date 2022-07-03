@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('title') Permissions @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            <a href="javascript:viod();"  data-toggle="modal" data-target="#permission_add"
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Permission</a>
        </div>
        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>permissions</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($permissions as $row)
                         <tr id="row{{$row->id}}">
                            <td>{{$counter++ }}</td>
                            <td> {{ $row->name }} </td>
                            <td> {{ $row->created_at }} </td>
                            <td>
                                <a data-delete="{{$row->id}}" class="btn btn-danger btn-sm text-white mr-2 permission_delete">Delete</a>
                                <a data-name="{{$row->name}}" data-toggle="modal" data-target="#permission_edit" data-id="{{$row->id}}" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
                            </td>
                
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>



{{-- Add User Modal  --}}
    <div id="permission_add" class="modal fade">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Permission</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="createform">
                        @csrf
                        <div class="form-group">
                            <label>Permission Name</label>
                            <input name="permission_name" type="text" class="form-control" placeholder="Permission Name" autocomplete="off">
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Create Permission</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{--End Add User Modal  --}}



    {{-- Edit Permission Modal  --}}
    <div id="permission_edit" class="modal fade">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Permission</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="editform" >
                        @csrf
                        <div class="form-group">
                            <label>Permission Name</label>
                            <input name="permission_name" type="text" class="form-control" placeholder="Permission Name" autocomplete="off" id="permission_name">
                            <input type="hidden" name="permission_id" id="permission_id">
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Edit Permission</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{--End Add Permission Modal  --}}


@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
@endsection


@section('jquery')
    <script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js')}}"></script>
    <script src="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js')}}"></script>


    <script>
        $('#example').DataTable();
        $(".alert").css('display','none');


        // Add Permission
        $("#createform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url: "{{url('permissions')}}",
                type: 'POST',
                data: formData,
                success: function (data) {
                    $(".alert").css('display','none');
                    $('.table').load(document.URL +  ' .table');
                    $('#permission_add').modal('hide');
                    $('#createform')[0].reset();
                        return $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                },
                error:function(data){
                    $(".alert").find("ul").html('');
                    $(".alert").css('display','block');
                    $.each( data.responseJSON.errors, function( key, value ) {
                        $(".alert").find("ul").append('<li>'+value+'</li>');
                    });     
        
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


        
 

        
        // Edit Permission
        $('body').on('click','.edit',function(){
            var id =$(this).attr('data-id');
            var name =$(this).attr('data-name');
            $('#permission_id').val(id);
            $('#permission_name').val(name);     
        });

        $("#editform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url:'{{ url("permissions_update")}}',
                type: 'post',
                data: formData,
                success: function (data) {
                    $(".alert1").css('display','none');
                    $('.table').load(document.URL +  ' .table');
                    $('#permission_edit').modal('hide');
                    $('#editform')[0].reset();
                        return $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
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



        // Delete Permission
        $('body').on('click','.permission_delete',function(){  
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
                        url:'{{url("permissions")}}/'+id,
                        success:function(data){ 
                        Swal.fire(
                        'Deleted!',
                        'Your recorde has been deleted.',
                        'success'
                        )
                        $('#row'+id).hide(1500);
                        },
                        error:function(error){
                        Swal.fire(
                        'Faild!',
                        'Ooops, something wrong appended.',
                        'error'
                        )
                        }
                    });
                }
            })
              
        });
        

    </script>

@endsection