@extends('layouts.admin')
@section('title') Settings @endsection

@section('css')
<link href="{{asset('public/assets/crop/cropper.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />


@endsection
@section('content')
@php
    $agent = new \Jenssegers\Agent\Agent;
    use Carbon\Carbon;
@endphp
    <div class="row">
        <div class="col-lg-3 col-xl-3 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc pt-5">
                            <div class="wideget-user-img">
                                @if (empty(Auth()->user()->profile_photo_path))
                                <img src="{{url('public/assets/images/icon.jpg')}}" alt="user" style="width:150px;height:100px;border-radius:50px;" >
                               @else
                                   <img src="{{url('storage/app/avatar')}}/{{Auth()->user()->profile_photo_path}}" alt="user" style="width:150px;height:90px;border-radius:50px;">
                               @endif

                            </div>
                            <div class="user-wrap">
                                <h3 class="pro-user-username text-dark">{{ Auth()->user()->name }}</h3>
                                <h6 class="text-muted mb-2">
                                    @php
                                        $role = Helper::getNameRole(Auth()->user()->role_id);
                                        echo $role[0]->name;
                                    @endphp
                                </h6>
                                <div class="wideget-user-rating">
                                    <a href="#"><i class="fa fa-star text-warning"></i></a>
                                    <a href="#"><i class="fa fa-star text-warning"></i></a>
                                    <a href="#"><i class="fa fa-star text-warning"></i></a>
                                    <a href="#"><i class="fa fa-star text-warning"></i></a>
                                    <a href="#"><i class="fa fa-star-o text-warning"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>


        </div>
        <div class="col-lg-8 col-xl-9 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profile  </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-12 col-md-12 userprofile">
                            <div class="userpic mb-2">
                                @if (empty(Auth()->user()->profile_photo_path))
                                <img src="{{url('public/assets/images/icon.jpg')}}" alt="user" style="width:150px;height:100px;border-radius:50px;" >
                               @else
                                   <img src="{{url('storage/app/avatar')}}/{{Auth()->user()->profile_photo_path}}" alt="user" style="width:150px;height:100px;border-radius:50px;">
                               @endif
                            </div>
                            <p class="text-center">{{ Auth()->user()->name }},   
                                @php
                                $role = Helper::getNameRole(Auth()->user()->role_id);
                                echo $role[0]->name;
                            @endphp</p>
                            <div class="text-center">
                                <form method="post">
                                    <input type="file" name="image" class="image d-none" accept="image/*">
                                </form>

                                <a href="#" class="btn btn-primary mt-1 select_photo"><i class="fe fe-camera  mr-1"></i>Change Photo</a><br>
                            </div>
                        </div>
                      
                    </div><hr>

                    <div class="row">
                        <div class="col-xl-6 col-lg-12 col-md-12">
                            <form class="password" action="{{url('setting/password')}}" method="post">
                                @csrf
                                <h4>Change Password</h4>
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" class="form-control"  name="current_password">
                                    {!!$errors->first('current_password', '<small class="text-danger ml-1">:message</small>')!!}

                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="new_password">
                                    {!!$errors->first('new_password', '<small class="text-danger ml-1">:message</small>')!!}

                                </div>
                                <div class="form-group mb-0">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control"   name="password_confirmation">
                                    {!!$errors->first('password_confirmation', '<small class="text-danger ml-1">:message</small>')!!}

                                </div>                                
                                <button  type="submit" class="btn btn-primary mt-1">Save</button>
                            </form>
                        </div>
                        <div class="col-xl-6 col-lg-12 col-md-12">
                            <h4>Change Details</h4>
                            <form method="post" class="detail" action="{{url('setting/change_detail')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputname">User Name</label>
                                    <input type="text" name="username" class="form-control" value="{{Auth()->user()->name}}" placeholder="User Name">
                                    {!!$errors->first('username', '<small class="text-danger ml-1">:message</small>')!!}

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" name="email_address" class="form-control" value="{{Auth()->user()->email}}" placeholder="email address">
                                    {!!$errors->first('email_address', '<small class="text-danger ml-1">:message</small>')!!}
                                </div>
                                <button  type="submit" class="btn btn-primary mt-1">Save</button>
                            </form>
                        </div>
                    </div><hr>
                    <h4>Two Factor Authentication</h4>
                    <div class="row">
                        <div class="col-md-6">
                         <form action="{{url('user/two-factor-authentication')}}" method="post">
                            @csrf
                            @if(Auth::user()->two_factor_secret)
                            @method('delete')
                            <h5 class="p-3">Bar Code</h5>

                           <div class="pl-4 pr-4 pt-2 pb-3"> {!!Auth::user()->twoFactorQrCodeSvg()!!}  </div> 
                            <button class="btn btn-danger btn-block">Disable</button>
                              @else
                            <button class="btn btn-success mt-2 btn-block">Enable</button>
                            @endif 
                       </form>
                    </div>
                    <div class="col-md-6">
                      @if(Auth::user()->two_factor_secret)
                        <h5 class="p-3">Recovery Codes</h5>
                      <div id="recovery_code" > 
                        @foreach(json_decode(decrypt(Auth::user()->two_factor_recovery_codes)) as $row)
                       <p>{{$row}}</p>
                        @endforeach 
                        </div>

                        @endif
                  
                        <button id="show" class="btn btn-warning btn-block mt-2">Recovery Code</button>
                      </div>  
                  </div><hr>
                  <h3 class="card-title">Browser Session</h3>
                  <small>Manage and log out your active sessions on other browsers and devices</small>
                  <small>If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.</small>

                  <div class="mt-5 space-y-6">
    
                    <!-- Other Browser Sessions -->
                    @foreach ($sessions as $session)
                        <div class="flex items-center">
                            <div>
                                @php   $agent->setUserAgent($session->user_agent); @endphp
                                @if ($agent->isDesktop())
                                    <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-gray-500">
                                        <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                                    </svg>
                                @endif
                            </div>
                
                            <div class="ml-3">
                                <div class="text-sm text-gray-600">
                                    {{ $agent->platform() }} - {{ $agent->browser() }}
                                </div>
                
                                <div>
                                    <div class="text-xs text-gray-500">
                                        {{ $session->ip_address }},
                
                                        @if ($session->id === request()->session()->getId())
                                            <span class="text-green font-semibold">{{ __('This device') }}</span>
                                        @else
                                            {{ __('Last active') }} {{ Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <br>
                    <a href="{{url('deleteothersession')}}">
                        <button class="btn btn-gray">Log Out Other Browser Sessions</button>
                       </a>
                </div>
         

                </div>
              
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">  
                                <!--  default image where we will set the src via jquery-->
                                <img id="image">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">User Settings</li>
@endsection
@section('jquery')
<script src="{{asset('public/assets/crop/cropper.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/notify/js/jquery.growl.js') }}"></script>


@if(session()->has('success'))
<script>
  $(document).ready(function(){
    $.growl.notice({
        message:'{{session()->get("success")}}',
        title: 'Success !',
        });
  });
</script>
@endif 
@if(session()->has('error'))
<script>
  $(document).ready(function(){
    $.growl.error({
        message:'{{session()->get("error")}}',
        title: 'Error!',
        });
  });
</script>
@endif 

<script>
    $('.select_photo').on('click', function() {
    $('.image').trigger('click');
});
</script>
<script type="text/javascript">
    $('#recovery_code').css('display','none');
    $('#show').click(function() {
       $('#recovery_code').toggle('slow');
    });
  </script>
  <script>

    var bs_modal = $('#modal');
    var image = document.getElementById('image');
    var cropper,reader,file;
   

    $("body").on("change", ".image", function(e) {
        var files = e.target.files;
        var done = function(url) {
            image.src = url;
            bs_modal.modal('show');
        };


        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    bs_modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });

    $("#crop").click(function() {
        canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 160,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
            var base64data = reader.result;
            var url="{{url('avatar-change')}}"; 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url:url,
                    data: {image: base64data},
                    success: function(data) { 
                        location.reload(true);
                        bs_modal.modal('hide');
                     
                    }
                });
            };
        });
    });

</script>
@endsection
