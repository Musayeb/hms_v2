@extends('layouts.admin')
@section('title') filter @endsection

@section('css')
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />

@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="expanel expanel-primary">
                <div class="expanel-heading">
                    <h3 class="expanel-title1">Select the Filter Percentage</h3>
                </div>
                <div class="expanel-body p-5">
                    <form action="{{ url('filter-change') }}" method="post">
                        @csrf
                        <div class="d-flex ">
                            <div class="input-group wd-150">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-percent tx-16 lh-0 op-6"></i>
                                    </div><!-- input-group-text -->
                                </div><!-- input-group-prepend -->
                                <input class="form-control ui-timepicker-input" name="percentage" id="tp3"
                                    placeholder="Set Percentage" type="text" autocomplete="off" required max="90">
                                <button type="submit" class="btn btn btn-primary br-tl-0 br-bl-0" id="setTimeButton">Set
                                    Percentage</button>
                            </div><!-- input-group -->

                        </div>
                    </form>
                    <div class="pt-4">
                        <h5>Percentage: &nbsp;&nbsp;<strong
                                class=" h4 text-white badge badge-danger">%{{ $filter[0]->percentage }}</strong></h5>
                        <h5>Edited By: &nbsp;&nbsp;<strong
                                class=" h4 text-white badge badge-danger">{{ $filter[0]->email }}</strong></h5>
                        <h5>Date : &nbsp; &nbsp;<strong
                                class=" h4 text-white badge badge-danger">{{ $filter[0]->created_at }}</strong></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('jquery')
    <script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>

    @if (session()->has('notif'))
        <script>
            $(document).ready(function() {
                return $.growl.notice({
                    message: '{{ session()->get("notif") }}',
                    title: 'Success !',
                });
            });
        </script>
    @endif
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Filter</li>
@endsection
