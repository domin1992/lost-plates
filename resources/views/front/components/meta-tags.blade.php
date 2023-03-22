<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}" />
<meta name="language" content="{{ strtoupper(app()->getLocale()) }}">
<meta name="robots" content="{{ $index }},{{ $follow }}" />

<meta name="og:title" content="{{ $title }}" />
<meta name="og:type" content="website" />
<meta name="og:url" content="{{ url()->current() }}" />
<meta name="og:image" content="{{ $image }}" />
<meta name="og:site_name" content="{{ config('app.name') }}" />
<meta name="og:description" content="{{ $description }}" />