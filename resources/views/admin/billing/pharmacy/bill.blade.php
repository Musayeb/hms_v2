@extends('layouts.admin')

@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{asset('public/assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />

@endsection
@section('title') Pharmacy Bill @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            
            @if (!empty(Helper::getpermission('_pharmacyBilling--create')))
                <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Generate Bill</a>
            @endif

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bill No</th>
                        <th>Patient Name</th>
                        <th>Patient Type</th>
                        <th>Patient Number</th>
                        <!-- <th>Department</th> -->
                        <!-- <th>Doctor Name</th> -->
                        <th>Author</th>
                        <th>Discount</th>                     
                        <th>Total</th>      
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_date12">
           
                    @foreach ($bill as $row)
                        <tr id="row2{{$row->bill_id }}">
                            <td>{{ $bill->firstItem()+$loop->index }}</td>
                            <td>{{ $row->bill_no }}</td>
                            <td>
                                {{$row->patient_name}}
                            </td>
                            <td>{{ $row->p_type}}</td>
                            <td>
                                @if($row->p_type=="OPD Patient")
                                opd-{{$row->p_identify}}
                                @elseif($row->p_type=="Registred Patient")
                                p-{{$row->p_identify}}
                                @else
                                N/A
                                @endif
                            </td>

                            <td>{{ $row->email }}</td>
                            <td>@if(!empty($row->discount)) {{ $row->discount.'%'}} @else {{'N/A'}} @endif</td>

                            <td> @php $total=Helper::getTotalpharmacyBill($row->bill_id)@endphp {{ $total }}</td>
                            <td><span data-toggle="tooltip" title="
                                {{Carbon\Carbon::parse($row->date)->diffForHumans()  }}" >
                            {{date('Y-m-d h:i:s a', strtotime($row->date)) }}
                            </span> </td>                            
                            <td>
                                <a data-discount="{{$row->discount}}" data-id="{{$row->bill_id}}" data-bill="{{$row->bill_no}}" 
                                    data-patient="
                                    {{$row->patient_name}}
                                 "  data-department="{{$row->department_name}}"
                                     data-docter="{{$row->ef.' '.$row->el}}" data-total="{{$row->total}}" data-date=" {{date('Y-m-d h:m A', strtotime($row->date)) }}"
                                    class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed" data-toggle="modal">Add Medicine</a>
                                <a data-toggle="modal" data-target="#print_modal" class="btn btn-success btn-sm text-white mr-2 print_slip "
                                data-discount="{{$row->discount}}" data-id="{{$row->bill_id}}" data-bill="{{$row->bill_no}}" 
                                    data-patient="
                                    {{$row->patient_name}}
                                "  data-department="{{$row->department_name}}"
                                     data-docter="{{$row->ef.' '.$row->el}}" data-total="{{$row->total}}" data-date="  {{date('Y-m-d h:i:s a', strtotime($row->date)) }}">Print Bill</a>

                                                        
                            <a data-id="{{$row->bill_id}}" class="btn btn-danger btn-sm text-white mr-2 delete2">Delete</a>
                            <a data-toggle="modal" data-target="#editBill" data-id="{{$row->bill_id}}" class="btn btn-info btn-sm text-white mr-2 edit_bi">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                <span class="float-right"> 
                      {{ $bill->links()}}
                 </span> 

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
                    <div class="alert alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="createform">
                       <div class="form-group billll">
                           <label>Bill Number</label>
                           <input type="text" readonly name="bill_number" class="form-control" value="@php $max=helper::getpharmacyBillNo()@endphp {{$max}}">
                        </div>  

                        <div class="form-group">
                           <label>Patient Type</label>
                            <select name="patient_type" class="form-control patient_type">
                                <option value="" disabled selected>select</option>
                                <option value="Registred Patient">Registred Patient</option>
                                <option value="OPD Patient">OPD Patient</option>
                                <option value="Outside Patient">Outside Patient</option>
                            </select>
                        </div>
                        <div class="form-group" style="display:none"  id="patient_group">
                            <label>Patient Name</label>
                
                        <select id="select21" style="width: 100%" class="patient_search form-control"  name="patient_name"></select>

                         </div> 
                         <div class="form-group" style="display:none"  id="patient_group_opd">
                            <label>OPD Patient Name</label>
       
                        <select id="select22" style="width: 100%" class="opd_search form-control"  name="patient_name"></select>

                            
                         </div> 

                         <div class="form-group" style="display:none" id="patient_group1">
                            <label>Patient Name</label>
                          <input type="text" name="patient_name" id="patient_id1382" class="form-control" placeholder="Patient Name">
                        </div> 
                         
                         <div class="form-group">
                            <label>Department Name</label>
                            <select name="department" class="form-control deps">
                                <option value="" selected disabled>select</option>
                                @foreach ($department as $row)
                                <option value="{{ $row->dep_id }}">{{ $row->department_name }}</option>
                                @endforeach
                            </select>
                         </div>  
                         <div class="form-group">
                            <label>Doctor Name</label>
                            <select name="docter_name" class="form-control pos">
                                <option value="" selected disabled>select</option>
                            </select>
                         </div>  
                         <div class="form-group">
                            <label>Note</label>
                             <textarea class="form-control" name="note"  cols="30" rows="3"></textarea>
                         </div>  

                         <div class="modal-footer">
                             <button type="submit" class="btn btn-primary float-left">Generate</button>

                         </div>
                    </form>
                    
                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="editdept" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Add Medicine to Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="editform">
                        <div class="form-group">
                            <label> Medicine Catagory </label>
                            <select name="midicine_catagory" class="form-control midi_cat" onchange="getmedicine_name(this.value)" >
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->ph_main_cat_id}}">{{$item->m_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Medicine Name</label>
                           <select name="medicine"    class="select2 form-control med medicine_name">
                               <option value="" selected disabled>select medicine</option>
                               </select>
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                          <input type="month" name="expiry_date" readonly class="month form-control">
                        </div>
                        <input type="hidden" name="bill_id" class="bill_id">
                            <div class="form-group">
                                <label> Quantity| Available Qty</label>
                                <div class="d-flex ">
                                    <input type="number" step="0.1" name="quantity" class=" d-inline quantity form-control">
                                    <span class="avliableQty form-control" style="text-align:center;width:140px;font-size: 12pt;padding: 5px 14px;border-radius: 0;border-color: #d2d6de;background-color: rgb(156, 156, 156);"></span>
                                </div>
                            </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                          <input type="text" name="sale_price" readonly class="sale_price form-control">
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="amount"  readonly class="amount form-control">
                        </div>
                        

                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Add Medicine</button>
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
                    <h6 class="modal-title">Add Medicine</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger"><ul id="error"></ul></div>
                    
                    @if (!empty(Helper::getpermission("_addpharmacyBillValue")))<button class="btn btn-blue float-right" data-toggle="modal" data-target="#editdept">Add Medicine</button>@endif

                    <br><br>
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label>Bill #:  <strong id="bill_no"></strong></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group float-right">
                                <label>Date:   <strong id="bill_date"></strong></label>
                            </div>
                        </div>
                    </div>
                <div class="row p-4 table-sm table"> 
                    <table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
                        <tbody><tr>
                            <th width="10%">Patient Name</th>
                            <td width="15%"><strong id="patient_name"></strong></td>
                            <th width="10%">Doctor Name</th>
                            <td width="15%"><strong id="docter_name">Sansa Gomez</strong></td>
                            <th width="10%">Department</th>
                            <td width="15%"><strong id="department">Sansa Gomez</strong></td>
                        </tr>
                
                    </tbody>
                  </table>
               </div>
               <div class="row p-4">
                <table class="printablea4 table" id="testreport" width="100%">
                    <tbody>
                    <tr>
                        <th width="20%">Medicine Name</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th> Price ($)</th>
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
                        <input type="number" name="discount" min="0" class="form-control" placeholder="Discount % ..." required max="{{Auth()->user()->discountp}}">
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
                        <th style="width: 30%;">Total</th>
                        <td align="right" id="totals"></td>
                        </tr>
                        <tr>
                        <th>Discount</th>
                        <td align="right" class="discount"></td>
                        </tr>
                        <th>Discount %</th>
                        <td align="right" class="discountpre"></td>
                        </tr>
                        <tr>
                        <th>Net Amount</th>
                        <td align="right" class="netamount"></td>
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
    </div>
    <div id="edit_medicine" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Bill Medicine</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert12 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="edit_medicine_form">
                        <div class="form-group">
                            <label>Medicine Catagory</label>
                            <select name="midicine_catagory" class="form-control midi_cat" id="midi_cat12" onchange="getmedicine_name(this.value)" >
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->ph_main_cat_id}}">{{$item->m_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Medicine Name</label>
                           <select name="medicine"  class="select2 form-control med medicine_name" id="medicine_name12">
                               <option value="" selected disabled>select medicine</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                          <input type="month" name="expiry_date" readonly class="month form-control" id="month12">
                        </div>
                        <input type="hidden" name="bill_id" class="bill_id" id="bill_id12">
                            <div class="form-group">
                                <label> Quantity| Available Qty</label>
                                <div class="d-flex ">
                                    <input type="number" step="0.1" name="quantity" class=" d-inline quantity form-control" id="quantity12">
                                    <span id="avliableQty12" class="avliableQty form-control" 
                                    style="text-align:center;width:140px;font-size: 12pt;padding: 5px 14px;border-radius: 0;border-color: #d2d6de;background-color: rgb(156, 156, 156);"></span>
                                </div>
                            </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                          <input type="text" name="sale_price" readonly class="sale_price form-control" id="sale_price12">
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="amount"  readonly class="amount form-control" id="amount12">
                        </div>
                        

                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Edit Medicine</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

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
                   <h5>Pharmacy Bill</h5>
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
                        <th width="15%">Patient Name</th>
                        <td width="15%"><label id="patient_name1"></label></td>
                        <th width="15%">Doctor Name</th>
                        <td width="15%"><label id="docter_name1"></label></td>
                        <th width="15%">Department</th>
                        <td width="15%"><label id="department1"></label></td>
                    </tr>
            
                </tbody>
              </table>
           </div>
           <div style="margin-top: 20px">
            <table class="printablea4 table"  width="100%">
                <tbody>
                <tr>
                    <th align="left">Medicine Name</th>
                    <th align="left">Expiry Date</th>
                    <th align="left">Quantity</th>
                    <th align="left">Price</th>
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
                    <th >Total</th>
                    <td align="right" id="totals1"></td>
                    </tr>
                    <tr>
                    <th>Discount</th>
                    <td align="right" class="discount1"></td>
                    </tr>
                    <th>Discount %</th>
                    <td align="right" class="discountpre1"></td>
                    </tr>
                    <tr>
                    <th>Net Amount</th>
                    <td align="right" class="netamount1"></td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
           </div>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

    <div id="editBill" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Edit Generated Bill</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert23232 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="editbill_form">
                       <div class="form-group">
                           <label>Bill Number</label>
                           <input type="text" readonly name="bill_number" class="form-control" id="bill_number1">
                           <input type="hidden" id="bill_number_id" name="bill_number_id">
                        </div> 

                        <div class="form-group">
                            <label>Patient Type</label>
                             <select name="patient_type" class="form-control patient_type1">
                                 <option value="" disabled selected>select</option>
                                 <option value="Registred Patient">Registred Patient</option>
                                 <option value="OPD Patient">OPD Patient</option>
                                 <option value="Outside Patient">Outside Patient</option>
                             </select>
                         </div>
                         
                         <div class="form-group" style="display:none"  id="patient_group_opd1">
                            <label>OPD Patient Name</label>
                
                            <select id="select222" style="width: 100%" class="opd_search form-control"  name="patient_names"></select>

                         </div> 


                        <div class="form-group d-none" id="patient_group12">
                            <label>Patient Name</label>
             
                            <select id="patient_id11" style="width: 100%" class="patient_search form-control"  name="patient_names"></select>

                         </div> 

                         <div class="form-group d-none" id="patient_group13">
                            <label>Patient Name</label>
                          <input type="text" name="patient_names" id="patient_id12" class="form-control" placeholder="Patient Name">
                         </div> 

                         <div class="form-group">
                            <label>Department Name</label>
                            <select name="department" class="form-control deps" id="department123">
                                <option value="" selected disabled>select</option>
                                @foreach ($department as $items)
                                <option value="{{ $items->dep_id }}">{{ $items->department_name }}</option>
                                @endforeach
                            </select>
                         </div>  
                         <div class="form-group">
                            <label>Doctor Name</label>
                            <select name="docter_name" class="form-control pos" id="docter1">
                                <option value="" selected disabled>select</option>
                            </select>
                         </div>  
                         <div class="form-group">
                            <label>Note</label>
                             <textarea class="form-control" name="note"  cols="30" rows="3" id="note1"></textarea>
                         </div>  

                         <div class="modal-footer">
                             <button type="submit" class="btn btn-primary float-left">Edit</button>

                         </div>
                    </form>
                    
                </div><!-- modal-body -->


            </div>
        </div><!-- MODAL DIALOG -->
    </div>

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Pharmacy Biling</li>
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


