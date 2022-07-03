@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('title') Room Managment @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            
            @if (!empty(Helper::getpermission('_rooms--create')))
                <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                    class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New</a>
              @endif

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Type</th>
                        <th>Room Number</th>
                        <th>Room Fees</th>
                        <th>Note</th>
                        <th>Author</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($room as $row)
                        <tr id="row{{$row->room_id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->room_type }}</td>
                            <td>{{ $row->room_number }}</td>
                            <td>{{ $row->room_fees }}</td>
                            <td>@if (!empty($row->description)){{ $row->description }} @else {{'N/A'}} @endif 
                           </td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                            @if (!empty(Helper::getpermission('_rooms--delete')))
                                <a data-delete="{{$row->room_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>

                            @endif
                            @if (!empty(Helper::getpermission('_rooms--edit')))
                                <a data-note="{{$row->description}}" data-id="{{$row->room_id}}" data-room_type="{{$row->room_type}}" 
                                    data-room_number="{{$row->room_number}}"
                                  data-room_fees="{{$row->room_fees}}" 
                                   data-toggle="modal" data-target="#editdept" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>

                            @endif


                                
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>


    {{-- models --}}
    <!-- LARGE MODAL -->
    <div id="createdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Room Register</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="createform">
                        <div class="form-group">
                            <label>Room Type</label>
                            <select name="room_type" class="form-control">
                                <option value="" disabled selected>Select</option>
                                <option value="VIP Room">VIP Room</option>
                                <option value="General Room">General Room</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Room Number</label>
                            <input type="number" name="room_number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label >Room Fees</label>
                            <input type="number" name="room_fees" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <input type="text" name="note"  class="form-control">
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Add Room</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        </div>

                    </form>
                    
                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="editdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Room Info</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="editform">
                        <div class="form-group">
                            <label>Room Type</label>
                            <select name="room_type" class="form-control" id="room_type">
                                <option value="" disabled selected>Select</option>
                                <option value="VIP Room">VIP Room</option>
                                <option value="General Room">General Room</option>
                            </select>
                        </div>
                        <input type="hidden" name="room_id" id="room_id">
                        <div class="form-group">
                            <label >Room Number</label>
                            <input type="number" name="room_number" class="form-control" id="room_number">
                        </div>
                        <div class="form-group">
                            <label >Room Fees</label>
                            <input type="number" name="room_fees" class="form-control" id="room_fees">
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <input type="text" name="note"  class="form-control" id="note">
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Edit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Rooms</li>
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
            url: "{{url('room')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                $(".alert").css('display','none');
                $('.table').load(document.URL +  ' .table');
                $('#createdept').modal('hide');
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

    $('body').on('click','.edit',function(){
     $('#room_type').val($(this).attr('data-room_type'));
     $('#room_id').val($(this).attr('data-id')); 
     $('#room_number').val($(this).attr('data-room_number')); 
     $('#note').val($(this).attr('data-note')); 
     $('#room_fees').val($(this).attr('data-room_fees')); 
    });

    $("#editform").submit(function(e) {
        e.preventDefault();   
        var id=$('#dept_id').val();  

        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("room_update")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert1").css('display','none');
                $('.table').load(document.URL +  ' .table');
                $('#editdept').modal('hide');
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
                    url:'{{url("room")}}/'+id,
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
                      'Room has related data first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
    </script>

@endsection
