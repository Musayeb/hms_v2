@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection
@section('title') Laboratory Material Purchase @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
        
            @if (!empty(Helper::getpermission('_purchaseLabMaterial--create')) )
                    <a href="javascript:viod();" data-toggle="modal" data-target="#createdept"
                    class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Purchase New Material</a>
            @endif

                <a class="pull-right btn btn-primary d-inline mr-1 approval">Send Approval Request</a>
        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material Name</th>
                        <th>Suppiler Name</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Sale Price</th>
                        <th>Total Amount</th>
                        <th>Expiry Date</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($mid as $row)
                        <tr id="row">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->material_name }}</td>
                            <td>{{ $row->supplier_name }}</td>
                            <td>{{ $row->quantity }}</td>
                            <td>{{ $row->purchase_price }}</td>
                            <td>{{ $row->sale_price }}</td>
                            <td>{{ $row->amount }}</td>
                            <td>{{ $row->expiry_date }}</td>
                            <td>{{ $row->email }}</td>
                            <td>
                            @if($row->status=="Pending")
                            <span class="badge badge-danger">{{$row->status}}</span> 
                            @else
                            <span class="badge badge-success">{{$row->status}}</span> 
                            @endif
                         </td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                                @if (!empty(Helper::getpermission('_purchaseLabMaterial--change_status')) )
                                <a href="javascript:void(0);" class="btn btn-success btn-sm status" data-backdrop="static" data-target="#status"
                                data-toggle="modal" data-id="{{ $row->lab_purchase_id }}">Status</a>
                                @endif

                                    @if (!empty(Helper::getpermission('_purchaseLabMaterial--edit')) )
                                        <a  data-toggle="modal" data-target="#editdept" data-id="{{$row->lab_purchase_id}}" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
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
                    <h6 class="modal-title">Purchase Material</h6>
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
                            <label>Material Catagory</label>
                            <select name="material_catagory " class="form-control mat_cat" id="">
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->lab_cat_id}}">{{$item->lab_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Material Name</label>
                           <select name="material" class="form-control med">
                               <option value="" selected disabled>select material</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input name="quantity" type="number" class="form-control" placeholder="Quantity" >
                        </div>
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input name="purchase_price" type="number" class="form-control" placeholder="Purchase Price" >
                        </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                            <input name="sale_price" type="number" class="form-control" placeholder="Sale Price" >
                        </div>
                        <div class="form-group">
                            <label>Expiry Name</label>
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
                    <h6 class="modal-title">Material Edit</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert1 alert-danger"><ul id="error"></ul></div>

                    <form method="post" id="editform">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <input name="supplier_name" type="text" class="form-control" placeholder="Supplier Name" id="supplier_name" >
                            <input type="hidden" name="purchase_id" id="purchase_id">
                        </div>

                        <div class="form-group">
                            <label>Material Catagory</label>
                            <select name="material_catagory" class="form-control mat_cat">
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->lab_cat_id}}">{{$item->lab_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Material Name</label>
                           <select name="material" class="form-control med">
                               <option value="" selected disabled>select material</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input name="quantity" type="number" class="form-control" placeholder="Quantity" id="quant">
                        </div>
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input name="purchase_price" type="number" class="form-control" placeholder="Purchase Price" id="purch_price" >
                        </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                            <input name="sale_price" type="number" class="form-control" placeholder="Sale Price" id="sale_price" >
                        </div>
                        <div class="form-group">
                            <label>Expiry Name</label>
                            <input name="expiry_date" type="month" class="form-control" id="expiry_date" >
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Edit purchase</button>
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
    <li class="breadcrumb-item active" aria-current="page">Purchase Lab Material</li>
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

        $(".alert").css('display','none');

        $('.mat_cat').change(function() {
            id = ($(this).val());
            url = '{{url("material_filter")}}' +'/'+ id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    if (data != '') {
                        Hdata = '<option value="" selected disabled>select Material</option>';
                        for (var i = 0; i < data.length; i++) {
                            Hdata += '<option value="' + data[i].lab_m_id + '">' + data[i]
                                .material_name + ' ' + data[i]
                                .company + '</option>';
                            $(".med").html(Hdata);
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
            url: "{{url('lab-purchase-materials')}}",
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
      var id =$(this).attr('data-id');
      var Hdata="";

        $.ajax({
            type: 'get',
            url: "{{url('lab-purchase-materials')}}"+'/'+id+'/'+'edit',
            success: function(data) {
                // if (data.mat != '') {
                Hdata = '<option value="" selected disabled>select Material</option>';
                
                if (data.mat != '') {
                Hdata = '<option value="" selected disabled>Select material</option>';
                for (let i = 0; i < data.mat.length; i++) {
                    Hdata += '<option value="' +data.mat[i].lab_m_id + '">' + data.mat[i]
                        .material_name + ' ' + data.mat[i]
                        .company + '</option>';
                    $(".med").html(Hdata);
                }
            }
                $('.mat_cat').val(data.cat);
                $('.med').val(data.purchase.lab_m_id);
                $('#supplier_name').val(data.purchase.supplier_name);
                $('#quant').val(data.purchase.quantity);
                $('#purch_price').val(data.purchase.purchase_price);
                $('#sale_price').val(data.purchase.sale_price);
                $('#expiry_date').val(data.purchase.expiry_date);
                $('#purchase_id').val(data.purchase.lab_purchase_id);
             
            },
            error: function() {}
        })

    });

    $("#editform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("materials_update")}}',
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
                    url: "{{ url('purchase-lab_aproval') }}",
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
    $('#status').change(function() {
        $value = $(this).val();
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
            url: "{{ url('purchase-lab_aproval_status') }}",
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
    $('.status').click(function() {
            $('#idss').val($(this).attr('data-id'));
        });
</script>
@endsection
