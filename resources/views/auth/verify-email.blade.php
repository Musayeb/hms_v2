
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
                <form class="login100-form validate-form" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-jet-validation-errors class="mb-4 text-danger" />  
                    @if (session('status'))
                          <div class="mb-4 font-medium text-sm text-success">
                              Verification link sent successfully
                          </div>
                    @else  <p class="text-center">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>@endif
            
                    <div class="container-login100-form-btn">
                        <button href="#" type="submit" class="login100-form-btn btn-primary">
                            Resend Verification Email
                        </button>
                        <div class=" mt-3">
                            {{-- <form method="POST" action=""> --}}
                                {{-- @csrf --}}
                              <a href="{{ route('logout') }}">
                                <button type="button" class="text-sm text-gray-600 hover:text-gray-900">
                                    {{ __('Logout') }}
                                </button>
                            </a>
                            {{-- </form> --}}
                        </div>
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

