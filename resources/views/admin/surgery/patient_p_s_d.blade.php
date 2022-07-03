@extends('layouts.admin')

@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
<link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />

@endsection
@section('title') Patients Operations @endsection

@section('content')
<div class="card p-3">
    <div class="btn-list ">
        @if (!empty(Helper::getpermission('_surgery&Delivery--create')))
            <a href="javascript:viod();" data-toggle="modal" data-backdrop="static" data-target="#createdept" 
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New</a>
           @endif

    </div>
    <div class="mt-5 table-responsive tables">
        <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Patient Number</th>
                    <th>Departement</th>
                    <th>Doctor</th>
                    <th>Operation type</th>
                    <th>Operation Name</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $counter=1; @endphp
                @foreach ($operate as $row)
                    <tr id="row{{$row->patient_s_del_pro_id}}">
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row->f_name.' '. $row->l_name }}</td>
                        <td>{{'p-'. $row->patient_idetify_number }}</td>
                        <td>{{ $row->department_name }}</td>
                        <td>{{$row->emp_f_name.' '.$row->emp_l_name}}</td>
                        <td>{{$row->type}}</td>
                        <td>
                            @if(!empty($row->surgery_id))
                            @php $surg=helper::getsurgery($row->patient_s_del_pro_id) @endphp
                            {{$surg[0]->surgery_name}}
                            @endif
                            @if (!empty($row->procedure_id))
                            @php $proc=helper::getprocedure($row->patient_s_del_pro_id) @endphp
                            {{$proc[0]->procedure_name}}
                            @endif
                            @if (empty($row->surgery_id) && empty($row->procedure_id))
                                @if($row->type=="normal delivery")
                                {{'Normal Delivery'}}
                                @else
                                {{$row->type}}
                                @endif
                            @endif
                        </td>

                        <td>{{ $row->email }}</td>
                        <td>{{ $row->date }}</td>
                        <td>{{$newDateTime = date('h:i A', strtotime($row->time))}}
                        </td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                          @if (!empty(Helper::getpermission('_surgery&Delivery--delete')) )
                            <a data-delete="{{$row->patient_s_del_pro_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                        @endif

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <span class="float-right"> {{$operate->links()}}</span>

    </div>
</div>
<div id="createdept" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Registeration</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="alert alert-danger"><ul id="error"></ul></div>

                <form method="post" id="createform" class="p-3">
                    <div class="form-group">
                        <label>Patient</label>
                        <select name="patient" class="select2">
                            <option value="" disabled selected>Select Patient</option>
                                @foreach ($patient as $row)
                                    <option value="{{ $row->patient_id }}">{{ $row->f_name.' '.$row->l_name.' P-00'.$row->patient_idetify_number }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Operation Type</label>
                        <select name="type" class="form-control type">
                            <option value="" selected disabled>select type</option>
                            <option value="surgery">Surgery</option>
                            <option value="procedure">Procedure</option>
                            <option value="normal delivery">Normal Delivery</option>
                            <option value="direct admission">Direct Admission</option>

                        </select>
                    </div>
                    <div class="form-group d-none" id="d">
                        <label>Department Name</label>
                        <select name="department" class="form-control deps">
                            <option value=""  selected>Select Department</option>
                            @foreach ($dep as $row)
                                <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="doc">
                        <label>Doctor</label>
                        <select name="docter" class="form-control pos">
                        </select>
                    </div>
                    <div class="form-group d-none" id="surgery">
                        <label>Surgery</label>
                        <select name="surgery" id="surg" class="form-control">

                        </select>
                    </div>
                    <div class="form-group d-none" id="procedure">
                        <label>Procedure</label>
                        <select name="procedure" id="pro" class="form-control">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                       <input type="date" name="date" class="form-control"  >
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                       <input type="time" name="time" class="form-control "  >
                    </div>
                    <div class="modal-footer">
                       <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>

@endsection
@section('directory')
<li class="breadcrumb-item active" aria-current="page">Surgery & Delivery</li>

@endsection
@section('jquery')
<script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>


    <script >

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

   $(document).ready(function() {
        $(".select2").select2({width: '100%',color:'#384364'});
            $(".alert").css('display','none');
        
        $('.type').change(function () {
          var val= $(this).val();
           if(val=="normal delivery"){
            $('#d').removeClass('d-none');
            $('.deps').prop('selectedIndex',0);
           }else if(val=="procedure"){
            $('#d').removeClass('d-none');
            $('.deps').prop('selectedIndex',0);
           }else{
            $('#d').removeClass('d-none');
            $('.deps').prop('selectedIndex',0);
           }
        }); 
        $('.deps').change(function() {
           var dep=$(this).val();
           var type=$('.type').val();
           url = '{{ url("operation_reg_docters") }}/' +dep+'/'+type;
            var Hdata = "";
            var Hdata1 = "";
            var Hdata2 = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data.emp != '') {
                        $('#doc').removeClass('d-none');
                        Hdata = '<option value="" selected disabled>Select Doctor</option>';
                        for (var i = 0; i < data.emp.length; i++) {
                            Hdata += '<option value="' + data.emp[i].emp_id + '">' + data.emp[i]
                                .f_name + ' ' + data.emp[i]
                                .l_name + '</option>';
                            $(".pos").html(Hdata);
                        }
                    } else { $(".pos").html('<option value="" selected disabled>No Record Found</option>');}
                    if (data.pro != '') {
                        $('#procedure').removeClass('d-none');
                        $('#surgery').addClass('d-none');
                        Hdata1 = '<option value="" selected disabled>Select Procedure</option>';
                        for (var i = 0; i < data.pro.length; i++) {
                            Hdata1 += '<option value="' + data.pro[i].procedure_id + '">' + data.pro[i]
                                .procedure_name +'</option>';
                            $("#pro").html(Hdata1);
                        }
                    } else { $("#pro").html('<option value="" selected disabled>No Record Found</option>');}
                    if (data.sur != '') {
                        $('#procedure').addClass('d-none');
                        $('#surgery').removeClass('d-none');
                        Hdata2 = '<option value="" selected disabled>Select Surgery</option>';
                        for (var i = 0; i < data.sur.length; i++) {
                            Hdata2 += '<option value="' + data.sur[i].surgery_id + '">' + data.sur[i]
                                .surgery_name +'</option>';
                            $("#surg").html(Hdata2);
                        }
                    } else { $("#surg").html('<option value="" selected disabled>No Record Found</option>');}
                    if (data.normal != '') {
                        $('#procedure').addClass('d-none');
                        $('#surgery').addClass('d-none');
                        $('#surgery').prop('selectedIndex',0);
                        $('#procedure').prop('selectedIndex',0);
                    }
                    if (data.normal == '' &&  data.sur == '' && data.pro == '') {
                        $('#procedure').addClass('d-none');
                        $('#surgery').addClass('d-none');
                        $('#surgery').prop('selectedIndex',0);
                        $('#procedure').prop('selectedIndex',0);
                    }


                },
                error: function() {}
            })
        });
        $("#createform").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                }
            });
            $.ajax({
                url: '{{ url("surgery_registration") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table').load(document.URL + ' .table');
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
                    $('.modal').animate({
                        scrollTop: 0
                    }, '500');
                },
                cache: false,
                contentType: false,
                processData: false
            });
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
                    url:'{{url("surgery_registration")}}/'+id,
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
                      'Record has related data first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
    </script>
@endsection