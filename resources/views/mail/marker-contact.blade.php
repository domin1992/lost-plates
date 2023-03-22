<x-mail::message>
# {{ __('mailMarkerContact.title') }}

{{ __('mailMarkerContact.message', ['plateNumber' => $marker->plate->number, 'page' => config('app.name')]) }}

## {{ __('mailMarkerContact.contactData') }}
{{ $contactInfo }}

</x-mail::message>
