@component('mail::message')
# Test

Test wiadomości email

@component('mail::button', ['url' => route('front.maps.index')])
Przejdź do strony
@endcomponent

{{ config('app.name') }}
@endcomponent
