@extends('layouts.admin')
@section('css')
{{-- <link href="{{asset('public/assets/plugins/charts-c3/c3-chart.css')}}" rel="stylesheet"/> --}}
<link href="{{asset('public/assets/plugins/morris/morris.css')}}" rel="stylesheet"/>
@endsection
@section('title') Dashboard @endsection
@section('content')

<h4>Today Appinments</h4>
<div class="column">
    <div class="row column">
       @foreach($app as $row)
        <div class="col-lg-5 col-md-6">
        <div class="card">
           <div class="card-body text-center">
              
               <div class="card-category bg-primary text-white">Dr.{{$row->f_name . ' ' . $row->l_name }} <br>{{$row->department_name }} <br> {{$row->date}}</div>

               <ul class="list-unstyled leading-loose " >
                   @foreach(helper::getappoinmentdata($row->emp_id,$row->date) as $detail)
                   <li id="row{{$detail->app_id}}"> <small> Patient:  {{$detail->p_f_name . ' ' . $detail->p_l_name}}|{{'APP-N-'.$detail->app_number}} |Time:{{$detail->time}}
             
                           <i style="font-size: 16px;cursor: pointer;"  data-toggle="modal" data-target="#show" data-id="{{$detail->app_id}}" class="ti-eye text-success  show"></i>
                               
                   </small></li>
                   @endforeach  

               </ul>
           </div>
       </div>
     </div>
    @endforeach  
   </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-primary img-card box-primary-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$patient}}</h2>
                        <p class="text-white mb-0">Total Patient</p>
                    </div>
                    <div class="ml-auto"> <i class="fa fa-hotel text-white fs-30 mr-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-success img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$opd}}</h2>
                        <p class="text-white mb-0">Total OPDs Patient</p>
                    </div>
                    <div class="ml-auto"><i class="fa fa-user text-white fs-30 mr-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-info img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$employee}}</h2>
                        <p class="text-white mb-0">Total Docter</p>
                    </div>
                    <div class="ml-auto"> <i class="fa fa-user-md text-white fs-30 mr-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card bg-danger img-card box-danger-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{$department}}</h2>
                        <p class="text-white mb-0">Total Department</p>
                    </div>
                    <div class="ml-auto"> <i class="fa fa-building text-white fs-30 mr-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
</div>




<div class="row">
  
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Appoinment Statistics</h3>
            </div>
            <div class="card-body">
                <div id="morrisBar6" class="chartsh"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">OPD Statistics</h3>
            </div>
            <div class="card-body">
                <div id="morrisBar7" class="chartsh"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Patient Statistics</h3>
            </div>
            <div class="card-body">
                <div id="morrisBar5" class="chartsh"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Finance Statistics</h3>
            </div>
            <div class="card-body">
                <div id="morrisBar8" class="chartsh"></div>
            </div>
        </div>
    </div>
</div>






<div id="show" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Appoinments Detail</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="details">

                </div>

            </div><!-- modal-body -->
        </div>
    </div><!-- MODAL DIALOG -->
</div>

@endsection

@section('directory')
<li class="breadcrumb-item active" aria-current="page">Home</li>
@endsection

@section('jquery')
<script src="{{url('public/assets/plugins/chart/Chart.bundle.js')}}"></script>
<script src="{{url('public/assets/plugins/morris/raphael-min.js')}}"></script>
<script src="{{url('public/assets/plugins/morris/morris.js')}}"></script>
<script>
    var day_data = [
		<?php echo $chart_data?>}];
    new Morris.Bar({
		element: 'morrisBar5',
		data: day_data,
		xkey: 'month',
		ykeys: ['Patient'],
		labels: ['Total Patient'],
		barColors: ['#6ab2f5'],
		xLabelAngle: 0
	});  
// 2
var day_data = [
		<?php echo $chart_data1?>}];
    new Morris.Bar({
		element: 'morrisBar6',
		data: day_data,
		xkey: 'month',
		ykeys: ['Appoinment'],
		labels: ['Total Appoinment'],
		barColors: ['#6610f2'],
		xLabelAngle: 0
	});  
// 3
var day_data = [
		<?php echo $chart_data2?>}];
    new Morris.Bar({
		element: 'morrisBar7',
		data: day_data,
		xkey: 'month',
		ykeys: ['OPD'],
		labels: ['Total OPD Patients'],
		barColors: ['#53cf70'],
		xLabelAngle: 0
	});  
    // 4
    var day_data = [
		<?php echo $chart_data3?>}];
    new Morris.Bar({
		element: 'morrisBar8',
		data: day_data,
		xkey: 'month',
		ykeys: ['Expense','Income'],
		labels: ['Expense','Income'],
		barColors: ['#dc3545','#8ab2f5'],
		xLabelAngle: 0
	});  
</script>

<script>
    
    $('body').on('click', '.show', function() {
            id = ($(this).attr('data-id'));
            url = '{{ url("appoinments") }}/' + id;
            var Hdata = "";
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                  $('.details').html(data);
                },
                error: function() {}
            })

        });
</script>
@endsection