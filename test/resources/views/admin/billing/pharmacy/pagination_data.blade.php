 <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bill No</th>
                        <th>Patient Name</th>
                        <th>Patient Type</th>
                        <th>Patient Number</th>
                        <!-- <th>Department</th> -->
                        <!-- <th>Doctor Name</th> -->
                        <th>Author</th>
                        <th>Discount</th>                     
                        <th>Total</th>      
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
           
                    @foreach ($bill as $row)
                        <tr id="row2{{$row->bill_id }}">
                            <td> {{ $bill->firstItem()+$loop->index }}</td>
                            <td>{{ $row->bill_no }}</td>
                            <td>
                                @if(empty($row->patient_id))
                                    {{$row->patient_name}}
                                @else
                                @php $patientss=Helper::getpatientname($row->patient_id); @endphp
                                {{ $patientss[0]->f_name.' '.$patientss[0]->l_name}}
                                @endif
                            </td>
                            <td>{{ $row->p_type}}</td>
                            <td>
                                @if($row->p_type=="OPD Patient")
                                opd-{{$row->p_identify}}
                                @elseif($row->p_type=="Registred Patient")
                                p-{{$row->p_identify}}
                                @else
                                N/A
                                @endif
                            </td>



                            <!-- <td>{{ $row->department_name}}</td> -->
                            <!-- <td>{{ $row->ef.' '.$row->el }}</td> -->
                            <td>{{ $row->email }}</td>
                            <td>@if(!empty($row->discount)) {{ $row->discount.'%'}} @else {{'N/A'}} @endif</td>

                            <td> @php $total=Helper::getTotalpharmacyBill($row->bill_id)@endphp {{ $total }}</td>
                            <td><span data-toggle="tooltip" title="
                                {{Carbon\Carbon::parse($row->date)->diffForHumans()  }}" >
                            {{date('Y-m-d h:m A', strtotime($row->date)) }}
                            </span> </td>                            
                            <td>
                                <a data-discount="{{$row->discount}}" data-id="{{$row->bill_id}}" data-bill="{{$row->bill_no}}" 
                                    data-patient="
                                @if(empty($row->patient_id))
                                    {{  $row->patient_name}}
                                @else
                                @php $patient=Helper::getpatientname($row->patient_id); @endphp
                                {{ $patient[0]->f_name.' '.$patient[0]->l_name}}
                                @endif
                                 "  data-department="{{$row->department_name}}"
                                     data-docter="{{$row->ef.' '.$row->el}}" data-total="{{$row->total}}" data-date=" {{date('Y-m-d h:m A', strtotime($row->date)) }}"
                                    class="btn btn-primary btn-sm text-white mr-2 addMedicine " data-target="#addMed" data-toggle="modal">Add Medicine</a>
                                <a data-toggle="modal" data-target="#print_modal" class="btn btn-success btn-sm text-white mr-2 print_slip "
                                data-discount="{{$row->discount}}" data-id="{{$row->bill_id}}" data-bill="{{$row->bill_no}}" 
                                    data-patient="
                                @if(empty($row->patient_id))
                                    {{$row->patient_name}}
                                @else
                                {{ $patient[0]->f_name.' '.$patient[0]->l_name}}
                                @endif 

                                "  data-department="{{$row->department_name}}"
                                     data-docter="{{$row->ef.' '.$row->el}}" data-total="{{$row->total}}" data-date=" {{date('Y-m-d h:m A', strtotime($row->date)) }}">Print Bill</a>
                                                        
                                <a data-id="{{$row->bill_id}}" class="btn btn-danger btn-sm text-white mr-2 delete2">Delete</a>
                            
                           
                                <a data-toggle="modal" data-target="#editBill" data-id="{{$row->bill_id}}" class="btn btn-info btn-sm text-white mr-2 edit_bi">Edit</a>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                <span class="float-right"> 
                      {{ $bill->links()}}
                 </span> 

        </div>