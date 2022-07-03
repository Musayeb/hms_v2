@extends('layouts.admin')
@section('css')
    
@endsection
@section('title') Employee Create Option @endsection

@section('content')
    <div class="row justify-content-center ">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3 ">
            <div class="card p-4">
                <div class="card-body text-center">
                    <i class="fa fa-user-md text-danger fa-5x"></i>
                    <h2 class="mt-4 mb-2">Docter Registration</h2>
                    <p class="text-muted">This is stage Register only docter</p>
                    <a href="{{url('employees/create/docter')}}" class="btn btn-success">Register</a>

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3 mt-auto mb-auto">
            <div class="card p-4">
                <div class="card-body text-center">
                    <i class="fa fa-user-plus fa-5x  text-blue"></i>
                    <h2 class="mb-2 mt-4 ">Employee Registration</h2>
                    <p class="text-muted">This is stage Register managment employee </p>
                    <a href="{{route('employees.create')}}" class="btn btn-success">Register</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('directory')
<li class="breadcrumb-item active" aria-current="page">Employees </li>
<li class="breadcrumb-item active" aria-current="page">Employee Register Option</li>
@endsection