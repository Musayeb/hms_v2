@extends('layouts.admin')

@section('css')

@endsection

@section('title') Patients Edit @endsection

@section('content')

        <div class="card justify-content-center">
            <div class="card-header">
                <h3 class="card-title">Patient Edit</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{url('patients_update')}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">First Name <label class="text-danger">*</label></label>
                                <input type="text" class="form-control" name="first_name" @if ($errors->first('first_name')) style="border:1px solid red" @endif placeholder="First Name.." value="{{$patient->f_name}}">
                                {!! $errors->first('first_name', '<small class="text-danger">:message</small>') !!}
                                <input type="hidden" value="{{$patient->patient_id}}" name="pat_id">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name <label class="text-danger">*</label></label>
                                <input type="text" class="form-control" value="{{$patient->l_name}}" name="last_name" @if ($errors->first('last_name')) style="border:1px solid red" @endif  placeholder="Last Name..">
                                {!! $errors->first('last_name', '<small class="text-danger">:message</small>') !!}
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date of Birth <label class="text-danger">*</label></label>
                                <input type="Date" class="form-control" value="{{$patient->dob}}" name="date_of_birth" @if ($errors->first('date_of_birth')) style="border:1px solid red" @endif  placeholder="Date of Birth..">
                                {!! $errors->first('date_of_birth', '<small class="text-danger">:message</small>') !!}
                         
                            </div>
                            <div class="form-group">
                                <label class="form-label">Gender <label class="text-danger">*</label></label>
                                <select @if ($errors->first('gender'))  style="border:1px solid red" @endif class="form-control" name="gender">
                                    <option value="" selected disabled>select gender</option>
                                    <option  @if(old("gender")=="Male") selected @endif @if($patient->gender=="Male") selected @endif  >Male</option>
                                    <option  @if(old("gender")=="Female") selected @endif  @if($patient->gender=="Female") selected @endif>Female</option>
                                    <option  @if(old("gender")=="Rather not say") selected @endif  @if($patient->gender=="Rather not say") selected @endif> Rather not say</option>
                                </select>
                                {!! $errors->first('gender', '<small class="text-danger">:message</small>') !!}
                         
                            </div>
                            <div class="form-group">
                                <label class="form-label">phone number <label class="text-danger">*</label></label>
                                <input  type="phone"  class="form-control phone" value="{{$patient->phone_number}}"  name="phone_number" @if ($errors->first('phone_number')) style="border:1px solid red" @endif placeholder="Phone Number..">
                                {!! $errors->first('phone_number', '<small class="text-danger">:message</small>') !!}
                         
                            </div>
                            <div class="form-group">
                                <label class="form-label">Blood Group <label class="text-danger">*</label></label>
                                <select @if ($errors->first('blood_group')) style="border:1px solid red" @endif class="form-control" name="blood_group">
                                    <option value="" selected disabled>Select blood group</option>
                                    <option @if(old("blood_group")=="A+") selected @endif  @if($patient->blood_g=="A+") selected @endif>A+</option>
                                    <option @if(old("blood_group")=="B+") selected @endif @if($patient->blood_g=="B+") selected @endif>B+</option>
                                    <option @if(old("blood_group")=="A-") selected @endif @if($patient->blood_g=="A-") selected @endif> A-</option>
                                    <option @if(old("blood_group")=="B-") selected @endif @if($patient->blood_g=="B-") selected @endif> B-</option>
                                    <option @if(old("blood_group")=="O+") selected @endif @if($patient->blood_g=="O+") selected @endif> O+</option>
                                    <option @if(old("blood_group")=="O-") selected @endif @if($patient->blood_g=="O-") selected @endif> O-</option>
                                    <option @if(old("blood_group")=="AB+") selected @endif @if($patient->blood_g=="A+") selected @endif> AB+</option>
                                    <option @if(old("blood_group")=="AB-") selected @endif @if($patient->blood_g=="AB-") selected @endif> AB-</option>
                                </select>
                                {!! $errors->first('blood_group', '<small class="text-danger">:message</small>') !!}
                         
                            </div>
                            <div class="form-group">
                                <label class="form-label">Age <label class="text-danger">*</label></label>
                                <input type="text" value="{{$patient->age}}" class="form-control age" @if ($errors->first('age')) style="border:1px solid red" @endif name="age" placeholder="Age..">
                                {!! $errors->first('age', '<small class="text-danger">:message</small>') !!}
                         
                            </div>
                            <div class="form-group">
                                <label class="form-label">Marital Status <label class="text-danger">*</label></label>
                                <select @if ($errors->first('marital_status')) style="border:1px solid red" @endif name="marital_status" class="form-control" autocomplete="off">
                                    <option value="" disabled selected>Select</option>
                                    <option value="Single" @if(old("marital_status")=="Single") selected @endif @if($patient->marital_status=="Single") selected @endif>Single</option>
                                    <option value="Married" @if(old("marital_status")=="Married") selected @endif @if($patient->marital_status=="Married") selected @endif>Married</option>
                                    <option value="Widowed" @if(old("marital_status")=="Widowed") selected @endif @if($patient->marital_status=="Widowed") selected @endif>Widowed</option>
                                    <option value="Separated" @if(old("marital_status")=="Separated") selected @endif @if($patient->marital_status=="Separated") selected @endif>Separated</option>
                                    <option value="Not Specified" @if(old("marital_status")=="Not Specified") selected @endif @if($patient->marital_status=="Not Specified") selected @endif>Not Specified</option>
                                </select>
                                {!! $errors->first('marital_status', '<small class="text-danger">:message</small>') !!}

                            </div>
                     
                        </div>{{-- end of row --}}
                        <div class="col-lg-6">
                        
                            <div class="form-group">
                                <label class="form-label">Department <label class="text-danger">*</label></label>
                                <select  name="department" class="form-control" @if ($errors->first('department')) style="border:1px solid red" @endif autocomplete="off">
                                    <option value="" disabled selected>Select</option>
                                    @foreach ($dep as $item)
                                         <option value="{{$item->dep_id}}"  @if($patient->dep_id==$item->dep_id) selected @endif @if(old("department")==$item->dep_id) selected @endif>{{$item->department_name}}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('department', '<small class="text-danger">:message</small>') !!}

                            </div>
                            <div class="form-group">
                                <label class="form-label">Occupation <label class="text-danger">*</label></label>
                                <input value="{{$patient->occupation}}" type="text" class="form-control" @if ($errors->first('occupation')) style="border:1px solid red" @endif name="occupation"
                                    placeholder="Occupation..">
                                    {!! $errors->first('occupation', '<small class="text-danger">:message</small>') !!}
                                </div>

                            <div class="form-group">
                                <label class="form-label">Address <label class="text-danger">*</label></label>
                                <textarea  name="address" id="" cols="44" rows="1" @if ($errors->first('address')) style="border:1px solid red" @endif class="form-control"
                                    placeholder="Address..">{{$patient->address}}</textarea>
                                    {!! $errors->first('address', '<small class="text-danger">:message</small>') !!}

                            </div>

                            <div class="form-group">
                                <label class="form-label">Emergency Contact <label class="text-danger">*</label></label>
                                <input type="phone" value="{{$patient->emergency_contact}}" class="form-control phone" @if ($errors->first('emergency_contact')) style="border:1px solid red" @endif name="emergency_contact"
                                    placeholder="Phone Number..">
                                    {!! $errors->first('emergency_contact', '<small class="text-danger">:message</small>') !!}

                            </div>

                            <div class="form-group">
                                <label class="form-label">Relationship <label class="text-danger">*</label></label>
                                <input type="text" value="{{$patient->relationship}}"   class="form-control" @if ($errors->first('relationship')) style="border:1px solid red" @endif name="relationship" placeholder="Relationship..">
                                {!! $errors->first('relationship', '<small class="text-danger">:message</small>') !!}

                            </div>
                            <div class="form-group">
                                <label class="form-label">Any Known Allergies </label>
                                <textarea name="allergies"   cols="44" rows="1" placeholder="Allergies.."
                                    class="form-control">{{$patient->allergies}} </textarea>

                            </div>
                            <div class="form-group">
                                <label class="form-label">Remark</label>
                                <textarea name="remark"  cols="44" rows="1"  placeholder="Remark.."
                                    class="form-control">{{$patient->remark}}</textarea>
                            </div>
                             <div class="form-group">
                            <label>Referred By</label>
                            <input name="referred_by" type="text" class="form-control "  placeholder="Referred By"  value="{{$patient->referred}}" >
                        </div>
                        
                        </div>{{-- end of col --}}

                    </div>{{-- end of row --}}
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Edit</button>

                    </div>
                </form>

            </div>
    </div>
@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{url('patients')}}">Patient</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection



@section('jquery')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.phone').inputmask('(0)-999-999-999');
            $('.age').inputmask('999');
        });
    </script>
@endsection
