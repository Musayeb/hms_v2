@extends('layouts.admin')
@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/time/bootstrap-datepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/time/jquery.timepicker.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection
@section('title') Blood Donation @endsection
@section('content')
    <div class="card p-3">
        <div class="btn-list ">
                
                @if (!empty(Helper::getpermission('_bloodDonation--create')))
                    <a href="javascript:viod();" data-backdrop="static" data-toggle="modal" data-target="#create"
                        class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Blood Donation</a>
                @endif

        </div>

        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Receiver Name</th>
                        <th>Receiver Phone</th>
                        <th>Blood Group</th>
                        <th>Gender</th>
                        <th>Donor Name</th>
                        <th>Donor Phone</th>
                        <th>Bag No</th>
                        <th>Amount (CC)</th>
                        <th>Author</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($bloodDonation as $row)
                        <tr id="row{{ $row->id }}">
                            <td >{{$counter++}}</td>
                            <td> {{$row->receiver_name}} </td>
                            <td> {{$row->receiver_phone}} </td>
                            <td> {{$row->blood_group}} </td>
                            <td style="text-transform:capitalize"> 
                                @if ($row->gender=="rather")
                                    Rather not say
                                @else
                                    {{$row->gender}}
                                @endif     
                            </td>
                            <td> {{$row->donor_name}} </td>
                            <td> {{$row->donor_phone}} </td>
                            <td>  {{$row->bag_no}} </td>
                            <td>  {{$row->amount}} </td>

                            
                            <td>
                                @php echo Helper::getBirthAuthor($row->user_id); @endphp
                            </td>
                            <td> {{$row->created_at}} </td>
                        
                                <td>
                                    @if (!empty(Helper::getpermission('_bloodDonation--delete')))
                                        <a  data-id="{{$row->id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                                    @endif
                                    @if (!empty(Helper::getpermission('_bloodDonation--edit')))
                                        <a data-toggle="modal" data-target="#edit" data-id="{{$row->id}}"  class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
                                    @endif
                                </td>

                            
                        </tr>
                    @endforeach

                </tbody>
            </table>
           <span class="float-right"> {{$bloodDonation->links()}}</span>

        </div>
    </div>

    <div id="create" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Blood Donation</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert  alert-danger">
                        <ul id="error"></ul>
                    </div>
                    <form method="post" id="createform">

                        <div class="form-row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label>Receiver Name</label>
                                    <input name="receiver_name" type="text" class="form-control" placeholder="Receiver name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Receiver Phone</label>
                                <input name="receiver_phone" type="text" class="form-control phone" placeholder="Receiver phone">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2" >
                            <label>Blood Group</label>
                            <select name="blood_group" class="form-control">
                                <option value="" selected disabled>Select blood group</option>
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="A-"> A-</option>
                                <option value="B-"> B-</option>
                                <option value="O+" > O+</option>
                                <option value="O-"> O-</option>
                                <option value="AB+"> AB+</option>
                                <option value="AB-"> AB-</option>
                            </select>
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

                        <div class="form-row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label>Donor Name</label>
                                    <input name="donor_name" type="text" class="form-control" placeholder="Donor name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Donor Phone</label>
                                <input name="donor_phone" type="text" class="form-control phone" placeholder="Donor phone">
                                </div>
                            </div>
                        </div>
                       

                        <div class="form-group mt-2">
                            <label>Bag No</label>
                            <input name="bag_no" type="number" class="form-control" placeholder="Bag no">
                        </div>
                        <div class="form-group mt-2">
                            <label>Amount</label>
                            <input name="amount" type="number" class="form-control" placeholder="Amount">
                        </div>
                        
                        <div class="modal-footer  mt-2">
                            <button type="submit" class="btn btn-primary">Create Blood Donation</button>
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
                    <h6 class="modal-title">Edit Birth</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="editform">
                        <div class="form-row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label>Receiver Name</label>
                                    <input name="receiver_name" id="receiver_name" type="text" class="form-control" placeholder="Receiver name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Receiver Phone</label>
                                <input name="receiver_phone" id="receiver_phone" type="text" class="form-control phone" placeholder="Receiver phone">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2" >
                            <label>Blood Group</label>
                            <select name="blood_group" id="blood_group" class="form-control">
                                <option value="" selected disabled>Select blood group</option>
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="A-"> A-</option>
                                <option value="B-"> B-</option>
                                <option value="O+" > O+</option>
                                <option value="O-"> O-</option>
                                <option value="AB+"> AB+</option>
                                <option value="AB-"> AB-</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="" selected >select gender</option>
                                <option value="male" >Male</option>
                                <option value="female" >Female</option>
                                <option value="rather" > Rather not say</option>
                            </select>
                        </div>

                        <div class="form-row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label>Donor Name</label>
                                    <input name="donor_name" id="donor_name" type="text" class="form-control" placeholder="Donor name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Donor Phone</label>
                                <input name="donor_phone" id="donor_phone" type="text" class="form-control phone" placeholder="Donor phone">
                                </div>
                            </div>
                        </div>
                       
                   
                        <div class="form-group mt-2">
                            <label>Bag No</label>
                            <input name="bag_no" id="bag_no" type="number" class="form-control" placeholder="Bag no">
                        </div>
                        <div class="form-group mt-2">
                            <label>Amount</label>
                            <input name="amount" id="amount" type="number" class="form-control" placeholder="Amount">
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




