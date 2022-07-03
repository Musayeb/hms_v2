
@extends('layouts.auth')
@section('content')    



<div class="page h-100">
    <div class="">
        <!-- CONTAINER OPEN -->
        <div class="col col-login mx-auto">
            <div class="text-center">
                <img src="{{url('public/assets/images/brand/logo-white.png')}}" class="header-brand-img" alt="">
            </div>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-6">
                <form class="login100-form validate-form" method="POST" action="{{ route('password.confirm') }}">
                    <p class="text-center">This is a secure area of the application. Please confirm your password before continuing.</p>
                    @csrf
                    <div class="wrap-input100 validate-input" data-validate = "Password is required" >
                        <input class="input100" type="password" id="password" name="password"  autocomplete="current-password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                        </span>
                    </div>
            
                    <div class="container-login100-form-btn">
                        <button  type="submit" class="login100-form-btn btn-primary">
                            Confirm
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection

@section('jquery')

@endsection

