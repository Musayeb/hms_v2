@extends('layouts.admin')
@section('title') End of the day @endsection

@section('css')
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css    " rel="stylesheet" />
@endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">


             @if (!empty(Helper::getpermission('_endOfTheDay--create')))
             <a href="{{url('calculateEndoftheday')}}"  
             class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New End Of The Day</a>
             @endif

        </div>
        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable main-table no-footer" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody >
                    @php $counter=1; @endphp
                    @foreach ($eod as $row)
                         <tr id="row{{$row->id}}" class="tbody123">
                            <td>{{$counter++ }}</td>
                            <td> {{ $row->user_name }} </td>
                            <td> {{ $row->created_at }} </td>
                            <td>

                       
                            @if (!empty(Helper::getpermission('_endOfTheDay--delete')))
                                <a data-delete="{{$row->id}}" class="btn btn-danger btn-sm text-white mr-2 user_delete">Delete</a>

                            @endif
                        

                                <a data-id="{{$row->id}}"  class="btn btn-success btn-sm text-white mr-2 print_slip" data-toggle="modal" data-target="#print_modal">Print</a>
         
                            </td>
                 
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <span class="float-right"> {{$eod->links()}}</span>

        </div>
    </div>
    



    <div id="print_modal" class="modal fade" style="z-index:100000">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">End Of The Day</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20" id="divtoprint">
                    <img src="{{ url('public/payslip.png') }}" width="100%" alt="">
                    <h4>End of the day</h4>

                    <div class="printData">

                    </div>

                </div><!-- modal-body -->
            </div>
        </div><!-- MODAL DIALOG -->
    </div>



@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">End Of The Day</li>
@endsection

@section('jquery')

<script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>

    @if(session()->has('notif'))
    <script>
      $(document).ready(function(){
         return  $.growl.notice({
              message: '{{session()->get("notif")}}',
              title: 'Success !',
          });  
      });
    </script>
    @endif 

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
            columns: [0,1,2]
          }
       },
       {
           extend: 'print',
           footer: false,
           exportOptions: {
                columns: [0,1,2]
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
                url: "{{url('end-of-the-day')}}",
                type: 'POST',
                data: formData,
                success: function (data) {
                    $(".alert").css('display','none');
                    $('.main-table').load(document.URL +  ' .main-table');
                    $('#create').modal('hide');
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

        // Delelte User
        $('body').on('click','.user_delete',function(){  
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
                        url:'{{url("end-of-the-day")}}/'+id,
                        success:function(data){ 
                        Swal.fire(
                        'Deleted!',
                        'Your recorde has been deleted.',
                        'success'
                        )
                        $('#row'+id).hide(1500);
                        },
                        error:function(error){
                        Swal.fire(
                        'Faild!',
                        'Ooops, something wrong appended.',
                        'error'
                        )
                        }
                    });
                }
            })
              
        });
        

    </script>


    {{-- billing code --}}
    <script>
        $('body').on("click",'.print_slip',function() {
            $.ajax({
                type:'GET',
                url:'{{url("end-of-the-day")}}/'+$(this).attr('data-id'),
                success:function(data){ 
                   $('.printData').html(data); 
                   setTimeout(function(){ printDiv(); },500);

                },
                error:function(error){
                    console.log('Server Error');

                }
          });
        });
        
 
        function printDiv() {
            var divToPrint = document.getElementById('divtoprint');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%;}th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
        $('.modal').modal('hide');
            setTimeout(function() {
                newWin.close();
            }, 10);
        }

    </script>
  
 
  
@endsection