@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        table.dataTable.table-sm>thead>tr>th{
            font-size: 11px !important;
        }
        .the{
            text-transform:capitalize !important;
        }
       #example_processing{
        z-index: 100000;
        }

    </style>
@endsection
@section('title') Purchase Medicine @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            
            @if (!empty(Helper::getpermission('_purchaseMediciens--create')) )
                    <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Purchase New Medicine</a>
            @endif

                <a class="pull-right btn btn-primary d-inline mr-1 approval">Send Approval Request</a>

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Batch #</th>
                        <th>Quantity</th>
                        <th>Sale Price</th>
                        <th>Total Amount</th>
                        <th>Status</th>              
                        <th style="min-width: 10em">Date</th>
                        <th style="min-width: 10em">Action</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>

        </div>
    </div>


    {{-- models --}}
    <!-- LARGE MODAL -->
    <div id="show_detail" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Purchase Detail</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="">
                    <table class="table table-bordered table-show table-sm">
                    <tr>
                        <th class="the">Medicine Name</th>
                        <td id="med_name"></td>
                        <th class="the">Supplier Name</th>
                        <td id="supplier_name2"></td>
                        <th class="the">Batch Number</th>
                        <td id="batch_no"></td>
                    </tr>
                    <tr>
                        <th class="the">Quantity</th>
                        <td id="quant"></td>
                        <th class="the">Sell Price</th>
                        <td id="sell_p"></td>
                        <th class="the">Purchase Price</th>
                        <td id="pur_price"></td>
                    </tr>
                    <tr>
                        <th class="the">Total Amount</th>
                        <td id="total_am"></td>
                        <th class="the">Expiry Date</th>
                        <td id="ex_date"></td>
                        <th class="the">Status</th>
                        <td id="status_info"></td>
                    </tr>
                    <tr>
                        <th  class="the">Author</th>
                        <td colspan="2"id="author_name"></td>
                        <th class="the">Date</th>
                        <td colspan="2" id="date_info"></td>
                    </tr>
                    </table>
                    </div>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>
    <div id="createdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Purchase Medicine</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger"><ul id="error"></ul></div>
                    <form method="post" id="createform">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <input name="supplier_name" type="text" class="form-control" placeholder="Supplier Name" >
                        </div>

                        <div class="form-group">
                            <label>Medicine Catagory</label>
                            <select name="midicine_catagory" class="form-control midi_cat">
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->ph_main_cat_id}}">{{$item->m_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Medicine Name</label>
                           <select name="medicine"  class="form-control med select2">
                               <option value="" selected disabled>select medicine</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label>Batch Number</label>
                            <input type="text" name="batch_number"  class="form-control" placeholder="Batch Number">
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input name="quantity" type="number"   step="any"  class="form-control" placeholder="Quantity Name" >
                        </div>
       
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input name="purchase_price" type="number"  step="any"   class="form-control" placeholder="Purchase Price" >
                        </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                            <input name="sale_price" type="number" step="any"  class="form-control" placeholder="Sale Price " >
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input name="expiry_date" type="month" class="form-control"  >
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Purchase</button>
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
                    <h6 class="modal-title">Medicine Edit</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="edit_form">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <input name="supplier_name" id="supplier_name" type="text" class="form-control" placeholder="Supplier Name" >
                        </div>

                        <div class="form-group">
                            <label>Medicine Catagory</label>
                            <select name="midicine_catagory" class="form-control midi_cat">
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->ph_main_cat_id}}">{{$item->m_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Medicine Name</label>
                           <select name="medicine" class="form-control med select2">
                               <option value="" selected disabled>select medicine</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label>Batch Number</label>
                            <input type="text" name="batch_number" id="batch_number" class="form-control" placeholder="Batch Number">
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input name="quantity" type="number"  step="any"   class="form-control quantity" placeholder="Quantity Name" >
                        </div>
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input name="purchase_price" type="number"   step="any"  class="form-control p_price" placeholder="Purchase Price" >
                        </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                            <input name="sale_price" type="number"  step="any"   class="form-control s_price" placeholder="Sale Price " >
                            <input type="hidden" name="hide" id="purchase_id">
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input name="expiry_date" type="month" class="form-control expiry_date">
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
    <div class="modal fade" id="status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">Purchase Medicines Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="status_form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Change Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="" selected disabled>Select Status</option>
                                <option value="Approved">Approved</option>
                                <option value="Pending">Pending</option>
                            </select>
                            <input type="hidden" id="idss" name="id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Purchase Medicines</li>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
   $('body').on('click','.show_rec',function(){
    var d= $(this).attr('data-id');
        $.ajax({
            url: "{{ url('purchase-mediciens') }}/"+d,
            type: 'get',
            success: function(data) {
                $('#med_name').html(data[0].medicine_name);
                $('#supplier_name1').html(data[0].supplier_name);
                $('#batch_no').html(data[0].batch_number);
                $('#quant').html(data[0].quantity);
                $('#sell_p').html(data[0].sale_price);
                $('#pur_price').html(data[0].purchase_price);
                $('#total_am').html(data[0].amount);
                $('#ex_date').html(data[0].expiry_date);
                $('#author_name').html(data[0].email);
                $('#status_info').html(data[0].status);
                $('#date_info').html(moment(data[0].created).format('YYYY-MM-DD h:m:s a'));
       
            },
            error: function(data) {
            },
        });
   }); 
