@extends('layouts.admin')
@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />

@endsection
@section('title') Admission Bill @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            

                @if (!empty(Helper::getpermission('_admissionBilling--create')))
                <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                   class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Generate Bill</a>
                @endif

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main"
                id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bill No</th>
                        <th>Admission Date</th>
                        
                        <th>Patient Name</th>
                        <th>Department</th>
                        <th>Doctor</th>
                        <th>Operation Type</th>
                        <th>Deposit Amount</th>

                        <th>Discharged Date</th>
                        <th>Discharged By</th>
                       
                        <th>Author</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($ad as $row)
                        <tr id="row{{ $row->admission_id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->bill_number }}</td>
                            <td>{{ $row->admission_date }}</td>
                            <td>{{ $row->p_name . ' ' . $row->p_last_name }}</td>
                            <td>{{$row->department_name}}</td>
                            <td>{{$row->e_name . ' ' . $row->e_last_name}}</td>
                            <td>{{$row->operate_type}}</td>
                            <td>{{$row->deposit_amount}}</td>
                            @if(!empty($row->discharge_date))
                            <td>{{$row->discharge_date}}</td>
                            <td>{{$row->discharged_by}}</td>
                           @else
                           <td>N/A</td>
                           <td>N/A</td>
                            @endif
                            <td>{{ $row->email }}</td>
                            <td>
                                @if (!empty($row->discount)) {{ $row->discount . '%' }}
                                @else {{ 'N/A' }} @endif
                            </td>

                            <td> @php $total=Helper::getadmissionBill_total($row->admission_id)@endphp {{ $total }}</td>

                            <td>
                                <a  data-surgery="{{$row->operate_type}}"  data-discount="{{ $row->discount }}" data-id="{{ $row->admission_id }}"
                                    data-bill="{{ $row->bill_number }}" data-patient="{{ $row->p_name . ' ' . $row->p_last_name }}"
                                    data-department="{{ $row->department_name }}"
                                    data-docter="{{ $row->e_name . ' ' . $row->e_last_name }}" 
                                    data-date="{{ $row->admission_date }}"
                                    data-deposit="{{$row->deposit_amount}}"
                                    
                                    class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed"
                                    data-toggle="modal">Add Charges</a>
                                    
                         

                                 <a data-toggle="modal" data-target="#print_modal"
                                    class="btn btn-success btn-sm text-white mr-2 print_slip "
                                    data-surgery="{{$row->operate_type}}" 
                                     data-discount="{{ $row->discount }}"
                                      data-id="{{ $row->admission_id }}"
                                    data-bill="{{ $row->bill_number }}" data-patient="{{ $row->p_name . ' ' . $row->p_last_name }}"
                                    data-department="{{ $row->department_name }}"
                                    data-docter="{{ $row->e_name . ' ' . $row->e_last_name }}" 
                                    data-date="{{ $row->admission_date }}"
                                    data-deposit="{{$row->deposit_amount}}"
                                    >Print Bill</a>
                                @if (!empty(Helper::getpermission('_admissionBilling--discharge')))
                                    @if(empty($row->discharge_date))
                                    <a data-id="{{ $row->admission_id }}"
                                        class="btn btn-purple btn-sm text-white mr-2  discharge">Discharge</a>
                                    @endif
                                @endif
                               
                                @if (!empty(Helper::getpermission('_admissionBilling--delete')))
                                  <a data-delete="{{ $row->admission_id }}"
                                    class="btn btn-danger btn-sm text-white mr-2 delete1">Delete</a>
                                @endif

                                @if (!empty(Helper::getpermission('_admissionBilling--edit')))
                                <a data-toggle="modal" data-target="#editBilldept" data-id="{{ $row->admission_id }}"
                                    class="btn btn-info btn-sm text-white mr-2 edit_bills">Edit</a> 
                                @endif

                                


                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
           <span class="float-right"> {{$ad->links()}}</span>
        </div>
    </div>


    {{-- models --}}
    <!-- LARGE MODAL -->
    <div id="createdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Generate Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="createform">
                        <div class="form-group billll">
                            <label>Bill Number</label>
                            <input type="text" readonly name="bill_number" class="form-control"
                                value="@php $max=helper::getadmissionBillNo()@endphp {{ $max }}">
                        </div>
                        <div class="form-group">
                            <label>Admission date</label>
                            <input type="date" class="form-control" name="admission_date">
                        </div>
                        <div class="form-group">
                            <label>Patient Name</label>
                            <select name="patient_name" class=" pat select2">
                                <option value="" selected disabled>select</option>
                                @foreach ($patient as $item1)
                                    <option value="{{ $item1->patient_id }}">
                                        {{ $item1->f_name . ' ' . $item1->l_name . ' P-00' . $item1->patient_idetify_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="dep_id" class="dep_id">
                        <input type="hidden" name="emp_id" class="emp_id">
                        <input type="hidden" name="operate_type" class="operate_type">
                        <input type="hidden" name="id" class="id">

  

                        <div class="form-group">
                            <label>Deposit Amount</label>
                            <input type="number" min="0" class="form-control" name="deposit_amount" placeholder="Amount">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-left">Generate</button>

                        </div>
                    </form>

                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="editBilldept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger">
                        <ul id="error"></ul>
                    </div>

                    <form method="post" id="edit_bill_form">
                        <div class="form-group">
                            <label>Bill Number</label>
                            <input type="text" readonly name="bill_number" class="form-control" id="bill_number123123">
                            <input type="hidden" name="bill_id" class="form-control" id="bill_id123123">

                        </div>
                        <div class="form-group">
                            <label>Admission date</label>
                            <input type="date" class="form-control" name="admission_date" id="admission_date123123">
                        </div>
                        <div class="form-group">
                            <label>Patient Name</label>
                            <select name="patient_name" class=" pat select2" id="patient_123123">
                                <option value="" selected disabled>select</option>
                                @foreach ($patient as $item1)
                                    <option value="{{ $item1->patient_id }}">
                                        {{ $item1->f_name . ' ' . $item1->l_name . ' P-00' . $item1->patient_idetify_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Deposit Amount</label>
                            <input type="number" min="0"  class="form-control" name="deposit_amount" placeholder="Amount" id="deposit123123">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-left">Edit</button>

                        </div>
                    </form>

                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="addroom" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Room to Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="editform">
                        
                        <div class="form-group">
                            <label>Room</label>
                            <select name="room" class="form-control rooms">
                                <option value="" selected disabled>select</option>
                                @foreach ($room as $item3)
                                <option value="{{ $item3->room_id }}">{{ $item3->room_type.' '.$item3->room_number }}</option>
                                @endforeach
                            </select>
                         </div>
                         <input type="hidden" name="room_info" id="room_info">
                          <input type="hidden" name="bill_id" class="bill_id" >
                      
                        <div class="form-group">
                            <label>Room Fees</label>
                            <input type="text" min="1" name="room_fees"  readonly class="room_fees form-control">
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="number" name="duration"  class="duration form-control">
                        </div>

                        <div class="form-group">
                            <label>Total</label>
                            <input type="number" name="total"  readonly class="total_duration form-control">
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
    <div id="addcharge" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Charges to Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="chargeform">
                        
                        <div class="form-group">
                            <label>Charges type</label>
                            <input type="text" name="charge_type" class="form-control">
                        </div>
                        <input type="hidden" name="bill_id" class="bill_id" >
                
                        <div class="form-group">
                            <label>Charges Description</label>
                            <input type="text" name="charge_description" class="form-control">
                        </div>
                
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" min="0"  name="amount" class="form-control">
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
    <div id="editcharge" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Charges to Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="chargeform_edit">
                        
                        <div class="form-group">
                            <label>Charges type</label>
                            <input type="text" name="charge_type" class="form-control" id="charges_type123">
                        </div>
                        <input type="hidden" name="bill_idss" class="bill_idss" >
                
                        <div class="form-group">
                            <label>Charges Description</label>
                            <input type="text" name="charge_description" class="form-control" id="charge_description123">
                        </div>
                
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" min="0"  name="amount" class="form-control" id="amount123">
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
    <div id="addMed" class="modal fade">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content ">
                <div class="modal-header ">
                    <h6 class="modal-title">Add charges to bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger">
                        <ul id="error"></ul>
                    </div>           
                    
                    @if (!empty(Helper::getpermission("_addadmissionBillValue")))
                    <button class="btn btn-blue float-right mr-1"  data-toggle="modal" data-target="#addcharge">Add charges</button>
                    <button class="btn btn-success float-right mr-1" data-toggle="modal" data-target="#addroom" >Add Room</button>
                    @endif

                    <br><br>
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label>Bill #: <strong id="bill_no"></strong></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group float-right">
                                <label>Date: <strong id="bill_date"></strong></label>
                            </div>
                        </div>
                    </div>
                    <div class="row p-4 table-sm table">
                        <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="10%">Patient Name</th>
                                    <td width="15%"><strong id="patient_name"></strong></td>
                                    <th width="10%">Doctor Name</th>
                                    <td width="15%"><strong id="docter_name"></strong></td>
                                    <th width="10%">Operation Type</th>
                                    <td width="15%"><strong id="operation_type"></strong></td>
                                    <th width="10%">Department</th>
                                    <td width="15%"><strong id="department"></strong></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="row p-4">
                        <table class="printablea4 table" id="testreport" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%">Charges Type</th>
                                    <th>Charges description</th>
                                    <th>Amount </th>
                                    <th style="text-align: center">Action</th>

                                </tr>
                            <tbody id="hdata">

                            </tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex">
                        <div style="width:50%">
                            <form id="discount" method="post">
                                <div class="input-group">
                                    <input type="number" name="discount" min="0" class="form-control"
                                        placeholder="Discount % ..." required  max="{{Auth()->user()->discountp}}">
                                    <input type="hidden" name="bill_id" class="bill_id">
                                    <span class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Discount %</button>
                                    </span>
                                </div>
                            </form>

                        </div>
                        <div style="width:50%">
                            <table align="" class="printablea4" style="width: 40%; float: right;">
                                <tbody>
                                    
                                    <tr>
                                        <th >Deposit Amount</th>
                                        <td id="deposit"></td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td  id="totals"></td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <td  class="discount"></td>
                                    </tr>
                                    <th>Discount %</th>
                                    <td  class="discountpre"></td>
                                    </tr>
                                    <tr>
                                        <th>Net Amount</th>
                                        <td  class="netamount"></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>

        </div><!-- modal-body -->
       </div>
    </div><!-- MODAL DIALOG -->

    <div id="print_modal" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Bill Medicine</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20" id="divtoprint">
                   <img src="{{url('public/payslip.png')}}" width="100%" alt="">
                   <h5>Admission Bill</h5>
                   <div style="display:flex;margin-top: 20px">
                    <div style="width:50%;text-align: left">
                        <div class="form-group ">
                            <label>Bill #:  <strong id="bill_no1"></strong></label>
                        </div>
                    </div>
                    <div style="width: 50%;text-align:right">
                        <div class="form-group float-right">
                            <label>Date:   <strong id="bill_date1"></strong></label>
                        </div>
                    </div>
                </div>
            <div class="row p-4 table-sm table " style="margin-top: 20px"> 
                <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
                    <tbody><tr>
                        <th>Patient Name</th>
                        <td><label id="patient_name1"></label></td>
                        <th>Doctor Name</th>
                        <td><label id="docter_name1"></label></td>

                    </tr>
                    <tr>
                        <th>Operation Type</th>
                        <td><label id="operation_type2"></label></td>
                        <th>Department</th>
                        <td><label id="department1"></label></td>
                    </tr>
            
                </tbody>
              </table>
           </div>
           <div style="margin-top: 20px">
            <table class="printablea4 table"  width="100%">
                <tbody>
                <tr>
                    <th align="left">Charages Type</th>
                    <th align="left">Charges Description</th>
                    <th align="left">Amount </th>
                </tr>
                <tbody id="hdata1">

                </tbody>

             </tbody>
          </table>
           </div>
           <div class="d-flex" style="margin-top: 20px">
              <div style="width:100%">
                <table align="" class="printablea4" style="width: 40%; float: right;">
                <tbody>
                    <tr>
                        <th >Deposit Amount</th>
                        <td id="deposit1"></td>
                    </tr>

                    <tr>
                    <th >Total</th>
                    <td  id="totals1"></td>
                    </tr>
                    <tr>
                    <th>Discount</th>
                    <td class="discount1"></td>
                    </tr>
                    <th>Discount %</th>
                    <td  class="discountpre1"></td>
                    </tr>
                    <tr>
                    <th>Net Amount</th>
                    <td class="netamount1"></td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
           </div>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

</div>



@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Admission Biling</li>
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

    <script>
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
            columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
          }
       },
       {
           extend: 'print',
           footer: false,
           exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
            }
       },
               
    ],
    } );
    
    $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');

        $('.select2').select2({width: '100%',color: '#384364'});
        $(".alert").css('display', 'none');

    </script>

  <script>
        $('body').on("click",'.print_slip',function() {
        $('.bill_id').val($(this).attr('data-id'));
        $('#bill_no1').html($(this).attr('data-bill'));
        $('#patient_name1').html($(this).attr('data-patient'));
        $('#department1').html($(this).attr('data-department'));
        $('#docter_name1').html($(this).attr('data-docter'));
        $('#bill_date1').html($(this).attr('data-date'));
        $('#data-total1').html($(this).attr('data-total'));
        $('#operation_type2').html($(this).attr('data-surgery'));
        $('#deposit1').html($(this).attr('data-deposit'));
        var htmldata="";
        $.ajax({
                url:'{{ url("bill_add_info_detail")}}/'+$(this).attr('data-id'),
                type: 'get',
                success: function (data) {
                    $('#hdata').html("");
                    if(data.info !=""){
                        for (let i = 0; i < data.info.length; i++) {
                            
                            htmldata+='<tr id="row'+data.info[i].bill_info+'"><td width="20%">'+ data.info[i].charges_type+'</td>\
                            <td>'+ data.info[i].charge_description+'</td>\
                            <td>'+ data.info[i].amount+'</td>'
                                
                            $('#hdata1').html(htmldata);
                        }
                        $('#totals1').html(data.totals);
                        var discount=data.discount*data.totals/100;
                        $('.discount1').html(discount);
                        $('.discountpre1').html(data.discount+'%');
                        $('.netamount1').html(data.totals-discount);  
                        setTimeout(function(){ printDiv(); },500);
                                                  
                        }else{
                            $('#hdata1').html('<tr><td>No record data found</td></tr>');
                            $('#totals1').html("N/A");
                            $('.discountpre1').html("N/A");
                            $('.netamount1').html("N/A");
                            $('.discount1').html("N/A");
                            setTimeout(function(){ printDiv(); },500);
                        }
                },
            });
        
      });
      function printDiv() {
            var divToPrint=document.getElementById('divtoprint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            $('.modal').modal('hide');
            setTimeout(function(){newWin.close();},10);

        }
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
            url: "{{ url('admission-bill') }}",
            type: 'POST',
            data: formData,
            success: function(data) {
                $(".alert").css('display', 'none');
                $('.table-main').load(document.URL + ' .table-main');
                $('.billll').load(document.URL +  ' .billll');
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

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $('.pat').change(function() {
        $.ajax({
            url: "{{ url('surger_data') }}/"+$(this).val(),
            type: 'get',
            success: function(data) {
            
                console.log(data);
                $('.dep_id').val(data[0].dep_id);
                $('.emp_id').val(data[0].emp_id);
                $('.operate_type').val(data[0].type);
                $('.id').val(data[0].procedure_id+data[0].surgery_id);
            },
            error: function(data) {
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $('body').on('click','.addMedicine',function() {
        $('.bill_id').val($(this).attr('data-id'));
        $('#bill_no').html($(this).attr('data-bill'));
        $('#patient_name').html($(this).attr('data-patient'));
        $('#department').html($(this).attr('data-department'));
        $('#docter_name').html($(this).attr('data-docter'));
        $('#operation_type').html($(this).attr('data-surgery'));
        $('#bill_date').html($(this).attr('data-date'));
        $('#data-total').html($(this).attr('data-total'));   
        $('#deposit').html($(this).attr('data-deposit'));   
                 
            
            var htmldata="";
            $.ajax({
                url:'{{ url("bill_add_info_detail")}}/'+$('.bill_id').val(),
                type: 'get',
                success: function (data) {
                    $('#hdata').html("");
                    for (let i = 0; i < data.info.length; i++) {
                        htmldata+='<tr id="row'+data.info[i].bill_info+'"><td width="20%">'+ data.info[i].charges_type+'</td>\
                            <td>'+ data.info[i].charge_description+'</td>\
                            <td>'+ data.info[i].amount+'</td>\
                            <td align="center">\
                                @if (!empty(Helper::getpermission("_deleteadmissionBillValue")))<a data-delete="'+data.info[i].bill_info+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                @if (!empty(Helper::getpermission("_editadmissionBillValue")))<a data-data="'+data.info[i].bill_info+'" data-toggle="modal" data-target="#editcharge" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>\@endif
                                </td>\
                            </tr>'; 
                            
                            $('#hdata').html(htmldata);
                    }
                    $('#totals').html(data.totals);
                    var discount=data.discount*data.totals/100;
                    $('.discount').html(discount);
                    $('.discountpre').html(data.discount+'%');
                    $('.netamount').html(data.totals-discount);

                },
            });
    });

    $('.rooms').change(function() {
        $.ajax({
            url: "{{ url('getRoomFees') }}/"+$(this).val(),
            type: 'get',
            success: function(data) {
                $('.room_fees').val(data.room_fees);                      
                $('#room_info').val(data.room_type+' '+data.room_number);                  

            },
            error: function(data) {
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $("#editform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("add_room_to_bill")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                var htmldata="";
            $.ajax({
                url:'{{ url("bill_add_info_detail")}}/'+$('.bill_id').val(),
                type: 'get',
                success: function (data) {
                    $('#hdata').html("");
                    for (let i = 0; i < data.info.length; i++) {
                        htmldata+='<tr id="row'+data.info[i].bill_info+'"><td width="20%">'+ data.info[i].charges_type+'</td>\
                            <td>'+ data.info[i].charge_description+'</td>\
                            <td>'+ data.info[i].amount+'</td>\
                            <td align="center">\
                                @if (!empty(Helper::getpermission("_deleteadmissionBillValue")))<a data-delete="'+data.info[i].bill_info+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                @if (!empty(Helper::getpermission("_editadmissionBillValue")))<a data-data="'+data.info[i].bill_info+'" data-toggle="modal" data-target="#editcharge" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>\@endif
                                </td>\
                            </tr>'; 
                            
                            $('#hdata').html(htmldata);
                    }
                    $('#totals').html(data.totals);
                    var discount=data.discount*data.totals/100;
                    $('.discount').html(discount);
                    $('.discountpre').html(data.discount+'%');
                    $('.netamount').html(data.totals-discount);

                },
            });


                $('#addroom').modal('hide');
                $('#editform')[0].reset();
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });

            },
            error:function(data){
                $(".alert12").find("ul").html('');
                $(".alert12").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert12").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("#chargeform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("bill_add_charges")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                var htmldata="";
                $.ajax({
                    url:'{{ url("bill_add_info_detail")}}/'+$('.bill_id').val(),
                    type: 'get',
                    success: function (data) {
                        $('#hdata').html("");
                        for (let i = 0; i < data.info.length; i++) {
                            htmldata+='<tr id="row'+data.info[i].bill_info+'"><td width="20%">'+ data.info[i].charges_type+'</td>\
                                <td>'+ data.info[i].charge_description+'</td>\
                                <td>'+ data.info[i].amount+'</td>\
                                <td align="center">\
                                    @if (!empty(Helper::getpermission("_deleteadmissionBillValue")))<a data-delete="'+data.info[i].bill_info+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                @if (!empty(Helper::getpermission("_editadmissionBillValue")))<a data-data="'+data.info[i].bill_info+'" data-toggle="modal" data-target="#editcharge" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>\@endif
                                    </td>\
                                </tr>'; 
                                
                                $('#hdata').html(htmldata);
                        }
                        $('#totals').html(data.totals);
                        var discount=data.discount*data.totals/100;
                        $('.discount').html(discount);
                        $('.discountpre').html(data.discount+'%');
                        $('.netamount').html(data.totals-discount);

                    },
                });
                $('#addcharge').modal('hide');
                $('#chargeform')[0].reset();
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });

            },
            error:function(data){
                $(".alert12").find("ul").html('');
                $(".alert12").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert12").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $('.duration').keyup(function() {
        var fees=$('.room_fees').val();
        var du = $(this).val();
        $('.total_duration').val(du*fees);
    });
    $('body').on('click','.edit_medicine',function() {
        $('.bill_idss').val($(this).attr('data-data'));
        
        $.ajax({
            url:'{{ url("bill_edit_charges/edit")}}/'+$(this).attr('data-data'),
            type: 'get',
            success: function (data) {
            $('#charges_type123').val(data.charges_type);
            $('#charge_description123').val(data.charge_description);
            $('#amount123').val(data.amount);
            },
        });

    });
    $("#chargeform_edit").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("bill_edit_charges")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                var htmldata="";
                $.ajax({
                    url:'{{ url("bill_add_info_detail")}}/'+$('.bill_id').val(),
                    type: 'get',
                    success: function (data) {
                        $('#hdata').html("");
                        for (let i = 0; i < data.info.length; i++) {
                            htmldata+='<tr id="row'+data.info[i].bill_info+'"><td width="20%">'+ data.info[i].charges_type+'</td>\
                                <td>'+ data.info[i].charge_description+'</td>\
                                <td>'+ data.info[i].amount+'</td>\
                                <td align="center">\
                                    @if (!empty(Helper::getpermission("_deleteadmissionBillValue")))<a data-delete="'+data.info[i].bill_info+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                @if (!empty(Helper::getpermission("_editadmissionBillValue")))<a data-data="'+data.info[i].bill_info+'" data-toggle="modal" data-target="#editcharge" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>\@endif
                                    </td>\
                                </tr>'; 
                                
                                $('#hdata').html(htmldata);
                        }
                        $('#totals').html(data.totals);
                        var discount=data.discount*data.totals/100;
                        $('.discount').html(discount);
                        $('.discountpre').html(data.discount+'%');
                        $('.netamount').html(data.totals-discount);

                    },
                });
                $('#editcharge').modal('hide');
                $('#chargeform_edit')[0].reset();
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });

            },
            error:function(data){
                $(".alert12").find("ul").html('');
                $(".alert12").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert12").find("ul").append('<li>'+value+'</li>');
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
                    type:'get',
                    url:'{{url("bill_delete_charges/")}}/'+id,
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
                      'Server Error',
                      'error'
                    )
                    }
                });
            }
        })
              
});

