@extends('front.layouts.content-narrow')

@section('subcontent')
    <h1 class="text-lg md:text-2xl font-display">Lista {{ $type == \App\Models\Marker::TYPE_LOST ? 'zgubionych' : 'znalezionych' }}</h1>

    <div class="mt-4">
        <markers-list
            :ext-markers="{{ json_encode($markers) }}"
            type="{{ $type }}"
        ></markers-list>
    </div>
@endsection