</script>
<script>
     $('.select2').select2({width: '100%',color:'#384364'});
     $(document).ready(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
        $('#example').DataTable({
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

                ajax: "{{ url('purchase-mediciens') }}",
                columns: [{
                        data: null
                    },
                    {
                        data: 'medicine_name'
                    },
                    {
                        data: 'batch_number'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'sale_price'
                    },
             
                    {
                        data: 'amount',
                      
                    },
                    {
                        data: 'status',
                      
                    },
    
                    {
                        data: 'created_at',
                        render: function (data, type, row) {//data
                        return moment(row.created_at).format('YYYY-MM-DD h:m:s a');

                    },
                    },

                    {
                        "mData": null,
                        "bSortable": false,
                        "mRender": function(o) {
                        return '<span class="btn btn-success btn-sm status"  style="display:none" data-backdrop="static" data-target="#status"data-toggle="modal"  data-id="'+o.purchase_m_id+'">\
                                    <i data-toggle="tooltip" title="Status" class="fa fa-calendar fa-lg"></i>\
                                </span><span  data-toggle="modal"  style="display:none" data-target="#editdept" data-id="'+o.purchase_m_id+'" class="btn btn-info btn-sm text-white edit">\
                                    <i  data-toggle="tooltip" title="Edit" class= "fa fa-edit fa-lg"></i>\
                                </span >\
                                <span data-toggle="tooltip" title="Delete"  style="display:none" class="btn btn-danger btn-sm delete" data-delete="'+o.purchase_m_id+'"><i class="fa fa-trash fa-lg"></i></span>\
                                <span data-toggle="modal" data-target="#show_detail"  class="btn btn-warning btn-sm show_rec" data-id="'+o.purchase_m_id+'"><i data-toggle="tooltip" title="Show Detail" class="fa fa-eye fa-lg"></i></span>';
                        }
                    }

                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    var index = iDisplayIndex +1;
                    $('td:eq(0)',nRow).html(index);
                    return nRow;
                },
                "drawCallback": function( settings ) {
                    @if (!empty(Helper::getpermission('_purchaseMediciens--status-change')) )
                    $('.status').show();
                    @endif
                    @if (!empty(Helper::getpermission('_purchaseMediciens--edit')) )
                    $('.edit').show();
                    @endif
                    @if (!empty(Helper::getpermission('_purchaseMediciens--delete')) )
                    $('.delete').show();
                    @endif
                
                },

                order: [
                    [0, 'desc']
                ]
            });
            $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');

     });
    

