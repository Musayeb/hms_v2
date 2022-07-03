@extends('layouts.admin')
@section('title') Roles @endsection

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />

    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
         .select2-selection--multiple {
            background:#f5f6fa !important;
            padding:8px !important;
        }
    </style>

@endsection
@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            {{-- @if (!empty(Helper::getpermission('_roles--create'))) --}}
                <a href="javascript:viod();"  data-toggle="modal" data-target="#permission_add"
                class="pull-right btn btn-primary d-inline modal_add"><i class="ti-plus"></i> &nbsp;Add New Role</a>
            {{-- @endif --}}
        </div>
        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Permissions</th>
                        <th>Created Date</th>
                        @if (!empty(Helper::getpermission('_roles--delete')) || !empty(Helper::getpermission('_roles--edit')))
                        <th>Action</th>
                        @endif
                        
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter=1;
                    $mydata=0;
                    @endphp
                    @foreach ($roles as $row)
                         <tr id="row{{$row->roles_id}}">
                            <td>{{$counter++ }}</td>
                            <td>{{$row->roles_name}}</td>
                            <td class="d-flex flex-wrap"> 
                                @foreach (Helper::getroles($row->roles_id) as $perm)
                                   <span class="badge badge-success mr-2 mb-2 "> {{$perm->name}}</span>
                                @endforeach
                            </td>
                            <td> {{$row->role_created}} </td>
                            @if (!empty(Helper::getpermission('_roles--delete')) || !empty(Helper::getpermission('_roles--edit')))
                                <td>     
                                    @if (!empty(Helper::getpermission('_roles--delete')))          
                                        <a  data-delete="{{$row->roles_id}}" class="btn btn-danger btn-sm text-white mr-2 role_delete">Delete</a>
                                    @endif
                                    @if (!empty(Helper::getpermission('_roles--edit')))
                                        <a data-id="{{$row->roles_id}}" data-role="{{$row->roles_name}}"  data-toggle="modal" data-target="#permission_edit"  class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
                                    @endif
                                </td>
                            @endif
                           

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>



{{-- Add Role --}}
    <div id="permission_add" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Role</h6>
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
                            <label>Role Name</label>
                            <input name="role_name" type="text" class="form-control" placeholder="Role Name" autocomplete="off">
                        </div>
                        <div class="form-group mt-5">
                            <label>Permissions</label>&nbsp;&nbsp;<a type="button" class="btn btn-info btn-sm text-white all mr-2">Select all</a> <a type="button" class="btn btn-danger btn-sm text-white remove mr-2">Deselect All</a> 
                            <select class="js-multiple form-control" name="permissions[]" multiple="multiple" id="selected">
                                @foreach ($per_data as $row)
                                    <option value="{{$row->id}}" >{{$row->name}}</option>
                                @endforeach
                              </select>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Create Role</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{-- Add Role --}}


    {{-- Edit Add Role --}}
    <div id="permission_edit" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Role</h6>
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
                            <label>Role Name</label>
                            <input name="role_name" type="text" class="form-control" placeholder="Role Name" autocomplete="off" id="role_name">
                            <input name="role_hidden" type="hidden" class="form-control"  id="role_hidden">
                        </div>
                        <div class="form-group mt-4">
                            <label>Permissions</label>&nbsp;&nbsp;<a type="button" class="btn btn-info btn-sm text-white all mr-2">Select all</a> <a type="button" class="btn btn-danger btn-sm text-white remove mr-2">Deselect All</a> 
                            <select class="js-multiple form-control" name="permissions[]" multiple="multiple" id="permission_selection">
                                @foreach ($per_data as $row)
                                    <option value="{{$row->id}}" >{{$row->name}}</option>
                                @endforeach
                              </select>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Edit Role</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{-- End Edit Add Role --}}

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Roles</li>
@endsection

@section('jquery')
    <script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js')}}"></script>
    <script src="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
   $(".all").click(function () {
        $(".js-multiple option").each(function(){
            $(this).prop('selected', true).change();
        });
    });
    $(".remove").click(function () {
        $(".js-multiple option").each(function(){
            $(this).prop('selected', false).change();
        });
    });

        $('#example').DataTable();
        $(".alert").css('display','none');

        $('.modal_add').click(function(){
            $('.js-multiple').val(" ").change();
            $('.alert1').hide();
        })

        $(document).ready(function() {
            $('.js-multiple').select2({
                width:'100%',
            });
        });

        // Create Role
        $("#createform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url: "{{url('roles')}}",
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

        // Edit Role        
        $('body').on('click','.edit',function(){
            $('.js-multiple').val(" ").change();
            $('.alert1').hide();

            var id =$(this).attr('data-id');
            var role =$(this).attr('data-role');
            $('#role_hidden').val(id);
            $('#role_name').val(role);  
            
            $permission_url='{{ url("roles")}}/'+id+'/'+'edit';
            $.get( $permission_url, function( data ) {
                $('#permission_selection').empty();
                var ids = [];
                var len_all=data[0].permission_all.length;
                var len_query=data[0].permission_query.length;

                for(var i=0;i<len_all;i++){
                    $('#permission_selection').append('<option value="'+data[0].permission_all[i].id+'">'+data[0].permission_all[i].name+'</option>');
                }
                for(var j=0;j<len_query;j++){
                  ids.push(data[0].permission_query[j].id);
                }

                $('#permission_selection').val(ids).trigger('change');
            });
        });
        
        $("#editform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url:'{{ url("roles_update")}}',
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


        // Delete Role
        $('body').on('click','.role_delete',function(){  
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
                if (result.value) {$.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
                $.ajax({
                        type:'DELETE',
                        url:'{{url("roles")}}/'+id,
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