@extends('layouts.admin')

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />

@endsection
@section('title') OPD Report @endsection

@section('content')
<form method="get" action="{{url('filter-opd-report')}}">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-6">
                <h4>Date</h4>
                <div class="btn-list">
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                           <input type="text" class="form-control pull-right" name="date" id="reservation" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Doctor</h4>
                <select name="docter" class="form-control" required>
                    <option value="" selected disabled>select</option>
                    @foreach ($docter as $emp )
                        <option value="{{$emp->emp_id}}">{{$emp->f_name.' '. $emp->l_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class=" text-right">
            <button type="submit" class="btn btn-success">Filter</button>
        </div>
    </form>


        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main"
                id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PID</th>
                        <th>Patient</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Doctor</th>
                        <th>Register Date </th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter=1; @endphp
                    @if(!empty($opd))
                    @foreach ($opd as $row)
             
                        <tr id="">
                            <td>{{ $counter++ }}</td>
                            <td>OPD-{{ $row->patient_id }}</td>
                            <td>
                             {{ $row->o_f_name.' '.$row->o_l_name }}
                            </td>
                            <td>{{$row->age}}</td>
                            <td>{{ $row->gender }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->department_name }}</td>
                            <td>{{ $row->f_name.' '.$row->l_name }}</td>
                            <td>{{ $row->updated_at }}</td>
                        </tr>
                    @endforeach

    
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total OPD</th>
                            <th>{{$counter-1}}</th>
                        </tr>
                    </tfoot>
                    @endif
                </tbody>
            </table>
      
        </div>
    </div>
@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Report</li>
    <li class="breadcrumb-item active" aria-current="page">OPD</li>
@endsection

@section('jquery')
    <script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script src="{{ asset('public/assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>

    <script>
        	$('#reservation').daterangepicker();
        $('#example').DataTable({
            dom: 'Blfrtip',
            "bLengthChange": false,
            "pageLength": 50,
            'paging':false,
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.defaultStyle.width = "100%";

                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8],
                    }
                },
                {
                    extend: 'print',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8]
                    }
                },

            ],
        });
        $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
    </script>

@endsection