</script>
    <script>
        var midi="";
        $('body').on('click','.status',function() {
            $('#idss').val($(this).attr('data-id'));
        });
        
        $(".alert").css('display','none');

        $('.midi_cat').change(function() {
            id = ($(this).val());
            url = '{{url("medicineFiter")}}' +'/'+ id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        Hdata = '<option value="" selected disabled>select midicine</option>';
                        for (var i = 0; i < data.length; i++) {
                            Hdata += '<option value="' + data[i].midi_id + '">' + data[i]
                                .medicine_name + ' ' + data[i]
                                .company + '</option>';
                            $(".med").html(Hdata);
                        }
                        if(midi !=""){
                            $(".med").val(midi).change();

                        }
                    } else {
                        $(".med").html(
                            '<option value="" selected disabled>No Record Found</option>');
                    }
                },
                error: function() {}
            })

        });

    $("#createform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('purchase-mediciens')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
                $(".alert").css('display','none');
                $('#example').DataTable().ajax.reload();
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
        var id=$(this).attr('data-id');
        $.ajax({
            url: "{{ url('purchase-mediciens') }}/"+id+'/edit',
            type: 'get',

            success: function(data) {
              $('.midi_cat').val(data.pharma).change();
              $('#supplier_name').val(data.purchase['supplier_name']);
              $('#purchase_id').val(data.purchase['purchase_m_id']);
              $('.s_price').val(data.purchase['sale_price']);
              $('.p_price').val(data.purchase['purchase_price']);
              $('.expiry_date').val(data.purchase['expiry_date']);
              $('.quantity').val(data.purchase['quantity']);  
              $('#batch_number').val(data.purchase['batch_number']);  
              $('.midi_cat').val(data.pharma).change();
               midi=data.purchase['midi_id'];
            },

            error: function(data) {
            console.log('Server Error');    
            },

        });

    });

    $("#edit_form").submit(function(e) {
        e.preventDefault();   
        var id=$('#dept_id').val();  

        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("purchase-mediciens-update")}}',
            type: 'post',
            data: formData,
            success: function (data) {
                $(".alert1").css('display','none');
                $('#example').DataTable().ajax.reload();
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
                    url:'{{url("purchase-mediciens")}}/'+id,
                    success:function(data){ 
                    Swal.fire(
                      'Deleted!',
                      'Your record has been deleted.',
                      'success'
                    )
                    $('#example').DataTable().ajax.reload();
                    },
                    error:function(error){
                    console.log('Server Error');
                    }
                });
            }
          })
              
});
    </script>
<script>
        $('.approval').click(function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    $(this).html('Sending....').prop('disabled', true);

                    $.ajax({
                        url: "{{ url('purchase-mediciens_aproval') }}",
                        type: 'get',

                        success: function(data) {
                            $('.approval').html('Send Approval Request');
                            $('.approval').attr('disabled',false);

                            Swal.fire(
                                'Successfull!',
                                'Approval Request Sent successfully.',
                                'success'
                            );

                        },
                        error: function(data) {

                        },

                    });


                }
            });


        });
        
        $('body').on('change','#status',function() {
            var a = $(this).val();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    $("#status_form").submit();
                }
            })
        });
        
        $("#status_form").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '@php echo csrf_token() @endphp '}});
            $.ajax({
                url: "{{ url('purchase-mediciens_aproval_status') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    $(".alert").css('display', 'none');
                    $('.table').load(document.URL + ' .table');
                    $('#status').modal('hide');
                    $('#status_form')[0].reset();

                    Swal.fire(
                        'Successfull!',
                        data.success,
                        'success'
                    );
                    return $.growl.notice({
                        message: data.success,
                        title: 'Success !',
                    });
                },
                error: function(data) {

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
</script>

@endsection
