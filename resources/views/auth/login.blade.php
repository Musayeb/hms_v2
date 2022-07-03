
@extends('layouts.auth')
@section('content')    
<div class="page h-100">
    <div class="">
        <!-- CONTAINER OPEN -->
        <div class="col col-login mx-auto">
            <div class="text-center">
                <img src="public/assets/images/brand/logo-white.png" class="header-brand-img" alt="">
            </div>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-6">
                <form class="login100-form validate-form" method="POST"  action="{{ route('login') }}">
                    @csrf
                    <x-jet-validation-errors class="mb-4 text-danger" />  
                    @if (session('status'))
                          <div class="mb-4 font-medium text-sm text-green-600">
                              {{ session('status') }}
                          </div>
                    @else  <p class="text-center">Please confirm that you are visiting </p>@endif

                    <span class="login100-form-title">
                        Login
                    </span>
                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                        <input class="input100" type="email" name="email" value="{{old('email')}}" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="zmdi zmdi-email" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100" type="password" name="password"  autocomplete="current-password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="check-box">
                        <input type="checkbox" name="remember" id="type-1"> 
                        <label for="type-1">Remember me</label>
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 float-right" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                       @endif
                    </div>
            
            
                    <div class="container-login100-form-btn">
                        <button href="#" type="submit" class="login100-form-btn btn-primary">
                            Login
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
    {{-- <script>
        $('#show').mousedown(function(){
            $('#password').attr('type','text');
        });
        $('#show').mouseup(function(){
            $('#password').attr('type','password');
        });
    </script> --}}
@endsection

