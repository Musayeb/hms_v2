@extends('layouts.admin')
@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />

<style>
    .selected1234{
        background-color: #97a6d6 !important;
    }
    #example_processing{
        z-index: 100000;
    }
</style>
@endsection
@section('title') OPDs @endsection
@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            @if (!empty(Helper::getpermission('_opd--create')))
            <a href="javascript:viod();" data-backdrop="static" data-toggle="modal" data-target="#create"
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New OPD</a>
            <a href="javascript:viod();" data-backdrop="static" data-toggle="modal" data-target="#createopdAppoinment"
                class="pull-right btn btn-primary d-inline mr-1"><i class="ti-plus"></i> &nbsp;Add OPD from Appoinments</a>
            @endif    
        </div>
        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th></th>
                        <th>PID</th>
                        <th>Patient </th>
                        <th>Age</th>
                        <th>Phone</th>
                        <th>Referred By</th> 
                        
                        <th>Author</th>
                        <th>Register Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="html_data">
                    {{-- @foreach ($opd as $row)
                        <tr id="row{{ $row->opd_id }}">
                            <td>{{ $opd->firstItem()+$loop->index }}</td>

                            <td>{{ 'OPD-' . $row->patient_id }}</td>
                            <td>{{ $row->o_f_name.' '.$row->o_l_name }}</td>
                            <td>{{ $row->age }}</td>
                            <td>{{ $row->phone }}</td>  
                            <td>@if(empty( $row->referred)) {{'N/A'}} @else {{ $row->referred }} @endif</td>  
                            <td>{{ $row->email }}</td>
                           <td><span data-toggle="tooltip" title="
                                {{Carbon\Carbon::parse($row->created_at)->diffForHumans()  }}" >
                               {{date('Y-m-d h:i:s a', strtotime($row->created_at)) }}
                            </span>
                          </td>  
                            <td>
                                <a data-delete="{{ $row->opd_id }}"
                                    class="btn btn-danger btn-sm text-white mr-2 delete d-none" class="Delete"><i class="fa fa-trash fa-lg"></i></a>
                                <a data-toggle="modal" data-target="#edit" data-id="{{ $row->opd_id }}"
                                    class="btn btn-info btn-sm text-white mr-2 edit d-none" title="Edit"><i class="fa fa-edit fa-lg"></i></a>
                                    <a 
                                        class="btn btn-primary btn-sm text-white mr-2 print"  
                                        data-patent="{{$row->o_f_name.' '.$row->o_l_name}}"
                                        data-age="{{$row->age}}"
                                        data-phone="{{$row->phone}}"
                                        data-no="{{'OPD-' . $row->patient_id}}"
                                        data-date="{{$row->created_at}}"
                                        data-department="{{$row->department_name}}" title="Print" onclick="print({{$row->opd_id}})" id="print{{ $row->opd_id }}" ><i class="fa fa-print fa-lg"></i>
                                    </a>
                                <a href="{{route('opd.show',$row->opd_id)}}" class="btn btn-success btn-sm text-white mr-2 d-none show_a" title="Show Detail"><i class="fa fa-eye fa-lg"></i></a>
                            </td>
                        </tr>
                    @endforeach --}}

                </tbody>
            </table>
            {{-- <span class="float-right"> {{$opd->links()}}</span> --}}

        </div>
    </div>

    <div id="create" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Create OPD</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
            
                    <form method="post" class="p-3 createform1">
                        <div class="alert alert1 alert-danger">
                            <ul id="error"></ul>
                        </div>
                        <div class="form-group">
                            <label>Patient FirstName</label>
                            <input name="first_name" type="text" class="form-control" placeholder="First Name" >
                        </div>

                        <div class="form-group">
                            <label>Patient LastName</label>
                            <input name="last_name" type="text" class="form-control"  placeholder="Last Name" >
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input name="age" type="text" class="form-control age"placeholder="Age" >
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input name="phone" type="text" class="form-control phone" placeholder="Phone number" >
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="" selected >select gender</option>
                                <option >Male</option>
                                <option >Female</option>
                                <option > Rather not say</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Date</label>
                            <input name="date" type="date" class="form-control "  placeholder="Date" >
                        </div>

                        <div class="form-group">
                            <label>Referred By</label>
                            <input name="referred_by" type="text" class="form-control "  placeholder="Referred By" >
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create OPD</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
   
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="createopdAppoinment" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add OPD From Appoinments</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
           
                        
                    <form method="post" class="createform1 p-3">
                        <div class="alert alert2 alert-danger"><ul id="error"></ul></div>
                        
                        <div class="form-group ">
                            <label>Appoinment Patient</label>
                            <div class="input-group " >
                              <select class="select2" name="patient">
                                  <option value="" selected disabled>Select</option>
                                  @foreach ($app as $item)
                                  <option value="{{$item->app_id}}" >{{$item->p_f_name.' '.$item->p_l_name}}|{{'APP-N-'.$item->app_number}}|{{$item->f_name.' '.$item->l_name}}</option>
                                      
                                  @endforeach
                              </select>

                            </div>
                        </div>
                    <input type="hidden" name="type" value="app">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="" selected >select gender</option>
                                <option >Male</option>
                                <option >Female</option>
                                <option > Rather not say</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create OPD</button>
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
                    <h6 class="modal-title">Edit OPD</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert3 alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="updateForm">
                        <div class="form-group">
                            <label>Patient FirstName</label>
                            <input name="first_name" type="text" class="form-control" placeholder="First Name" id="first_name1" >
                            <input type="hidden" id="opd_id" name="opd_id">
                        </div>

                        <div class="form-group">
                            <label>Patient LastName</label>
                            <input name="last_name" type="text" class="form-control"  placeholder="Last Name" id="last_name1" >
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input name="age" type="text" class="form-control age"placeholder="Age" id="age1" >
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input name="phone" type="text" class="form-control phone" placeholder="Phone number"  id="phone_number1">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control" id="gender1">
                                <option value="" selected >select gender</option>
                                <option >Male</option>
                                <option >Female</option>
                                <option > Rather not say</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Date</label>
                            <input name="date" type="date" class="form-control "  placeholder="Date"  id="date1">
                        </div>
   
                       <div class="form-group">
                            <label>Referred By</label>
                            <input name="referred_by" type="text" class="form-control "  placeholder="Referred By"  id="referred_by" >
                        </div>
                        
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Edit OPD</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

    <div id="print" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Print</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20" id="DivIdToPrint">
                   <div style="text-align: center"><img src="{{url('public/hlogo.png')}}" width="200" height="80" alt=""></div> 
                   <h4 style="text-align: center;margin-top:4px;margin-bottom:0px">Morwarid Medical Complex</h4>
                   <h4 style="text-align: center;margin-top:0px;">OPD</h4>
                <div style="text-align: center;border-bottom:1px solid black">
                    <div style="height: 70px;">
                        <div style=";color: #000; width: 48%;float: left;">   
                            <div style="text-align:left">
                                   <p>Patient Name : <strong id="p_reg-patient"></strong></p>
                                   <p>Age :<strong id="p_age"></strong></p>
                                   <p>Phone Number :<strong id="p_phone"></strong></p>
                               </div>
                            </div>
                            <div style="color: #000;width: 48%;float: right;">
                                <div style="text-align:left">
                                       <p>Register Date :<strong id="p_reg_date"></strong></p>
                                       <p style="margin-top: 4px">Patient Register Number :<strong id="p_reg-no"></strong></p>
                                   </div>
                                </div>  
                
                              
                        </div>
                     
                        <div class="qr" style="text-align: center">

                        </div>
                 </div>
                 <div class="text-center" style="height:590px;border-bottom:1px solid black"><span style="margin-top:4px">Impotant Note:....</span>
                 </div>
                   <div style="height:105px;overflow: hidden;margin-top:4px">
                
                    <div style="color: #000;height: 400px; width: 48%;float: left;">   
                        <div style="">
                               <p>Address: Opposite Haji Sahib Gul Karim Center, Next to Tribal Directorate , 1st Zone, Professor Morwarid Safi Curative Hospital</p>
                               <p>Hospital No.:+93 78 55555 44</p>
                               <p>Ambulance Number: +93 74 55555 44</p>
                               <p>Email:info@pmsmedicalcomplex.com</p>
                               <p>https://pmsmedicalcomplex.com</p>
                           </div>
                        </div>
                        <div style="color: #000;height: 400px;width: 48%;float: right;text-align:right">
                            <div style="">
                                   <p> آدرس: قبایلو ریاست تر څنګ حاجی صیب ګل کریم مرکز ته مخامخ، اوله ناحیه ، پروفیسر مروارید صافی معالجوی روغتون</p>
                                   <p>0093 785555544:روغتون اړیکه</p>
                                   <p>0093 745555544:امبولانس اړیکه</p>
                                   <p>info@pmsmedicalcomplex.com :ایمیل ادرس</p>
                                   <p>https://pmsmedicalcomplex.com</p>
                               </div>
                            </div>  
                  </div> 
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- MODAL DIALOG -->
    </div>
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">OPD</li>
@endsection
@section('jquery')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script src="{{asset('public/assets/plugins/select2/select2.full.min.js')}}"></script>
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

    <script>
        var emp="";
        $('.select2').select2({width: '100%',color: '#384364'});
    </script>

    
  <script>
        $(document).ready(function() {
            $('.phone').inputmask('(0)-999-999-999');
            $('.age').inputmask('999');
        });
    </script>
    <script>

        function print(id){
            Swal.fire({
                        title: 'Success!',
                        text: "Do You Want To Print ?",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#218838',
                        cancelButtonColor: '#4b4b4b',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                            url: '{{ url("printOPDFirst") }}/'+id,
                            type: 'get',
                            success: function(data) {
                                console.log(data.data.o_l_name);
                                $('#p_reg-patient').html(data.data.o_f_name+' '+data.data.o_l_name);
                                $('#p_age').html(data.data.age);
                                $('#p_phone').html(data.data.phone);
                                $('#p_reg_date').html(moment(data.data.created_at).format('YYYY-MM-DD h:mm:s a'));
                                $('#p_reg-no').html('OPD-'+data.data.patient_id);
                                $('.qr').html(data.qr);
                                $('#print').modal('show');
                                printDiv();

                            },
                            error: function(data) {

                            },
                            contentType: false,
                        });
                        }
                    });
        }
        $('#print').on('shown.bs.modal', function () {
            $('#print').modal('hide');
        });
        function printDiv() {
            var divToPrint=document.getElementById('DivIdToPrint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media print { body{hight: 100%;}}p, ul, ol {margin:0px}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},10);
            }

      
    </script>

    <script>

        $('.alert').hide();

        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
          var table=  $('#example').DataTable({
                dom: 'Blfrtip',
                processing: true,
                serverSide: true,
                "pageLength": 400,
                "bLengthChange": false,
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 8;
                            doc.defaultStyle.width = "*";

                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7]
                        }
                    },
                    {
                        extend: 'print',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7]
                        }
                    },
                  

                ],
              
                ajax: "{{ url('opd') }}",
                columns: [{
                        data: null
                    },
                    {
                        "mData": null,
                        "bSortable": false,
                        "mRender": function(o) {
                            return 'OPD-'+o.patient_id+'';
                        }
                    },
                    {
                        "mData": null,
                        "bSortable": false,
                        "mRender": function(o) {
                            return ''+o.o_f_name+' '+o.o_l_name+''; 
                        }
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'phone'
                    },
               
                    {
                        data: 'referred',
                        "render": function (data, type, row) {
                            if (row.referred <= 0) {
                                return 'N/A';
                            }else {
                                return row.referred;
                            }
                    }
                    },
                    {
                       data:'email', 
                    },
                    {
                        data: 'created_at',
                        render: function (data, type, row) {//data
                        return moment(row.created_at).format('YYYY-MM-DD h:mm:s a');

                    },
                    },

                    {
                        "mData": null,
                        "bSortable": false,
                        "mRender": function(o) {
                            return '<a data-delete="'+o.opd_id+'"class="btn btn-danger btn-sm text-white mr-2 delete d-none" class="Delete"><i class="fa fa-trash fa-lg"></i></a>\
                                <a data-toggle="modal" data-target="#edit" data-id="'+o.opd_id+'"class="btn btn-info btn-sm text-white mr-2 edit d-none" title="Edit"><i class="fa fa-edit fa-lg"></i></a>\
                                    <a class="btn btn-primary btn-sm text-white mr-2 print"  title="Print" onclick="print('+ o.opd_id +')" id="print'+o.opd_id+'" ><i class="fa fa-print fa-lg"></i>\
                                    </a>\
                                <a href="{{url('opd')}}/'+o.opd_id+'" class="btn btn-success btn-sm text-white mr-2 d-none show_a" title="Show Detail"><i class="fa fa-eye fa-lg"></i></a>';
                        }
                    }

                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    var index = iDisplayIndex +1;
                    $('td:eq(0)',nRow).html(index);
                    return nRow;
                },
                "drawCallback": function( settings ) {
             
                    @if (!empty(Helper::getpermission('_opd--delete')))
                        $('.delete').removeClass('d-none');
                    @endif      
                    @if (!empty(Helper::getpermission('_opd--edit')))
                            $('.edit').removeClass('d-none');
                    @endif     
                                                
                    @if (!empty(Helper::getpermission('_opd--view')))
                            $('.show_a').removeClass('d-none');
                    @endif  
                },

                order: [
                    [0, 'desc']
                ]
            });
            $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
    </script>
    <script>
        $(".createform1").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
            $.ajax({
                url: '{{ url("opd") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('#example').DataTable().ajax.reload();
                    $('#createopdAppoinment').modal('hide');
                    $('#create').modal('hide');
                    $('.createform1')[0].reset();
                    $.growl.notice({
                        message: data.msg,
                        title: 'Success !',
                    });
         
                    print(data.id);
                
                },
                error: function(data) {
                    $(".alert").find("ul").html('');
                    if($('#create').hasClass('show')==true){
                        $(".alert1").css('display', 'block');
                    }else{
                        $(".alert2").css('display', 'block');
                    }
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
        $('.deps').change(function() {
            id = ($(this).val());
            url = '{{ url('appoinments_get_position') }}/' + id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        Hdata = '<option value="" selected disabled>Select Doctor</option>';
                        for (var i = 0; i < data.length; i++) {
                            Hdata += '<option value="' + data[i].emp_id + '">' + data[i]
                                .f_name + ' ' + data[i]
                                .l_name + '</option>';
                            $(".pos").html(Hdata);
                        }
                        if(emp != ""){
                            $(".pos").val(emp).trigger('change');
                        }
                    } else {
                        $(".pos").html(
                            '<option value="" selected disabled>No Record Found</option>');
                    }
                },
                error: function() {}
            })

        });

        $('body').on('click','.edit',function() {
            $.ajax({
                type: 'get',
                url: '{{ url("opd") }}/'+$(this).attr('data-id')+'/'+'edit',
                success: function(data) {
                        $('#first_name1').val(data.o_f_name);
                        $('#last_name1').val(data.o_l_name);
                        $('#age1').val(data.age);
                        $('#phone_number1').val(data.phone);
                        $('#date1').val(data.date);
                        $('#gender1').val(data.gender);
                        $('#referred_by').val(data.referred);
                        $('#opd_id').val(data.opd_id);
                        
                },
                error: function() {}
            })

        });
        $("#updateForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp ' }});
            $.ajax({
                url: '{{ url("opd_update") }}',
                type: 'post',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');

                    $('#example').DataTable().ajax.reload();

                    $('#edit').modal('hide');
                    $('#updateForm')[0].reset();
                 
                    return $.growl.notice({
                        message: data.msg,
                        title: 'Success !',
                    });
                
                    print(data.id);
                     
                },
                error: function(data) {
                    $(".alert3").css('display', 'block');

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
                    url:'{{url("opd")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your recorde has been deleted.',
                      'success'
                    )
                    // $('#row'+id).hide(1500);
                    $('#example').DataTable().ajax.reload();
                    },
                    error:function(error){
                    Swal.fire(
                      'Faild!',
                      'Opd record has related data first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
    </script>
  
@endsection
