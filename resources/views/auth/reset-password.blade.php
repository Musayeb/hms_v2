
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
                <form class="login100-form validate-form" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                        <input class="block mt-1 w-full input100" type="email" name="email" value="@php echo  old('email', $request->email) @endphp" required autofocus readonly>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="zmdi zmdi-email" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100"  placeholder="Password"  id="password"  type="password" name="password" required autocomplete="new-password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100"  type="password" name="password_confirmation"  autocomplete="new-password" placeholder="Confirm Password" id="password_confirmation">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                        </span>
                    </div>
            
            
                    <div class="container-login100-form-btn">
                        <button href="#" type="submit" class="login100-form-btn btn-primary">
                            Reset Password
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

