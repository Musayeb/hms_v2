@extends('layouts.admin')

@section('css')
<link href="{{asset('public/assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('title') Employee Create @endsection
@section('content')

    <div class="card justify-content-center">
        <div class="card-header">
            <h3 class="card-title">Employee Registration</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label >First Name <label class="text-danger">*</label></label>
                            <input type="text" class="form-control" name="first_name" @if ($errors->first('first_name')) style="border:1px solid red" @endif placeholder="First Name.." value="{{ old('first_name') }}">
                            {!! $errors->first('first_name', '<small class="text-danger">:message</small>') !!}

                        </div>
                        <div class="form-group">
                            <label >Last Name <label class="text-danger">*</label></label>
                            <input type="text" class="form-control" value="{{ old('last_name') }}" name="last_name" @if ($errors->first('last_name')) style="border:1px solid red" @endif
                                placeholder="Last Name..">
                            {!! $errors->first('last_name', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Father Name <label class="text-danger">*</label></label>
                            <input type="text" value="{{ old('father_name') }}" class="form-control " @if ($errors->first('father_name')) style="border:1px solid red" @endif name="father_name"
                                placeholder="Father Name..">
                            {!! $errors->first('father_name', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Mother Name <small>(Optional)</small></label>
                            <input type="text" value="{{ old('mother_name') }}" class="form-control " name="mother_name"
                                placeholder="Mother Name..">
                  
                        </div>
                        <div class="form-group">
                            <label >ID/Passport No. <label class="text-danger">*</label></label>
                            <input type="text" value="{{ old('id_or_passport') }}" class="form-control " @if ($errors->first('id_or_passport')) style="border:1px solid red" @endif name="id_or_passport"
                                placeholder="ID/Passport..">
                            {!! $errors->first('id_or_passport', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Date of Birth <label class="text-danger">*</label></label>
                            <input type="Date" class="form-control" value="{{ old('date_of_birth') }}"
                                name="date_of_birth" @if ($errors->first('date_of_birth')) style="border:1px solid red" @endif
                                placeholder="Date of Birth..">
                            {!! $errors->first('date_of_birth', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Gender <label class="text-danger">*</label></label>
                            <select @if ($errors->first('gender')) style="border:1px solid red" @endif class="form-control"
                                name="gender">
                                <option value="" selected disabled>select gender</option>
                                <option @if (old('gender') == 'Male') selected @endif>Male</option>
                                <option @if (old('gender') == 'Female') selected @endif>Female</option>
                                <option @if (old('gender') == 'Rather not say') selected @endif> Rather not say</option>
                            </select>
                            {!! $errors->first('gender', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >phone number <label class="text-danger">*</label></label>
                            <input type="phone" class="form-control phone" value="{{ old('phone_number') }}"
                                name="phone_number" @if ($errors->first('phone_number')) style="border:1px solid red" @endif
                                placeholder="Phone Number..">
                            {!! $errors->first('phone_number', '<small class="text-danger">:message</small>') !!}
                        </div>

                    </div>{{-- end of row --}}
                    <div class="col-lg-4">

                        <div class="form-group">
                            <label >Email Address <label class="text-danger">*</label></label>
                            <input type="email" class="form-control " value="{{ old('email') }}" name="email" @if ($errors->first('email')) style="border:1px solid red" @endif
                                placeholder="Email Address..">
                            {!! $errors->first('email', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Marital Status <label class="text-danger">*</label></label>
                            <select @if ($errors->first('marital_status')) style="border:1px solid red" @endif name="marital_status"
                                class="form-control" autocomplete="off">
                                <option value="" disabled selected>Select</option>
                                <option value="Single" @if (old('marital_status') == 'Single') selected @endif>Single</option>
                                <option value="Married" @if (old('marital_status') == 'Married') selected @endif>Married</option>
                                <option value="Widowed" @if (old('marital_status') == 'Widowed') selected @endif>Widowed</option>
                                <option value="Separated" @if (old('marital_status') == 'Separated') selected @endif>Separated</option>
                                <option value="Not Specified" @if (old('marital_status') == 'Not Specified') selected @endif>Not Specified</option>
                            </select>
                            {!! $errors->first('marital_status', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Department <label class="text-danger">*</label></label>
                            <select name="department" class="form-control" @if ($errors->first('department')) style="border:1px solid red" @endif autocomplete="off">
                                <option value="" disabled selected>Select</option>
                                @foreach ($dep as $item)
                                    <option value="{{ $item->dep_id }}" @if (old('department') == $item->dep_id) selected @endif>{{ $item->department_name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('department', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Position <label class="text-danger">*</label></label>
                            <input value="{{ old('position') }}" type="text" class="form-control" @if ($errors->first('position')) style="border:1px solid red" 
                            @endif name="position" placeholder="position..">
                            {!! $errors->first('position', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Salary <label class="text-danger">*</label></label>
                            <input value="{{ old('salary') }}" type="number" class="form-control" @if ($errors->first('salary')) style="border:1px solid red" @endif name="salary" placeholder="Salary..">
                            {!! $errors->first('salary', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label >Current Address <label class="text-danger">*</label></label>
                            <input type="text" name="current_address"  @if ($errors->first('current_address')) style="border:1px solid red" @endif class="form-control"
                                placeholder="Address.." value="{{ old('current_address') }}">
                            {!! $errors->first('current_address', '<small class="text-danger">:message</small>') !!}
                        </div>


                        <div class="form-group">
                            <label >Permenent Address <label class="text-danger">*</label></label>
                            <input type="text" name="permenent_address"  @if ($errors->first('permenent_address')) style="border:1px solid red" @endif class="form-control"
                                placeholder="Address.." value="{{ old('permenent_address') }}">
                            {!! $errors->first('permenent_address', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Date of Join <label class="text-danger">*</label></label>
                            <input type="Date" class="form-control" value="{{ old('date_of_birth') }}"
                                name="date_of_join" @if ($errors->first('date_of_join')) style="border:1px solid red" @endif
                                placeholder="Date of Birth..">
                            {!! $errors->first('date_of_join', '<small class="text-danger">:message</small>') !!}
                        </div>
                  
                    </div>{{-- end of col --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >End of Contract <label class="text-danger">*</label></label>
                            <input type="Date" class="form-control" value="{{ old('end_of_contract') }}"
                                name="end_of_contract" @if ($errors->first('end_of_contract')) style="border:1px solid red" @endif>
                            {!! $errors->first('end_of_contract', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label>TIN Number</label>
                            <input type="text" class="form-control" value="{{ old('tin_number') }}"name="tin_number" placeholder="TIN Number">
                        </div>
                        <div class="form-group">
                            <label >Emergency Contact <label class="text-danger">*</label></label>
                            <input type="phone" value="{{ old('emergency_contact') }}" class="form-control phone" @if ($errors->first('emergency_contact')) style="border:1px solid red" @endif
                                name="emergency_contact" placeholder="Phone Number..">
                            {!! $errors->first('emergency_contact', '<small class="text-danger">:message</small>') !!}
                        </div>
                        
                        <div class="form-group">
                            <label >Relationship <label class="text-danger">*</label></label>
                            <input type="text" value="{{old('relationship')}}"  class="form-control" @if ($errors->first('relationship')) style="border:1px solid red" @endif name="relationship" placeholder="Relationship..">
                            {!! $errors->first('relationship', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Photo<small>(Optional) (MAX 2MB)</small></label>
                            <input type="file" name="photo" class="dropify" data-max-file-size="2M" />
                            {!! $errors->first('photo', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Upload CV <small>(Optional) (MAX 2MB)</small></label>
                            <input type="file" name="cv" class="dropify" data-max-file-size="2M" />
                            {!! $errors->first('cv', '<small class="text-danger">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label >Upload Contract  <small>(Optional) (MAX 2MB)</small></label>
                            <input type="file" name="contract" class="dropify" data-max-file-size="2M" />
                            {!! $errors->first('contract', '<small class="text-danger">:message</small>') !!}
                        </div>
                    </div>{{-- end of col --}}
                </div>{{-- end of row --}}
                <div class="form-group text-right"><hr>
                    <button type="submit" class="btn btn-primary">Register</button>
                    <button type="reset" class="btn btn-danger">Reset</button>

                </div>
            </form>

        </div>
    </div>
@endsection



@section('directory')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('employees') }}">Employees</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('jquery')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script src="{{asset('public/assets/plugins/fileuploads/js/fileupload.js')}}"></script>
    {{-- <script src="{{asset('public/assets/plugins/fileuploads/js/file-upload.js')}}"></script> --}}
    <script>
        $('.dropify').dropify({
            messages: {
            'default': '',
            'error':   'Ooops, something wrong happended.'
            },
            error: {
                'fileSize': 'The file size is too big (2M max).'
            },
            height:51,
        });
            
        $(document).ready(function() {
            $('.phone').inputmask('(0)-999-999-999');
            // $('.age').inputmask('999');
        });
    </script>
@endsection
