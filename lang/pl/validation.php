<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Pole :attribute musi być zaakceptowane.',
    'accepted_if' => 'Pole :attribute musi być zaakceptowane, gdy :other jest :value.',
    'active_url' => 'Pole :attribute musi być poprawnym adresem URL.',
    'after' => 'Pole :atrybut musi być datą po :data.',
    'after_or_equal' => 'Pole :atrybut musi być datą po lub równą :data.',
    'alpha' => 'Pole :attribute musi zawierać tylko litery.',
    'alpha_dash' => 'Pole :attribute może zawierać tylko litery, cyfry, kreski i podkreślenia.',
    'alpha_num' => 'Pole :attribute może zawierać tylko litery i cyfry.',
    'array' => 'Pole :attribute musi być tablicą.',
    'ascii' => 'Pole :attribute może zawierać tylko jednobajtowe znaki alfanumeryczne i symbole.',
    'before' => 'Pole :atrybut musi być datą przed :data.',
    'before_or_equal' => 'Pole :atrybut musi być datą wcześniejszą lub równą :data.',
    'between' => [
        'array' => 'Pole :attribute musi mieć od :min do :max elementów.',
        'file' => 'Pole :attribute musi zawierać się w przedziale :min i :max kilobajtów.',
        'numeric' => 'Pole :atrybut musi zawierać się pomiędzy :min a :max.',
        'string' => 'Pole :atrybut musi zawierać od :min do :max znaków.',
    ],
    'boolean' => 'Pole :attribute musi mieć wartość true lub false.',
    'confirmed' => 'Potwierdzenie pola :attribute nie pasuje.',
    'current_password' => 'Hasło jest nieprawidłowe.',
    'date' => 'Pole :attribute musi być poprawną datą.',
    'date_equals' => 'Pole :atrybut musi być datą równą :data.',
    'date_format' => 'Pole :attribute musi odpowiadać formatowi :format.',
    'decimal' => 'Pole :attribute musi mieć :decimal miejsca dziesiętne.',
    'declined' => 'Pole :attribute musi być zdeklasowane.',
    'declined_if' => 'Pole :attribute musi być zdeklasowane, gdy :other jest :value.',
    'different' => 'Pole :attribute i :other muszą być różne.',
    'digits' => 'Pole :attribute musi być :digits cyframi.',
    'digits_between' => 'Pole :atrybut musi zawierać się pomiędzy :min a :max cyframi.',
    'dimensions' => 'Pole :attribute ma nieprawidłowe wymiary obrazu.',
    'distinct' => 'Pole :atrybut ma zduplikowaną wartość.',
    'doesnt_end_with' => 'Pole :attribute nie może kończyć się jednym z następujących: :values.',
    'doesnt_start_with' => 'Pole :attribute nie może zaczynać się od jednego z następujących: :values.',
    'email' => 'Pole :atrybut musi być prawidłowym adresem e-mail.',
    'ends_with' => 'Pole :attribute musi kończyć się jednym z następujących: :values.',
    'enum' => 'Wybrany :atrybut jest nieprawidłowy.',
    'exists' => 'Wybrany :atrybut jest nieprawidłowy.',
    'file' => 'Pole :attribute musi być plikiem.',
    'filled' => 'Pole :attribute musi mieć wartość.',
    'gt' => [
        'array' => 'Pole :attribute musi mieć więcej niż :value elementów.',
        'file' => 'Pole :attribute musi być większe niż :value kilobajtów.',
        'numeric' => 'Pole :attribute musi być większe niż :value.',
        'string' => 'Pole :attribute musi być większe niż :value znaków.',
    ],
    'gte' => [
        'array' => 'Pole :attribute musi mieć :value elementów lub więcej.',
        'file' => 'Pole :atrybut musi być większe lub równe :wartość kilobajtów.',
        'numeric' => 'Pole :attribute musi być większe lub równe :value.',
        'string' => 'Pole :attribute musi być większe lub równe od znaków :value.',
    ],
    'image' => 'Pole :attribute musi być obrazem.',
    'in' => 'Wybrany :atrybut jest nieprawidłowy.',
    'in_array' => 'Pole :attribute musi istnieć w :other.',
    'integer' => 'Pole :attribute musi być liczbą całkowitą.',
    'ip' => 'Pole :attribute musi być prawidłowym adresem IP.',
    'ipv4' => 'Pole :attribute musi być prawidłowym adresem IPv4.',
    'ipv6' => 'Pole :attribute musi być prawidłowym adresem IPv6.',
    'json' => 'Pole :attribute musi być poprawnym ciągiem JSON.',
    'lowercase' => 'Pole :attribute musi być napisane małymi literami.',
    'lt' => [
        'array' => 'Pole :attribute musi mieć mniej niż :value elementów.',
        'file' => 'Pole :attribute musi być mniejsze niż :value kilobajtów.',
        'numeric' => 'Pole :atrybut musi być mniejsze niż :wartość.',
        'string' => 'Pole :attribute musi być mniejsze niż :value znaków.',
    ],
    'lte' => [
        'array' => 'Pole :attribute nie może mieć więcej niż :value elementów.',
        'file' => 'Pole :atrybut musi być mniejsze lub równe :wartość kilobajtów.',
        'numeric' => 'Pole :attribute musi być mniejsze lub równe :value.',
        'string' => 'Pole :attribute musi być mniejsze lub równe od znaków :value.',
    ],
    'mac_address' => 'Pole :attribute musi być prawidłowym adresem MAC.',
    'max' => [
        'array' => 'Pole :atrybut nie może mieć więcej niż :max elementów.',
        'file' => 'Pole :attribute nie może być większe niż :max kilobajtów.',
        'numeric' => 'Pole :atrybut nie może być większe niż :max.',
        'string' => 'Pole :atrybut nie może być większe niż :max znaków.',
    ],
    'max_digits' => 'Pole :atrybut nie może mieć więcej niż :max cyfr.',
    'mimes' => 'Pole :attribute musi być plikiem typu: :values.',
    'mimetypes' => 'Pole :attribute musi być plikiem typu: :values.',
    'min' => [
        'array' => 'Pole :attribute musi mieć co najmniej :min elementów.',
        'file' => 'Pole :attribute musi mieć co najmniej :min kilobajtów.',
        'numeric' => 'Pole :atrybut musi mieć wartość co najmniej :min.',
        'string' => 'Pole :atrybut musi mieć co najmniej :min znaków.',
    ],
    'min_digits' => 'Pole :atrybut musi mieć co najmniej :min cyfry.',
    'missing' => 'Musi brakować pola :attribute.',
    'missing_if' => 'Pole :attribute musi być nieobecne, gdy :other jest :value.',
    'missing_unless' => 'W polu :atrybut musi brakować, chyba że :other jest :value.',
    'missing_with' => 'Pole :attribute musi być nieobecne, gdy :values jest obecne.',
    'missing_with_all' => 'Pole :attribute musi być nieobecne, gdy :values jest obecne.',
    'multiple_of' => 'Pole :attribute musi być wielokrotnością :value.',
    'not_in' => 'Wybrany :atrybut jest nieprawidłowy.',
    'not_regex' => 'Format pola :attribute jest nieprawidłowy.',
    'numeric' => 'Pole :atrybut musi być liczbą.',
    'password' => [
        'letters' => 'Pole :attribute musi zawierać co najmniej jedną literę.',
        'mixed' => 'Pole :attribute musi zawierać co najmniej jedną wielką i jedną małą literę.',
        'numbers' => 'Pole :atrybut musi zawierać co najmniej jedną liczbę.',
        'symbols' => 'Pole :attribute musi zawierać co najmniej jeden symbol.',
        'uncompromised' => 'Podany :atrybut pojawił się w wycieku danych. Proszę wybrać inny :atrybut.',
    ],
    'present' => 'Pole :attribute musi być obecne.',
    'prohibited' => 'Pole :attribute jest zabronione.',
    'prohibited_if' => 'Pole :attribute jest zabronione, gdy :other jest :value.',
    'prohibited_unless' => 'Pole :attribute jest zabronione, chyba że :other znajduje się w :values.',
    'prohibits' => 'Pole :attribute zabrania obecności :other.',
    'regex' => 'Format pola :attribute jest nieprawidłowy.',
    'required' => 'Pole :atrybut jest wymagane.',
    'required_array_keys' => 'Pole :attribute musi zawierać wpisy dla: :values.',
    'required_if' => 'Pole :attribute jest wymagane, gdy :other jest :value.',
    'required_if_accepted' => 'Pole :atrybut jest wymagane, gdy akceptowany jest :inny.',
    'required_unless' => 'Pole :attribute jest wymagane, chyba że :other znajduje się w :values.',
    'required_with' => 'Pole :attribute jest wymagane, gdy występuje :values.',
    'required_with_all' => 'Pole :attribute jest wymagane, gdy występuje :values.',
    'required_without' => 'Pole :attribute jest wymagane, gdy :values nie jest obecne.',
    'required_without_all' => 'Pole :attribute jest wymagane, gdy nie występuje żadne z :values.',
    'same' => 'Pole :attribute musi odpowiadać :other.',
    'size' => [
        'array' => 'Pole :attribute musi zawierać elementy :size.',
        'file' => 'Pole :attribute musi być :size kilobajtowe.',
        'numeric' => 'Pole :attribute musi być :size.',
        'string' => 'Pole :atrybut musi mieć :rozmiar znaków.',
    ],
    'starts_with' => 'Pole :attribute musi zaczynać się od jednego z następujących: :values.',
    'string' => 'Pole :attribute musi być ciągiem znaków.',
    'timezone' => 'Pole :attribute musi być poprawną strefą czasową.',
    'unique' => ':atrybut został już zajęty.',
    'uploaded' => 'Nie udało się załadować :atrybut.',
    'uppercase' => 'Pole :attribute musi być napisane wielkimi literami.',
    'url' => 'Pole :attribute musi być poprawnym adresem URL.',
    'ulid' => 'Pole :attribute musi być prawidłowym ULID.',
    'uuid' => 'Pole :attribute musi być prawidłowym identyfikatorem UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */


    'custom' => [
        'attribute-name' => [
            'rule-name' => 'wiadomość niestandardowa',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