@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Blood Donation</li>
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
                url: '{{ url("blood-donation") }}',
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
            url = '{{ url("blood-donation") }}' + '/' + id + '/' + "edit";
            $.get(url, function(data) {
                $('#receiver_name').val(data.receiver_name);
                $('#receiver_phone').val(data.receiver_phone);
                $('#blood_group').val(data.blood_group);
                $('#gender').val(data.gender);
                $('#donor_name').val(data.donor_name);                  
                $('#donor_phone').val(data.donor_phone);
                $('#bag_no').val(data.bag_no);
                $('#amount').val(data.amount);

                

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
                url: '{{ url("blood-donation_update") }}',
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
                    url:'{{url("blood-donation")}}/'+id,
                    type:'Delete',
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
                      'Blood Donation has related data ',
                      'error'
                        )
                    }
                 });
                }
            })
              
        });


        // print code

        // $('body').on("click",'.print_slip',function() {
        //     id = $(this).attr('data-print');
        //     url = '{{ url("partial-payment-billing") }}' + '/' + id + '/' + "edit";
        //     $.get(url, function(data) {
        //         console.log(data);
        //         $('#print_doctor_name').val(data.doctor_name);
        //         $('#print_bill_number').val(data.bill_number);
        //         $('#print_bill_number_hidden').val(data.bill_number);
        //         $('#print_doctor_phone').val(data.doctor_phone_number);
        //         $('#print_patient_name').val(data.patient_name);                  
        //         $('#print_patient_phone').val(data.patient_phone_number);
        //         $('#print_department').val(data.department);
        //         $('#print_date').val(data.date);
        //         $('#print_services_charges').val(data.services_charges);
        //         $('#print_facility_charges').val(data.facility_charges);
        //         $('#print_description').val(data.description);
        //         $('#print_hidden_id').val(data.id);
        //     });

        // });
        // $('#print_modal').on('shown.bs.modal', function() {
        //     printDiv();
        //     $('#print_modal').modal('hide');
        // });
        // function printDiv() {
        //     var divToPrint = document.getElementById('divtoprint');
        //     var newWin = window.open('', 'Print-Window');
        //     newWin.document.open();
        //     newWin.document.write(
        //         '<html><head><style>@media  print { body{hight: 100%;}}</style></head> <body onload="window.print()">' +
        //         divToPrint.innerHTML + '</body></html>');
        //     newWin.document.close();
        //     setTimeout(function() {
        //         newWin.close();
        //     }, 10);
        // }

    </script>
@endsection