$('body').on('click','.delete1',function(){  
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
                    url:'{{url("admission-bill/")}}/'+id,
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
                      'Bill has related data please delete related data first',
                      'error'
                    )
                    }
                });
            }
        })
              
});

$('body').on('click','.edit_bills',function() {
        
        $.ajax({
            url:'{{ url("admission-bill")}}/'+$(this).attr('data-id')+'/edit',
            type: 'get',
            success: function (data) {
            $('#bill_number123123').val(data.bill_number);
            $('#admission_date123123').val(data.admission_date);
            $('#patient_123123').val(data.patient_id);
            $('#patient_123123').trigger("change");
            $('#deposit123123').val(data.deposit_amount);
            $('#bill_id123123').val(data.admission_id);            
            },
        });

});

$('#edit_bill_form').submit(function(e) {
    e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("admission-bill_update")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                $('.table-main').load(document.URL + ' .table-main');
                $('#editBilldept').modal('hide');
                $('#edit_bill_form')[0].reset();
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });

            },
            error:function(data){
                $(".alert12").find("ul").html('');
                $(".alert12").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert12").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
});

$('body').on('click','.discharge',function() {
        
        $.ajax({
            url:'{{ url("admission-bill/discharge")}}/'+$(this).attr('data-id'),
            type: 'get',
            success: function (data) {
                return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
            },
        });
        

});


