@extends('front.layouts.content-narrow')

@section('subcontent')
    <h1 class="text-lg md:text-2xl font-display">Tablica rejestracyjna {{ $plate['number'] }}</h1>

    <div class="flex flex-col lg:flex-row lg:gap-4 mt-4">
        <div class="lg:basis-2/3">
            <h2 class="md:text-xl font-display mt-4">
                Pineski
            </h2>

            <div class="mt-2">
                @foreach($plate['markers'] as $marker)
                    <a href="{{ $marker['link'] }}" class="flex items-center w-full text-sm mt-4 border-t border-t-gray-600 border-t-solid pt-4 first-of-type:mt-0 first-of-type:border-t-0 first-of-type:pt-0 hover:text-purple-heart transition-colors group">
                        <div class="flex-1 flex flex-wrap items-center">
                            @if($marker['formatted_address'])
                                <span class="flex items-center mr-4">
                                    <svg class="h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path class="transition-colors group-hover:fill-purple-heart" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                                    <span class="block ml-1">{{ $marker['formatted_address'] }}</span>
                                </span>
                            @endif
                            <span class="inline-block whitespace-nowrap rounded-full px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold text-white @if($marker['type'] == 'found') bg-primary @elseif($marker['type'] == 'lost') bg-danger @endif">{{ $marker['type'] == 'found' ? 'Znaleziono' : 'Zgubiono' }}</span><br>
                            <span class="block w-full">{{ $marker['created_at_for_humans'] }}</span>
                        </div>
                        <div class="h-8 w-auto">
                            <svg class="h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path class="transition-colors group-hover:fill-purple-heart" d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="lg:basis-1/3">
            <mini-map
                :coords-list="{{ json_encode($markersCoords) }}"
            ></mini-map>
        </div>
    </div>
@endsection
