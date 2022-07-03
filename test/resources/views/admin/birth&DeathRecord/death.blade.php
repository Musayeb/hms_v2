@extends('layouts.admin')
@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/time/bootstrap-datepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/time/jquery.timepicker.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection
@section('title') Death Record @endsection
@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            
                @if (!empty(Helper::getpermission('_death--create')))
                    <a href="javascript:viod();" data-backdrop="static" data-toggle="modal" data-target="#create"
                        class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Death</a>
                @endif

        </div>

        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>patient_name</th>
                        <th>Gender</th>
                        <th>Death Date</th>
                        <th>Death Time</th>
                        <th>Guardian</th>
                        <th>Report</th>
                        <th>Author</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($death as $row)
                        <tr id="row{{ $row->id }}">
                            <td >{{$counter++}}</td>
                            <td> {{$row->patient_name}} </td>
                            <td style="text-transform:capitalize"> 
                                @if ($row->gender=="rather")
                                    Rather not say
                                @else
                                    {{$row->gender}}
                                @endif     
                            </td>
                            <td> {{$row->death_date}} </td>
                            <td> {{$row->death_time}} </td>
                            <td> {{$row->guardian}} </td>
                            <td style="max-width:300px;" class="d-flex flex-wrap">
                                <span style="width:300px ;white-space: pre-wrap">{{$row->report}}</span>
                             </td>
                            <td>
                                @php echo Helper::getBirthAuthor($row->user_id); @endphp
                            </td>
                            <td> {{$row->created_at}} </td>
                            <td>
                                <a  data-print="{{$row->id}}" class="btn btn-success btn-sm text-white mr-2 print_slip"  data-toggle="modal" data-target="#print_modal" >Print</a>
                            @if (!empty(Helper::getpermission('_death--delete')))
                                <a  data-id="{{$row->id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                            @endif
                            @if (!empty(Helper::getpermission('_death--edit')))
                                <a data-toggle="modal" data-target="#edit" data-id="{{$row->id}}"  class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
                            @endif

                            </td>
                            
                        </tr>
                    @endforeach

                </tbody>
            </table>
           <span class="float-right"> {{$death->links()}}</span>

        </div>
    </div>

    <div id="create" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Death</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert  alert-danger">
                        <ul id="error"></ul>
                    </div>
                    <form method="post" id="createform">

                        <div class="form-group mt-2" id="refresh1212">
                            <label>Patient Name</label>
                            <input type="text"  name="patient_name" class="form-control" placeholder="Patient Name">
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="" selected >select gender</option>
                                <option value="male" >Male</option>
                                <option value="female" >Female</option>
                                <option value="rather" > Rather not say</option>
                            </select>
                        </div>

                        <div class="form-group  mt-2">
                            <label>Death Date</label>
                            <input name="death_date" type="date" class="form-control" placeholder="Death date">
                        </div>
                        <div class="form-group  mt-2">
                            <label>Death Time</label>
                            <input name="death_time" type="time" class="form-control" placeholder="Death time">
                        </div>

                        <div class="form-group">
                            <label>Guardian</label>
                            <input name="guardian" type="text" class="form-control" placeholder="Guardian">
                        </div>

                        <div class="form-group mt-2">
                            <label>Report</label>
                            <textarea name="report"class="form-control" rows="4" placeholder="Write Report"></textarea>
                        </div>
                        
                        <div class="modal-footer  mt-2">
                            <button type="submit" class="btn btn-primary">Create Death</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>




    <div id="edit" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Death</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="editform">
                        <div class="form-group mt-2" id="refresh1212">
                            <label>Patient Name</label>
                            <input type="text"  name="patient_name" class="form-control" placeholder="Patient Name" id="patient_name">
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control" id="gender">
                                <option value="" selected >select gender</option>
                                <option value="male" >Male</option>
                                <option value="female" >Female</option>
                                <option value="rather" > Rather not say</option>
                            </select>
                        </div>

                        <div class="form-group  mt-2">
                            <label>Death Date</label>
                            <input name="death_date" type="date" class="form-control" placeholder="Death date" id="death_date">
                        </div>

                        <div class="form-group  mt-2">
                            <label>Death Time</label>
                            <input name="death_time" type="time" class="form-control" placeholder="Death time" id="death_time">
                        </div>

                        <div class="form-group">
                            <label>Guardian</label>
                            <input name="guardian" type="text" class="form-control" placeholder="Guardian" id="guardian">
                        </div>

                        <div class="form-group mt-2">
                            <label>Report</label>
                            <textarea name="report"class="form-control" rows="4" placeholder="Write Report" id="report"></textarea>
                        </div>

                        <div class="modal-footer  mt-2">
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <button type="submit" class="btn btn-primary">Update Birth</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->

            </div>
        </div><!-- MODAL DIALOG -->
    </div>



    {{-- Print Modal --}}
    <div id="print_modal" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Certificate</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="divtoprint">
                    <img src="{{ url('public/death_certificate.png') }}" width="100%" alt="">
                    
                    <div class="print">

                    </div>
        

                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>




@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Death Record</li>
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

    <script src="{{ asset('public/assets/time/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('public/assets/time/jquery.timepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.phone').inputmask('(0)-999-999-999');
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
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            },
            {
                extend: 'print',
                footer: false,
                exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8]
                    }
            },
                    
            ],
            } );
            
            $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
        });
        $('.alert').hide();
    </script>

    <script>

        $("#createform").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                }
            });
            $.ajax({
                url: '{{ url("death-record") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table').load(document.URL + ' .table');
                    $('#create').modal('hide');
                    $('#createform')[0].reset();
                    $('#refresh1212').load(document.URL +' #refresh1212'); 
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


        $('body').on('click', '.edit', function() {
            id = $(this).attr('data-id');
            url = '{{ url("death-record") }}' + '/' + id + '/' + "edit";
            $.get(url, function(data) {
                $('#patient_name').val(data.patient_name);
                $('#gender').val(data.gender);
                $('#death_date').val(data.death_date);
                $('#death_time').val(data.death_time);
                $('#guardian').val(data.guardian);
                $('#report').val(data.report);                  
                $('#hidden_id').val(data.id);
                
            });
        });

        $("#editform").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '
                }
            });
            $.ajax({
                url: '{{ url("death-record_update") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                    $(".alert1").css('display', 'none');
                    $('.table').load(document.URL + ' .table');
                    $('#edit').modal('hide');
                    $('#editform')[0].reset();
                    return $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                },
                error: function(data) {
                    $(".alert1").find("ul").html('');
                    $(".alert1").css('display', 'block');
                    $.each(data.responseJSON.errors, function(key, value) {
                        $(".alert1").find("ul").append('<li>' + value + '</li>');
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


        $('body').on('click','.delete',function(){  
            var id =$(this).attr('data-id');
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
                            url:'{{url("death-record")}}/'+id,
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


        // print code

        $('body').on("click",'.print_slip',function() {
            id = $(this).attr('data-print');
            url = '{{ url("death_certificate_print") }}/'+ id;
            $.get(url, function(data) {
                $('.print').html(data);
                setTimeout(function(){ printDiv(); },500);
            });

        });
        function printDiv() {
            var divToPrint = document.getElementById('divtoprint');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write(
                '<html><head><style>@media  print { body{hight: 100%;} table, th, td { border: 0.5px solid black; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">' +
                divToPrint.innerHTML + '</body></html>');
            newWin.document.close();
            $('#print_modal').modal('hide');
            setTimeout(function(){newWin.close();},10);

            setTimeout(function() {
                newWin.close();
            }, 10);
        }

    </script>
@endsection