$('#discount').submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('admission-bill-discount')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                var htmldata="";
                $.ajax({
                    url:'{{ url("bill_add_info_detail")}}/'+$('.bill_id').val(),
                    type: 'get',
                    success: function (data) {
                        $('#hdata').html("");
                        for (let i = 0; i < data.info.length; i++) {
                            htmldata+='<tr id="row'+data.info[i].bill_info+'"><td width="20%">'+ data.info[i].charges_type+'</td>\
                                <td>'+ data.info[i].charge_description+'</td>\
                                <td>'+ data.info[i].amount+'</td>\
                                <td align="center">\
                                    @if (!empty(Helper::getpermission("_deleteeadmissionBillValue")))<a data-delete="'+data.info[i].bill_info+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                    @if (!empty(Helper::getpermission("_editeadmissionBillValue")))<a data-data="'+data.info[i].bill_info+'" data-toggle="modal" data-target="#editcharge" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>\@endif
                                    </td>\
                                </tr>'; 
                                
                                $('#hdata').html(htmldata);
                        }
                        $('#totals').html(data.totals);
                        var discount=data.discount*data.totals/100;
                        $('.discount').html(discount);
                        $('.discountpre').html(data.discount+'%');
                        $('.netamount').html(data.totals-discount);

                    },
                });
                $('#createform')[0].reset();
                    return $.growl.notice({
                    message: data.success,
                    title: 'Success !',
                });
                $('#discount')[0].reset();
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

  
    </script>
@endsection
