@foreach($translations as $key => $translation)
    @if(is_array($translation))
        '{{ $key }}' => [
            @include('translator::langArray', ['translations' => $translation])
        ],
    @else
        '{{ $key }}' => '{{ $translation }}',
    @endif
@endforeach