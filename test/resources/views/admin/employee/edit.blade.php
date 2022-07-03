@extends('layouts.admin')

@section('css')
<link href="{{asset('public/assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('title') Employee Edit @endsection

@section('content')

    <div class="card justify-content-center">
        <div class="card-header">
            <h3 class="card-title">Edit Employee</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{ url('employees_update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label >First Name <label class="text-danger">*</label></label>
                            <input type="text" class="form-control" name="first_name" @if ($errors->first('first_name')) style="border:1px solid red" @endif placeholder="First Name.." value="{{$emp->f_name}}">
                            <input type="hidden" name="emp" value="{{$emp->emp_id}}">
                            {!! $errors->first('first_name', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Last Name <label class="text-danger">*</label></label>
                            <input type="text" class="form-control" value="{{$emp->l_name}}" name="last_name" @if ($errors->first('last_name')) style="border:1px solid red" @endif
                                placeholder="Last Name..">
                            {!! $errors->first('last_name', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Father Name <label class="text-danger">*</label></label>
                            <input type="text" value="{{$emp->father_name}}" class="form-control " @if ($errors->first('father_name')) style="border:1px solid red" @endif name="father_name"
                                placeholder="Father Name..">
                            {!! $errors->first('father_name', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Mother Name <small>(Optional)</small></label>
                            <input type="text" value="{{$emp->mother_name}}" class="form-control " name="mother_name"
                                placeholder="Mother Name..">
                  
                        </div>
                        <div class="form-group">
                            <label >ID/Passport No. <label class="text-danger">*</label></label>
                            <input type="text" value="{{$emp->passport_id}}" class="form-control " @if ($errors->first('id_or_passport')) style="border:1px solid red" @endif name="id_or_passport"
                                placeholder="ID/Passport..">
                            {!! $errors->first('id_or_passport', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Date of Birth <label class="text-danger">*</label></label>
                            <input type="Date" class="form-control" value="{{$emp->date_of_birth}}"
                                name="date_of_birth" @if ($errors->first('date_of_birth')) style="border:1px solid red" @endif
                                placeholder="Date of Birth..">
                            {!! $errors->first('date_of_birth', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Gender <label class="text-danger">*</label></label>
                            <select @if ($errors->first('gender')) style="border:1px solid red" @endif class="form-control"
                                name="gender">
                                <option value="" selected disabled>select gender</option>
                                <option @if ($emp->gender == 'Male') selected @endif>Male</option>
                                <option @if ($emp->gender == 'Female') selected @endif>Female</option>
                                <option @if ($emp->gender == 'Rather not say') selected @endif> Rather not say</option>
                            </select>
                            {!! $errors->first('gender', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >phone number <label class="text-danger">*</label></label>
                            <input type="phone" class="form-control phone" value="{{ $emp->phone_number}}"
                                name="phone_number" @if ($errors->first('phone_number')) style="border:1px solid red" @endif
                                placeholder="Phone Number..">
                            {!! $errors->first('phone_number', '<small class="text-danger">:message</small>') !!}
                        </div>

                    </div>{{-- end of row --}}
                    <div class="col-lg-4">

                        <div class="form-group">
                            <label >Email Address <label class="text-danger">*</label></label>
                            <input type="email" class="form-control " value="{{ $emp->email }}" name="email" @if ($errors->first('email')) style="border:1px solid red" @endif
                                placeholder="Email Address..">
                            {!! $errors->first('email', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Marital Status <label class="text-danger">*</label></label>
                            <select @if ($errors->first('marital_status')) style="border:1px solid red" @endif name="marital_status"
                                class="form-control" autocomplete="off">
                                <option value="" disabled selected>Select</option>
                                <option value="Single" @if ($emp->m_status == 'Single') selected @endif>Single</option>
                                <option value="Married" @if ($emp->m_status == 'Married') selected @endif>Married</option>
                                <option value="Widowed" @if ($emp->m_status == 'Widowed') selected @endif>Widowed</option>
                                <option value="Separated" @if ($emp->m_status == 'Separated') selected @endif>Separated</option>
                                <option value="Not Specified" @if ($emp->m_status == 'Not Specified') selected @endif>Not Specified</option>
                            </select>
                            {!! $errors->first('marital_status', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Department <label class="text-danger">*</label></label>
                            <select name="department" class="form-control" @if ($errors->first('department')) style="border:1px solid red" @endif autocomplete="off">
                                <option value="" disabled selected>Select</option>
                                @foreach ($dep as $item)
                                    <option value="{{ $item->dep_id }}" @if ($emp->dep_id == $item->dep_id) selected @endif>{{ $item->department_name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('department', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Position <label class="text-danger">*</label></label>
                            <input value="{{ $emp->position }}" type="text" class="form-control" @if ($errors->first('position')) style="border:1px solid red" 
                            @endif name="position" placeholder="position..">
                            {!! $errors->first('position', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Salary <label class="text-danger">*</label></label>
                            <input value="{{ $emp->salary }}" type="number" class="form-control" @if ($errors->first('salary')) style="border:1px solid red" @endif name="salary" placeholder="Salary..">
                            {!! $errors->first('salary', '<small class="text-danger">:message</small>') !!}
                        </div>
                        @if(!empty($emp->fees))
                        <div class="form-group">
                            <label >Fees <label class="text-danger">*</label></label>
                            <input value="{{ $emp->fees }}" type="number" class="form-control" @if ($errors->first('fees')) style="border:1px solid red" @endif name="fees" placeholder="Fees..">
                            {!! $errors->first('fees', '<small class="text-danger">:message</small>') !!}
                        </div>
                        @endif
                        

                        <div class="form-group">
                            <label >Current Address <label class="text-danger">*</label></label>
                            <input type="text" name="current_address"  @if ($errors->first('current_address')) style="border:1px solid red" @endif class="form-control"
                                placeholder="Address.." value="{{$emp->current_address}}">
                            {!! $errors->first('current_address', '<small class="text-danger">:message</small>') !!}
                        </div>


                        <div class="form-group">
                            <label >Permenent Address <label class="text-danger">*</label></label>
                            <input type="text" name="permenent_address"  @if ($errors->first('permenent_address')) style="border:1px solid red" @endif class="form-control"
                                placeholder="Address.." value="{{ $emp->permanent_address }}">
                            {!! $errors->first('permenent_address', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Date of Join <label class="text-danger">*</label></label>
                            <input type="Date" class="form-control" value="{{ $emp->date_of_join }}"
                                name="date_of_join" @if ($errors->first('date_of_join')) style="border:1px solid red" @endif
                                placeholder="Date of Birth..">
                            {!! $errors->first('date_of_join', '<small class="text-danger">:message</small>') !!}
                        </div>

                    </div>{{-- end of col --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Emergency Contact <label class="text-danger">*</label></label>
                            <input type="phone" value="{{ $emp->emergency_contact }}" class="form-control phone" @if ($errors->first('emergency_contact')) style="border:1px solid red" @endif
                                name="emergency_contact" placeholder="Phone Number..">
                            {!! $errors->first('emergency_contact', '<small class="text-danger">:message</small>') !!}
                        </div>
                        
                        <div class="form-group">
                            <label >Relationship <label class="text-danger">*</label></label>
                            <input type="text" value="{{$emp->relationship}}"  class="form-control" @if ($errors->first('relationship')) style="border:1px solid red" @endif name="relationship" placeholder="Relationship..">
                            {!! $errors->first('relationship', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Photo<small>(Optional) (MAX 2MB,jpg,jpeg,png)</small></label>
                            <input type="file" id="photo" name="photo" class="dropify" data-max-file-size="2M" />
                            {!! $errors->first('photo', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Upload CV <small>(Optional) (MAX 2MB ,pdf)</small></label>
                            <input type="file" name="cv" id="cv" class="dropify" data-max-file-size="2M" />
                            {!! $errors->first('cv', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Upload Contract  <small>(Optional) (MAX 2MB ,pdf)</small></label>
                            <input type="file" name="contract" id="contract" class="dropify" data-max-file-size="2M" />
                            {!! $errors->first('contract', '<small class="text-danger">:message</small>') !!}
                        </div>
                    </div>{{-- end of col --}}
                </div>{{-- end of row --}}
                <div class="form-group text-right"><hr>
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>

        </div>
    </div>
@endsection



@section('directory')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('employees') }}">Employees</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('jquery')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script src="{{asset('public/assets/plugins/fileuploads/js/fileupload.js')}}"></script>
    {{-- <script src="{{asset('public/assets/plugins/fileuploads/js/file-upload.js')}}"></script> --}}
    <script>
          @if(!empty($emp->photo))
            $("#photo").attr("data-default-file", "{{url('storage/app')}}/{{$emp->photo}}");
         @endif
         @if(!empty($emp->cv_file))
            $("#cv").attr("data-default-file", "{{url('storage/app')}}/{{$emp->cv_file}}");
         @endif
         @if(!empty($emp->contract_file))
            $("#contract").attr("data-default-file", "{{url('storage/app')}}/{{$emp->contract_file}}");
         @endif
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': 'The file size is too big (2M max).'
            },
            height:96,
        });
            
        $(document).ready(function() {
            $('.phone').inputmask('(0)-999-999-999');
            // $('.age').inputmask('999');
        });
    </script>
@endsection
