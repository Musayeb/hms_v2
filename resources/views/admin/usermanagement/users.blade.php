@extends('layouts.admin')
@section('title') Users @endsection

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            @if (!empty(Helper::getpermission('_users--create')))
                <a href="javascript:viod();"  data-toggle="modal" data-target="#user_add"
                    class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New User</a>
            @endif
        </div>
        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Discount</th>
                        <th>Created Date</th>
                        @if (!empty(Helper::getpermission('_users--delete')) || !empty(Helper::getpermission('_users--edit')) )
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($users as $row)
                         <tr id="row{{$row->id}}">
                            <td>{{$counter++ }}</td>
                            <td style="text-align: center"> 
                               @if (empty($row->profile_photo_path))
                                    <img src="{{url('public/assets/images/icon.jpg')}}" alt="user" style="width:50px;height:50px;border-radius:50px;" >
                               @else
                                    <img src="{{url('storage/app/avatar')}}/{{$row->profile_photo_path}}" alt="user" style="width:50px;height:50px;border-radius:50px;">
                               @endif
                            </td>
                            <td> {{ $row->name }} </td>
                            <td> {{ $row->email }} </td>
                            <td>
                                @php
                                    $role=Helper::getNameRole($row->role_id);
                                    if(!empty($role)){
                                        echo '<span class="badge badge-primary">'.$role[0]->name.'</span>';
                                    }else{
                                        echo 'No seleceted';
                                    }
                                @endphp
                            </td>
                            <td> {{ $row->discountp }}% </td>
                            <td> {{ $row->created_at }} </td>
    
                            <td>
                                @if (!empty(Helper::getpermission('_users--delete')) || !empty(Helper::getpermission('_users--edit')) )
                                    @if (!empty(Helper::getpermission('_users--delete')))
                                        <a data-delete="{{$row->id}}" class="btn btn-danger btn-sm text-white mr-2 user_delete">Delete</a>
                                    @endif
                                    @if (!empty(Helper::getpermission('_users--edit')))
                                        <a data-edit="{{$row->id}}" data-name="{{$row->name}}" data-email="{{$row->email}}" data-role="{{$row->role_id}}"  data-dis="{{$row->discountp}}" data-toggle="modal" data-target="#user_edit" class="btn btn-success btn-sm text-white mr-2 edit">Edit</a>
                                        <a data-id="{{$row->id}}"   data-toggle="modal" data-target="#reset_password" class="btn btn-info btn-sm text-white mr-2 reset">Reset Password</a>
                                    @endif
                                @endif
                            </td>
                 
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

{{-- Add User Modal  --}}
    <div id="user_add" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add User</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>
                    <form method="post" id="createform">
                        <div class="form-group">
                            <label>User Name</label>
                            <input name="user_name" type="text" class="form-control" placeholder="User Name"  autocomplete="off">
                        </div>

                        <div class="form-group mt-3">
                            <label>User Email</label>
                            <input name="user_email" type="email" class="form-control email" placeholder="User Email"  autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>User discount %</label>
                            <input name="discount" type="number" class="form-control " placeholder="Discount"  autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>User Password</label>
                            <input name="user_password" type="password" class="form-control password" placeholder="User Pasword"  autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>Password Confirm</label>
                            <input name="password_confirm" type="password" class="form-control confirm" placeholder="Password Confirm"  autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>User Role</label>
                            <select name="user_role" class="form-control" >
                                <option value="" selected disabled>Select User Role</option>
                                @foreach ($roles as $item)
                                   <option value="{{$item->id}}">{{$item->name}}</option>     
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Create User</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{--End Add User Modal  --}}

    {{-- Edit User Modal  --}}
    <div id="user_edit" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit User</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>
                    <form method="post" id="editform">
                        <div class="form-group">
                            <label>User Name</label>
                            <input name="user_name" type="text" class="form-control" placeholder="User Name" id="user_name" autocomplete="off">
                            <input name="user_id_hidden" type="hidden" class="form-control"  id="user_id_hidden" autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>User discount %</label>
                            <input name="discount" type="number" class="form-control " placeholder="Discount" id="dis"  autocomplete="off">
                        </div>

                        <div class="form-group mt-3">
                            <label>User Email</label>
                            <input name="user_email" type="email" class="form-control email" placeholder="User Email" id="user_email" autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>User Role</label>
                            <select name="user_role" class="form-control" id="user_role">
                                <option value="" selected disabled>Select User Role</option>
                                @foreach ($roles as $item)
                                   <option value="{{$item->id}}">{{$item->name}}</option>     
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer mt-3 mb-3">
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{--End Add User Modal  --}}

    {{-- Reset Password User Modal  --}}
    <div id="reset_password" class="modal fade">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Reset Password</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>
                    <form method="post" id="editpassword">
                        <div class="form-group mt-3">
                            <label>Now Password</label>
                            <input name="user_password"  type="password" class="form-control password" placeholder="Now Pasword" id="user_password" autocomplete="off">
                        </div>
                        <div class="form-group mt-3">
                            <label>Password Confirm</label>
                            <input name="password_confirm" type="password" class="form-control confirm" placeholder="Password Confirm" id="password_confirm" autocomplete="off">
                        </div>
                        <div class="modal-footer mt-3 mb-3">
                            <input name="reset_password_id"  type="hidden" class="form-control password"  id="reset_password_id" autocomplete="off">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>{{--End Reset Password User Modal  --}}


@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Users</li>
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
        
        $("#createform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url: "{{url('users')}}",
                type: 'POST',
                data: formData,
                success: function (data) {
                    $(".alert").css('display','none');
                    $('.table').load(document.URL +  ' .table');
                    $('#user_add').modal('hide');
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


        // Delelte User
        $('body').on('click','.user_delete',function(){  
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
                        url:'{{url("users")}}/'+id,
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


        $('body').on('click','.edit',function(){
            var id =$(this).attr('data-edit');
            $('#user_id_hidden').val(id);
            $('#user_name').val($(this).attr('data-name'));
            $('#user_email').val($(this).attr('data-email'));
            $('#user_role').val($(this).attr('data-role'));
            $('#dis').val($(this).attr('data-dis'));
        });
        $("#editform").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url:'{{ url("user_update")}}',
                type: 'post',
                data: formData,
                success: function (data) {
                    $(".alert1").css('display','none');
                    $('.table').load(document.URL +  ' .table');
                    $('#user_edit').modal('hide');
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


        $('body').on('click','.reset',function(){
            var id =$(this).attr('data-id');
            $('#reset_password_id').val(id);
        });
        $("#editpassword").submit(function(e) {
            e.preventDefault();   
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
            $.ajax({
                url:'{{ url("resetPassword")}}',
                type: 'post',
                data: formData,
                success: function (data) {
                    $(".alert1").css('display','none');
                    $('.table').load(document.URL +  ' .table');
                    $('#reset_password').modal('hide');
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

        function UpdatePreview(){
            $('#upload').attr('src', URL.createObjectURL(event.target.files[0]));
        };

        

    </script>

@endsection