<script type="text/javascript">
     $('.select2').select2({width: '100%',color:'#384364'});

    // opd serach
    $('.opd_search').select2({
      placeholder: 'Select an item',
      ajax: {
        url: '{{ url("search_opd_record")}}',
        dataType: 'json',
        delay: 10,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
                  return {
                      text: item.o_f_name +' '+item.o_l_name +' '+ 'opd-'+item.patient_id,
                      id:item.o_f_name +' '+item.o_l_name +'-'+ item.patient_id
                  }
              })
          };
        },
        cache: true
      }
    });
    // patient serach
$('.patient_search').select2({
      placeholder: 'Select an item',
      ajax: {
        url: '{{ url("search_patient_record")}}',
        dataType: 'json',
        delay: 10,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
                  return {
                      text: item.f_name +' '+item.l_name +' '+ 'p-'+item.patient_idetify_number,
                      id:item.patient_id+'-'+item.patient_idetify_number+ '-'+item.f_name +' '+item.l_name
                  }
              })
          };
        },
        cache: true
      }
    });
</script>

@if (!empty(Helper::getpermission('_pharmacyBilling--delete')))
<script>
        $('.delete2').removeClass('d-none');
</script>
@endif

@if (!empty(Helper::getpermission('_pharmacyBilling--edit')))
<script>
    $('.edit_bi').removeClass('d-none');
