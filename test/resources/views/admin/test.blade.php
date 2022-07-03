@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
          
            @if (!empty(Helper::getpermission('_tests--create')) )
                <a href="javascript:viod();"  data-toggle="modal" data-target="#createdept"
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Test</a>
            @endif

        </div>
        <div class="mt-5 tables">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Test Type</th>
                        <th>Department</th>
                        <th>Fees</th>
                        <th>Author</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($test as $row)
                        <tr id="row{{$row->test_id}}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->test_type }}</td>
                            <td>{{ $row->department_name }}</td>
                            <td>{{ $row->fees }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                            @if (!empty(Helper::getpermission('_tests--delete')) )
                                <a data-delete="{{$row->test_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                            @endif
                            @if (!empty(Helper::getpermission('_tests--edit')) )
                                <a data-data="{{$row->test_type}}" data-datadep="{{$row->dep_id}}"  data-fee="{{$row->fees}}" data-toggle="modal" data-target="#editdept" data-id="{{$row->test_id}}" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
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
                    <h6 class="modal-title">Test Register</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="createform">
                        <div class="form-group">
                            <label>Test Type</label>
                            <input name="test_type" type="text" class="form-control" placeholder="Test Type" >
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-control">
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($dep as $item)
                                   <option value="{{$item->dep_id}}">{{$item->department_name}}</option>     
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fees</label>
                            <input name="fees" type="number" class="form-control" placeholder="Fees " >
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Add Test</button>
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
                    <h6 class="modal-title">Test Edit</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="editform">
                        <div class="form-group">
                            <label>Test Type</label>
                            <input name="test_type" type="text" class="form-control" placeholder="Test Name" id="type" >
                            <input type="hidden" name="test_id" id="test_id">
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-control" id="dep">
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($dep as $item)
                                   <option value="{{$item->dep_id}}">{{$item->department_name}}</option>     
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fees</label>
                            <input name="fees" type="number" class="form-control" id="fee" placeholder="Fees " >
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Edit Test</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Tests</li>
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
            url: "{{url('test')}}",
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
      var data=$(this).attr('data-data');
      var data2=$(this).attr('data-datadep');
      var id =$(this).attr('data-id');
      var fee =$(this).attr('data-fee');
        $('#dep').val(data2);   
        $('#type').val(data);   
        $('#test_id').val(id); 
        $('#fee').val(fee);   

    });

    $("#editform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("test_update")}}',
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
                    url:'{{url("test")}}/'+id,
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
                      'Test has related data first delete Test data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
    </script>

@endsection
