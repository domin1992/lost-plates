<script>
    window.globalVariables = {
        allowedLocale: {!! json_encode(config('app.allowed_locale')) !!},
    };
</script>