@extends('layouts.admin')
@section('title') User Activity Log @endsection

@section('css')
    <link href="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
@php
    $agent = new \Jenssegers\Agent\Agent;
    use Carbon\Carbon;
@endphp
    <div class="card p-3">
      
        <div class="mt-5 tables table-responsive">
            <table class="table table-striped table-bordered table-sm text-nowrap w-100 dataTable no-footer table-main"
                id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Device</th>
                        <th>Ip Address</th>
                        <th>Last Activity</th>
                 
                    </tr>
                </thead>
                <tbody>
                  @php $counter=1; @endphp

                    @foreach ($sessions as $session)
                    @php   $agent->setUserAgent($session->user_agent); @endphp

                    <tr>
                      <td>{{$counter++}}</td>
                      <td>{{$session->name}}</td>
                      <td>{{$session->email}}</td>
                      <td>
                        @if ($agent->isDesktop())
                        <svg  fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-500">
                            <path  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        @else
                            <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-gray-50">
                                <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                            </svg>
                        @endif
                        {{ $agent->platform() }} - {{ $agent->browser() }}
                      </td>

                      <td>{{ $session->ip_address }}</td>
                      <td>   @if ($session->id === request()->session()->getId())
                                <span class="text-green font-semibold">{{ __('This device') }}</span>
                            @else
                                {{ __('Last active') }} {{ Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="float-right">
            <span >{{$sessions->links()}}</span>
        </div>

        </div>
    </div>
  
    
@endsection

@section('directory')
    <li class="breadcrumb-item active" aria-current="page">User Log</li>
  
@endsection

@section('jquery')
    <script src="{{ asset('public/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>

    <script>
        $('#example').DataTable({
            dom: 'Blfrtip',
            "bLengthChange": false,
            "pageLength": 50,
            'paging':false,
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.defaultStyle.width = "100%";

                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
                    }
                },
                {
                    extend: 'print',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },

            ],
        });
        $('.buttons-print, .buttons-pdf ,.buttons-colvis').addClass('btn btn-primary mr-1');
    </script>

    <script>
    

    </script>

@endsection
