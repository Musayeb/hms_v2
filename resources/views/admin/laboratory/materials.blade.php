@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />

@endsection
@section('title') Laboratory Material @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
            
            @if (!empty(Helper::getpermission('_labMaterials--create')) )
            <a href="javascript:viod();" data-toggle="modal" data-target="#createdept" class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Material</a>
            @endif
            

        </div>
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material Name</th>
                        <th>Material Catagory</th>
                        <th>Material Company</th>
                        <th>Author</th>
                        <th>Purchase Price</th>
                        <th>Sale Price</th>
                        <th>Expiry Date</th>
                        <th>Avaliable Qty</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($mat as $row)
                        <tr id="row{{$row->ph_main_cat_id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $row->material_name }}</td>
                            <td>{{ $row->lab_cat_name }}</td>
                            <td>{{ $row->company }}</td>
                            <td>{{ $row->email }}</td>
                            <td> @php $purchase=helper::getlabpurchaseprice($row->lab_m_id) @endphp 
                                @if ($purchase=='') N/A @else{{ $purchase->purchase_price }} @endif
                            </td>

                            <td> @php $sale=helper::getlabsaleprice($row->lab_m_id) @endphp  
                                 @if ($sale=='') N/A @else{{ $sale->sale_price }} @endif
                            </td>
                            <td>
                                @php $expire=helper::getlabexpirydate($row->lab_m_id) @endphp  
                                 @if ($expire=='') N/A @else{{ $expire->expiry_date }} @endif
                             </td>
                                
                            <td>
                                @php $mark=helper::getquantity($row->lab_m_id) @endphp
                       
                            @if ($mark==0)<span class="text-danger">Out of Stock</span>@endif
                            @if ($mark>20)<span class="text-success">{{$mark}}</span>@endif
                            @if ($mark<20 && $mark!==0)<span class="text-danger">Low stock ({{$mark}})</span>@endif</td>

                            <td>{{ $row->created_at }}</td>
                            <td>
                                @if (!empty(Helper::getpermission('_labMaterials--delete')) )
                                    <a data-delete="{{$row->ph_main_cat_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                                @endif
                                @if (!empty(Helper::getpermission('_labMaterials--edit')) )
                                    <a data-cat="{{$row->lab_cat_id}}" data-material="{{$row->lab_cat_name}}"  data-company="{{$row->company}}" data-toggle="modal" data-target="#editdept" data-id="{{$row->lab_m_id}}" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
                                @endif


                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
           <span class="float-right"> {{$mat->links()}}</span>

        </div>
    </div>


    {{-- models --}}
    <!-- LARGE MODAL -->
    <div id="createdept" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Material Register</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger"><ul id="error"></ul></div>
                    <form method="post" id="createform">
                        <div class="form-group">
                            <label>Material Catagory</label>
                            <select name="material_catagory" class="form-control">
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->lab_cat_id}}">{{$item->lab_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Material Name</label>
                            <input name="material_name" type="text" class="form-control" placeholder="Midicine Name" >
                        </div>
                        <div class="form-group">
                            <label>Material Company Name</label>
                            <input name="company_name" type="text" class="form-control" placeholder="Company Name" >
                        </div>
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Add Material</button>
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
                            <label>Material Catagory</label>
                            <select name="material_catagory" class="form-control" id="cat">
                                <option value="" selected disabled>select catagory</option>
                                @foreach ($cat as $item)
                                    <option value="{{$item->lab_cat_id}}">{{$item->lab_cat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Material Name</label>
                            <input name="material_name" type="text" class="form-control" id="mat" placeholder="Midicine Name" >
                        </div>
                        <div class="form-group">
                            <label>Material Company Name</label>
                            <input name="company_name" type="text" class="form-control" id="company" placeholder="Company Name" >
                        </div>
                        
                        <input type="hidden" name="id_material" id="id_material">                    
                        <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Edit Material</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>

@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Lab Materials</li>
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
            columns: [1,2,3,4,5,6,7,8,9]
          }
       },
       {
           extend: 'print',
           footer: false,
           exportOptions: {
                columns: [1,2,3,4,5,6,7,8,9]
            }
       },
               
    ],
    } );
    
    $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
    $(".alert").css('display','none');
    $("#createform").submit(function(e) {
        e.preventDefault();   
        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url: "{{url('lab-materials')}}",
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



    $("#editform").submit(function(e) {
        e.preventDefault();   
        var id=$('#dept_id').val();  

        var formData = new FormData(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN':'@php echo csrf_token() @endphp '}});  
        $.ajax({
            url:'{{ url("material_update")}}',
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

    $('body').on('click','.edit',function(){
        
      var cat=$(this).attr('data-cat');
      var company=$(this).attr('data-company');
      var material=$(this).attr('data-material');
       var id =$(this).attr('data-id');
        $('#cat').val(cat);   
        $('#id_material').val(id);  
        $('#mat').val(material);  
        $('#company').val(company);          
    });


    </script>

@endsection