</script>
@endif

{{-- end permission --}}
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
    });
    $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');

$('.patient_type').change(function(){
    if($(this).val()=='Registred Patient'){
        $('#patient_group').css('display','block');
        $('#patient_group1').css('display','none');
        $('#patient_id1382').attr('name','');
        $('#select21').attr('name','patient_name');
        $('#select22').attr('name','');
        $('#patient_group_opd').css('display','none');

    }else if($(this).val()=='OPD Patient'){
        $('#patient_group_opd').css('display','block');
        $('#patient_group1').css('display','none');
        $('#patient_group').css('display','none');
        $('#patient_id1382').attr('name','');
        $('#select21').attr('name','');
        $('#select22').attr('name','patient_name');

    }else{
        $('#patient_group').css('display','none');
        $('#patient_group1').css('display','block');
        $('#select21').attr('name','');
        $('#patient_id1382').attr('name','patient_name');
        $('#select22').attr('name','');
        $('#patient_group_opd').css('display','none');
    }
});

$('.patient_type1').change(function(){
    if($(this).val()=='Registred Patient'){
    $('#patient_group12').removeClass('d-none');
    $('#patient_group13').addClass('d-none');
    $('#patient_id12').attr("name",'');
    $('#patient_group_opd1').css('display','none');
    $('#select222').attr("name",'');
    $('#patient_id11').attr("name",'patient_names');

    }else if($(this).val()=='OPD Patient'){
    $('#patient_group_opd1').css('display','block');
    $('#patient_group12').addClass('d-none');
    $('#patient_group13').addClass('d-none');
    $('#select222').attr("name",'patient_names');
    $('#patient_id11').attr("name",'');
    $('#patient_id12').attr("name",'');
    }else{
    $('#patient_group12').addClass('d-none');
    $('#patient_group13').removeClass('d-none');
    $('#patient_id12').attr("name",'patient_names');
    $('#patient_group_opd1').css('display','none');
    $('#patient_id11').attr("name",'');
    $('#select222').attr("name",'');

    }
});
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
        var htmldata="";
        $.ajax({
            url:'{{ url("bill_pharmacy_detail")}}/'+$(this).attr('data-id'),
            type: 'get',
            success: function (data) {
                if(data.info !=""){
                for (let i = 0; i < data.info.length; i++) {
                    
                     htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td>'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                        <td>'+ data.info[i].quanitity+'</td>\
                        <td>'+ data.info[i].price+'</td>\
                        <td>'+ data.info[i].total+'</td>\
                        </tr>'; 
                        
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
            error:function(data){
              
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
        
      });

        
      function printDiv() {
            var divToPrint=document.getElementById('divtoprint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}  th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            $('.modal').modal('hide');
            setTimeout(function(){newWin.close();},10);
        }

  </script>
   <script>
       var Midicine="";
       var emp="";
        // $('select').css('width','100%');
        $(".alert").css('display','none');
        $('.deps').change(function() {       
            var dep=$(this).val();
            url = '{{ url("appoinments_get_position") }}/' +dep;
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
                         if(emp !=""){
                            $(".pos").val(emp);
                         }
                     } else { $(".pos").html('<option value="" selected disabled>No Record Found</option>');}
                 
                 },
 
                 error: function() {}
             })
 
         });
        function getmedicine_name(id) {
            $("#medicine_name").html("<option value=''>Select</option>");
            url = '{{url("medicineFiter")}}' +'/'+ id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        Hdata = '<option value="" selected disabled>select medicine</option>';
                        for (var i = 0; i < data.length; i++) {
                            Hdata += '<option value="' + data[i].midi_id + '">' + data[i]
                                .medicine_name + ' ' + data[i]
                                .company + '</option>';
                            // $(".med").html(Hdata);
                            $(".medicine_name").html(Hdata);
                            if(Midicine!==""){
                                $('.medicine_name').val(Midicine);
                            }
                        }
                    } else {
                        $(".medicine_name").html('<option value="" selected disabled>No Record Found</option>');
                    }
                },
                error: function() {}
            })

        };
        
    $('body').on('change','.med',function(){
      
      $.ajax({
          url:'{{ url("getMedicine_info")}}/'+$(this).val(),
          type: 'get',
          success: function (data) {
            //   console.log(data.total[0]['expiry_date']);
             $('.month').val(data.total[0]['expiry_date']);
             $('.avliableQty').html(data.avaliable);
             $('.quantity').attr('Max',data.avaliable);
             $('.sale_price').val(data.total[0]['sale_price']);
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
  






















    $("#createform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('bill-pharmacy')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                $('#table_date12').prepend(data.html);
                @if (!empty(Helper::getpermission('_pharmacyBilling--delete')))
                        $('.delete2').removeClass('d-none');
                @endif

                @if (!empty(Helper::getpermission('_pharmacyBilling--edit')))
                    $('.edit_bi').removeClass('d-none');
                @endif


                $(".alert").css('display','none');
                $('.billll').load(document.URL +  ' .billll');
                $('#createdept').modal('hide');
                $('#createform')[0].reset();
                    return $.growl.notice({
                    message: data.msg,
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


















     $('body').on('keyup','.quantity',function() {
        if($("#edit_medicine").data('bs.modal')?._isShown){
            var total= $(this).val()*$('#sale_price12').val();  
            $('#amount12').val(total);
        }else{
            var total= $(this).val()*$('.sale_price').val();  
            $('.amount').val(total);
        }
         
    });
    $('body').on("click",'.addMedicine',function() {
        $('.bill_id').val($(this).attr('data-id'));
        $('#bill_no').html($(this).attr('data-bill'));
        $('#patient_name').html($(this).attr('data-patient'));
        $('#department').html($(this).attr('data-department'));
        $('#docter_name').html($(this).attr('data-docter'));
        $('#bill_date').html($(this).attr('data-date'));
        $('#data-total').html($(this).attr('data-total'));
        
        var htmldata="";
        $.ajax({
            url:'{{ url("bill_pharmacy_detail")}}/'+$(this).attr('data-id'),
            type: 'get',
            success: function (data) {
                if(data.info !=""){
                for (let i = 0; i < data.info.length; i++) {
                    
                     htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                        <td>'+ data.info[i].quanitity+'</td>\
                        <td>'+ data.info[i].price+'</td>\
                        <td>'+ data.info[i].total+'</td>\
                        <td align="center">\
                            @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                            @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
                            </td>\
                        </tr>'; 
                        
                        $('#hdata').html(htmldata);
                }
                $('#totals').html(data.totals);
                var discount=data.discount*data.totals/100;
                $('.discount').html(discount);
                $('.discountpre').html(data.discount+'%');
                $('.netamount').html(data.totals-discount);
                

                }else{
                    $('#hdata').html('<tr><td>No record data found</td></tr>');
                    $('#totals').html("N/A");
                    $('.discountpre').html("N/A");
                    $('.netamount').html("N/A");
                    $('.discount').html("N/A");

                }

            },
            error:function(data){
              
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $('#discount').submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('pharmacy-bill-discount')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                var htmldata="";
             
                $.ajax({
                    url:'{{ url("bill_pharmacy_detail")}}/'+$('.bill_id').val(),
                    type: 'get',
                    success: function (data) {
                        $('#hdata').html("");
                        for (let i = 0; i < data.info.length; i++) {
                            htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                                <td>'+ data.info[i].quanitity+'</td>\
                                <td>'+ data.info[i].price+'</td>\
                                <td>'+ data.info[i].total+'</td>\
                                <td align="center">\
                                    @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                    @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
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

    $("#editform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("pharmacy_add_medicine_bill")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                    var htmldata="";
 
                    $.ajax({
                        url:'{{ url("bill_pharmacy_detail")}}/'+$('.bill_id').val(),
                        type: 'get',
                        success: function (data) {
                            $('#hdata').html("");
                            for (let i = 0; i < data.info.length; i++) {
                                htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                                    <td>'+ data.info[i].quanitity+'</td>\
                                    <td>'+ data.info[i].price+'</td>\
                                    <td>'+ data.info[i].total+'</td>\
                                    <td align="center">\
                                        @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                                        @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
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

                $('#editdept').modal('hide');
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

    $('body').on('click','.edit_medicine',function() {
  
        $.ajax({
            url: "{{url('getMedicine_info_edit')}}/"+$(this).attr('data-data'),
            type: 'get',
            success: function (data) {
                $('#midi_cat12').val(data.mid_cat);
                $('#midi_cat12').trigger('change');
                $('#medicine_name12').val(data.info['midi_id']);
                if(data.info['midi_id']!==""){
                    Midicine=data.info['midi_id'];
                }
                $('#month12').val(data.info['expiry_date']);
                $('#quantity12').val(data.info['quanitity']);
                $('#sale_price12').val(data.info['price']);
                $('#amount12').val(data.info['total']);   
                $('#bill_id12').val(data.info['pharma_bill_ifo_id']);                 
                $('#avliableQty12').html(data.avaliable);    
                      
            },
            error:function(data){
                console.log('server Error');
            },
            cache: false,
            contentType: false,
            processData: false
        }); 


    });


    $("#edit_medicine_form").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        var htmldata="";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("pharmacy_update_medicine_bill")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert12").css('display','none');
                    var htmldata="";
 
                    $.ajax({
                        url:'{{ url("bill_pharmacy_detail")}}/'+$('.bill_id').val(),
                        type: 'get',
                        success: function (data) {
                            $('#hdata').html("");
                            for (let i = 0; i < data.info.length; i++) {
                                htmldata+='<tr id="row'+data.info[i].pharma_bill_ifo_id+'"><td width="20%">'+ data.info[i].medicine_name+'</td><td>'+ data.info[i].expiry_date +'</td>\
                                    <td>'+ data.info[i].quanitity+'</td>\
                                    <td>'+ data.info[i].price+'</td>\
                                    <td>'+ data.info[i].total+'</td>\
                                    <td align="center">\
                                        @if (!empty(Helper::getpermission("_deletepharmacyBillValue")))<a data-delete="'+data.info[i].pharma_bill_ifo_id+'" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>\@endif
                            @if (!empty(Helper::getpermission("_editpharmacyBillValue")))<a data-data="'+data.info[i].pharma_bill_ifo_id+'" data-toggle="modal" data-target="#edit_medicine" class="btn btn-info btn-sm text-white mr-2 edit_medicine">Edit</a>@endif
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

                $('#edit_medicine').modal('hide');
                $('#edit_medicine_form')[0].reset();
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
                    url:'{{url("pharmacy_delete_medicine_bill")}}/'+id,
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
                      'Medicine has related Data please first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});

$('body').on('click','.edit_bi',function () {
    var id=$(this).attr('data-id');
    $.ajax({
        type:'get',
        url:'{{url("bill-pharmacy")}}/'+id+'/'+'edit',
        success:function(data){ 
            $('#bill_number1').val(data.bill_no);
            if(data.p_type=="Registred Patient"){
                $('#patient_id11').html('<option value="'+data.patient_id+'-'+data.p_identify+'-'+data.patient_name+'" >'+data.patient_name+' p-'+data.p_identify+'</option>');
                $(".patient_type1").val(data.p_type);
                $(".patient_type1").prop("checked", true).trigger("change");
             
            }else if(data.p_type=="OPD Patient"){
                $(".patient_type1").val(data.p_type);
                $(".patient_type1").prop("checked", true).trigger("change");
                $('#select222').html('<option value="'+data.patient_name+'-'+data.p_identify+'" >'+data.patient_name+' opd-'+data.p_identify+'</option>')
                
            }else{
                $(".patient_type1").val(data.p_type);
                $(".patient_type1").prop("checked", true).trigger("change");
                $('#patient_id12').val(data.patient_name);
            }

            $('#department123').val(data.dep_id);
            $('#department123').trigger('change');
            if(data.emp_id !=""){
                emp=data.emp_id;
            }
            $('#note1').val(data.note);
            $('#bill_number_id').val(data.bill_id);
          
       
        },
        error:function(error){
      
        }
    });
    


});

$("#editbill_form").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('bill-pharmacy_update')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                $('#table_date12').prepend(data.html);
                $(".alert23232").css('display','none');
                @if (!empty(Helper::getpermission('_pharmacyBilling--delete')))
                        $('.delete2').removeClass('d-none');
                @endif

                @if (!empty(Helper::getpermission('_pharmacyBilling--edit')))
                    $('.edit_bi').removeClass('d-none');
                @endif

                $('#editBill').modal('hide');
                $('#editbill_form')[0].reset();
                    return $.growl.notice({
                    message: data.msg,
                    title: 'Success !',
                });
            },
            error:function(data){
                $(".alert23232").find("ul").html('');
                $(".alert23232").css('display','block');
            $.each( data.responseJSON.errors, function( key, value ) {
                    $(".alert23232").find("ul").append('<li>'+value+'</li>');
                });     
    
            },
            cache: false,
            contentType: false,
            processData: false
        });
});


$('body').on('click','.delete2',function(){  
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
                    url:'{{url("bill-pharmacy")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    )
                    $('#row2'+id).hide(1500);
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



</script>

@endsection
