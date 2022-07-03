@extends('layouts.app')

@section('content')
@php
    $agent = new \Jenssegers\Agent\Agent;
    use Carbon\Carbon;
@endphp
{{-- @if (count($sessions) > 0) --}}
<div class="mt-5 space-y-6">
    
    <!-- Other Browser Sessions -->
    @foreach ($sessions as $session)
        <div class="flex items-center">
            <div>
                @php   $agent->setUserAgent($session->user_agent); @endphp
                @if ($agent->isDesktop())
                    <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500">
                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500">
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
                            <span class="text-green-500 font-semibold">{{ __('This device') }}</span>
                        @else
                            {{ __('Last active') }} {{ Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{-- @endif --}}
@endsection