@extends('front.layouts.content-narrow')

@section('subcontent')
    <h1 class="text-lg md:text-2xl font-display">{{ $type == \App\Models\Marker::TYPE_LOST ? 'Zgubiona' : 'Znaleziona' }} <a href="{{ $marker['plate']['link'] }}" class="text-purple-heart hover:text-cyan focus:text-cyan">{{ $marker['plate_number'] }}</a></h1>

    <div class="flex flex-col lg:flex-row lg:gap-4 mt-4">
        <div class="lg:basis-2/3">
            @if ($marker['additional_info'])
                <h2 class="md:text-xl font-display">
                    Opis
                </h2>

                <p class="mt-2 mb-4">{{ $marker['additional_info'] }}</p>
            @endif

            @if ($marker['phone_number'] || $marker['has_email'])
                <h2 class="md:text-xl font-display">
                    Kontakt z osobą, która dodała znacznik
                </h2>

                @if ($marker['phone_number'])
                    <div class="mt-4">
                        <reveal-phone-number
                            marker-id="{{ $marker['id'] }}"
                            hidden-phone-number="{{ $marker['phone_number'] }}"
                        ></reveal-phone-number>
                    </div>
                @endif

                @if ($marker['has_email'])
                    <div class="mt-4">
                        <email-contact-form
                            marker-id="{{ $marker['id'] }}"
                        ></email-contact-form>
                    </div>
                @endif
            @endif

            <div class="lg:hidden">
                @include('front.markers.partials.mini-map')
            </div>

            <h2 class="md:text-xl font-display mt-8">
                Komentarze
            </h2>
            <marker-comments
                :marker="{{ json_encode($marker) }}"
            ></marker-comments>
        </div>
        <div class="hidden lg:basis-1/3 lg:block">
            @include('front.markers.partials.mini-map')
        </div>
    </div>
@endsection
