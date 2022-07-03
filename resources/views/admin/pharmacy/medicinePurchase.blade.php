@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <th>Suppiler Name</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Sale Price</th>
                        <th>Total Amount</th>
                        <th>Expiry Date</th>
                        <th>Status</th>              
                        <th>Author</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($mid as $row)
                        <tr id="row{{$row->purchase_m_id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->medicine_name }}</td>
                            <td>{{ $row->supplier_name }}</td>
                            <td>{{ $row->quantity }}</td>
                            <td>{{ $row->purchase_price }}</td>
                            <td>{{ $row->sale_price }}</td>
                            <td>{{ $row->amount }}</td>
                            <td>{{ $row->expiry_date }}</td>
                            <td>
                                @if($row->status=="Pending")
                               <span class="badge badge-danger">{{$row->status}}</span> 
                               @else
                               <span class="badge badge-success">{{$row->status}}</span> 
                               @endif
                            </td>

                            <td>{{ $row->email }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                           

                                @if (!empty(Helper::getpermission('_purchaseMediciens--status-change')) )
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm status" data-backdrop="static" data-target="#status"
                                    data-toggle="modal" data-id="{{ $row->purchase_m_id }}">Status</a>
                                @endif
                                
                                @if (!empty(Helper::getpermission('_purchaseMediciens--edit')) )
                                    <a  data-toggle="modal" data-target="#editdept" data-id="{{$row->purchase_m_id}}" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>

                                @endif


                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
           <span class="float-right"> {{$mid->links()}}</span>

        </div>
    </div>


    {{-- models --}}
    <!-- LARGE MODAL -->
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
     $('.select2').select2({width: '100%',color:'#384364'});
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
            columns: [1,2,3,4,5,6,7,8,9,10]
          }
       },
       {
           extend: 'print',
           footer: false,
           exportOptions: {
                columns: [1,2,3,4,5,6,7,8,9,10]
            }
       },
               
    ],
    } );
    
    $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');

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
                    url:'{{url("medicines_cat")}}/'+id,
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
                      'Catagory has related data first delete Catagory data',
                      'error'
                    )
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
