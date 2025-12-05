@props(['title' => 'Laporan Keuangan'])

@section('title', $title)

@include('layouts.header')

<div id="main-content">
    @if (isset($header))
        <div class="page-heading">
            {{ $header }}
        </div>
    @endif

    <div class="page-content">
        {{ $slot }}
    </div>
</div>

@include('layouts.footer')
