@extends('layouts.admin')

@section('css')
<link href="{{asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{asset('public/assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet"/>
<link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css    " rel="stylesheet" />

@endsection
@section('title') Patients @endsection

@section('content')
    <div class="card p-3">
        <div class="btn-list ">
        @if (!empty(Helper::getpermission('_patients--create')))       
        <a href="{{route('patients.create')}}" class="pull-right btn btn-primary d-inline"><i class="ti-plus"></i> &nbsp;Add New Patient</a>
          @endif  
    </div>
        <div class="mt-5 table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-sm" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PID</th>
                        <th>Patients FullName</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Department</th>
                        <th>phone Number</th>
                        <th>Registred Date</th>
                        <th>Author</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @foreach ($patients as $row)
                        <tr id="row{{$row->patient_id}}">
                            <td>{{$counter++}}</td>
                            <td>P-{{$row->patient_idetify_number}}</td>
                            <td>{{$row->f_name.' '.$row->l_name}}</td>
                            <td>{{$row->dob}}</td>
                            <td>{{$row->gender}}</td>
                            <td>{{$row->department_name}}</td>
                            <td>{{$row->phone_number}}</td>
                            
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->email}}</td>

                            <td>
                                <a 
                                class="btn btn-primary btn-sm text-white mr-2 print"  
                                data-patent="{{$row->f_name.' '.$row->l_name}}"
                                data-age="{{$row->age}}"
                                data-phone="{{$row->phone_number}}"
                                data-no="{{'P-' . $row->patient_idetify_number}}"
                                data-date="{{$row->created_at}}"
                                data-department="{{$row->department_name}}" >Print
                              </a>
                              @if (!empty(Helper::getpermission('_patients--delete')) || !empty(Helper::getpermission('_patients--edit')) || !empty(Helper::getpermission('_patients--view')))
                                 @if (!empty(Helper::getpermission('_patients--view')))    
                                <a href="{{route('patients.show',$row->patient_id)}}"  class="btn btn-success btn-sm text-white mr-2">View</a>
                                @endif
                                @if (!empty(Helper::getpermission('_patients--delete')))
                                <a data-delete="{{$row->patient_id}}" class="btn btn-danger btn-sm text-white mr-2 delete">Delete</a>
                               @endif
                                @if (!empty(Helper::getpermission('_patients--edit')))
                                <a href="{{route('patients.edit',$row->patient_id)}}" class="btn btn-info btn-sm text-white mr-2 edit">Edit</a>
                               @endif
                            @endif


                            </td>
                            

                        </tr>
                    @endforeach

                </tbody>
                
            </table>
           <span class="float-right"> {{$patients->links()}}</span>

        </div>
    </div>


{{-- models --}}

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
               <h4 style="text-align: center;margin-top:0px;">Patient Registration</h4>
            <div style="text-align: center;">
                <div style="height: 80px;border-bottom:1px solid black">
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
                                   <p>Department :<strong id="p_reg-dep"></strong></p>
                                   <p>Patient Register Number :<strong id="p_reg-no"></strong></p>

                               </div>
                            </div>  
                </div>
  
             </div>
             <div class="text-center" style="height:590px;border-bottom:1px solid black"><span style="margin-top:4px">Impotant Note:....</span></div>
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
    <li class="breadcrumb-item active" aria-current="page">Patients</li>
@endsection


@section('jquery')
<script src="{{asset('public/assets/plugins/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js')}}"></script>
<script src="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>




<script>
    $('body').on('click','.print',function() {
      $('#p_reg-patient').html( $(this).attr('data-patent'));
      $('#p_age').html($(this).attr('data-age'));
      $('#p_phone').html($(this).attr('data-phone'));
      $('#p_reg_date').html($(this).attr('data-date'));
      $('#p_reg-dep').html( $(this).attr('data-department'));
      $('#p_reg-no').html($(this).attr('data-no'));
      $('#print').modal('show');
    });
    $('#print').on('shown.bs.modal', function () {
        printDiv();
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
    $('#example').DataTable( {
    dom: 'Blfrtip',
    "bLengthChange": false,
    "pageLength": 50,  
    'paging':false,

    buttons: [
   {
       extend: 'pdf',
       footer: true,
       customize: function(doc) {
       doc.defaultStyle.fontSize = 8;
       doc.defaultStyle.width = "*";

       },     
       exportOptions: {
        columns: [1,2,3,4,5,6,7,8]
      }
   },
   {
       extend: 'print',
       footer: false,
       exportOptions: {
            columns: [1,2,3,4,5,6,7,8]
        }
   },
           
],
} );

$('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');

    $('.select2').select2({width: '100%',color: '#384364'});
    $(".alert").css('display', 'none');

</script>

@if(session()->has('notif'))
<script>
      $.growl.notice({
        message: "{{session()->get('notif')}}",
        title: 'Success !',
        position: {
            from: "top",
            align: "left"
        },
    });
  
</script>       
@endif
<script>
    
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
                    url:'{{url("patients")}}/'+id,
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
                      'Patient record has related data first delete related data',
                      'error'
                    )
                    }
                });
            }
          })
              
});
</script>


@endsection
