@extends('layouts.admin')

@section('css')
<link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />

@endsection
@section('title') Employees @endsection

@section('content')
<div class="pb-3">
    <div class="float-right btn-list ">
        @if (!empty(Helper::getpermission('_employee--create')))       
         <a href="{{url('employees/create/option')}}" class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Employee</a>
        @endif
</div>
</div>
<br>

    <div class="row ">
        @foreach ($emp as $row)
            
        <div class=" col-xl-4 col-md-6 " id="row{{$row->emp_id}}">
            <div class="card text-center">
                <div class="ml-auto">
                <div class="btn-group mt-2 mr-2 mb-2 float-right">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        @if (!empty(Helper::getpermission('_employee--edit')))       
                        <li><a href="{{route('employees.edit',$row->emp_id)}}">Edit</a></li>
                        @endif
                        @if (!empty(Helper::getpermission('_employee--delete')))       
                        <li><a href="#" class="delete" data-delete="{{$row->emp_id}}">Delete</a></li>                  
                        @endif
                    </ul>
                </div>
            </div>

                @if(!empty($row->photo)) 
                <img src="{{url('storage/app')}}/{{$row->photo}}" alt="img" class="" style="height:200px;object-fit:contain;width:100%">
                @else
                <img src="{{asset('public/empty.png')}}" alt="img" class="" style="height:200px;object-fit: contain;width:100%">
                @endif
                <div class="card-body">
                    <h3 class="mb-3">{{$row->f_name.' '. $row->l_name}}</h3>
                    <p style="margin-bottom:0px">{{$row->position}}</p>
                    <p style="margin-bottom:0px">{{$row->department_name}}</p>
                    <p>Employee ID:{{'PMS-EMP00'.$row->emp_identify_id}}</p>

                    @if ($row->emp_identify_id % 2) 
                        
                    @if (!empty(Helper::getpermission('_employee--view')))       
                    <a href="{{route('employees.show',$row->emp_id)}}"  class="btn btn-primary">-Read More</a>
                    @endif

                    @else
                    @if (!empty(Helper::getpermission('_employee--view')))       
                    <a href="{{route('employees.show',$row->emp_id)}}" class="btn btn-secondary">-Read More</a>
                    @endif
                    @endif
                </div>
            </div>
        </div><!-- COL-END -->
        @endforeach
        
    </div>

{{-- models --}}

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Employee</li>
@endsection


@section('jquery')

<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js')}}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>



@if(session()->has('notif'))
<script>
      $.growl.notice({
        message: "{{session()->get('notif')}}",
        title: 'Success !',
        position: {
            from: "top",
            align: "left"
        },
    });
  
</script>       
@endif

<script>
    $('body').on('click','.delete',function(){  
     var id=$(this).attr('data-delete');
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
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
            $.ajax({
                    type:'DELETE',
                    url:'{{url("employees")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your record has been deleted.',
                      'success'
                    )
                    $('#row'+id).hide(1500);
                    },
                    error:function(error){
                    Swal.fire(
                      'Faild!',
                      'Employee record has related data first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
</script>

@endsection
