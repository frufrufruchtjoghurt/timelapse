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

    'accepted' => ':attribute muss akzeptiert werden.',
    'active_url' => ':attribute ist keine gültige URL.',
    'after' => ':attribute muss ein Datum nach :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach oder am :date sein.',
    'alpha' => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => ':attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => ':attribute darf nur Buchstaben und Zahlen enthalten.',
    'array' => ':attribute muss eine Liste sein.',
    'before' => ':attribute muss ein Datum vor :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor oder am :date sein.',
    'between' => [
        'numeric' => ':attribute muss zwischen :min und :max sein.',
        'file' => ':attribute muss zwischen :min und :max Kilobytes groß sein.',
        'string' => ':attribute muss zwischen :min und :max Zeichen lang sein.',
        'array' => ':attribute muss zwischen :min und :max Elemente beinhalten.',
    ],
    'boolean' => ':attribute muss wahr oder falsch sein.',
    'confirmed' => ':attribute stimmt nicht überein.',
    'date' => ':attribute ist kein gültiges Datum.',
    'date_equals' => ':attribute muss ein Datum gleichwertig zu :date sein.',
    'date_format' => ':attribute stimmt nicht mit dem Format :format überein.',
    'different' => ':attribute und :other müssen unterschiedlich sein.',
    'digits' => ':attribute muss :digits Ziffern lang sein.',
    'digits_between' => ':attribute muss zwischen :min und :max Ziffern lang sein.',
    'dimensions' => ':attribute hat eine inkorrekte Größe.',
    'distinct' => ':attribute ist ein Duplikat.',
    'email' => ':attribute muss eine gültige E-Mail Adresse beinhalten.',
    'ends_with' => ':attribute benötigt einen der folgenden Werte: :values.',
    'exists' => ':attribute ist ungültig.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute darf nicht leer sein.',
    'gt' => [
        'numeric' => ':attribute muss größer sein als :value.',
        'file' => ':attribute muss größer sein als :value Kilobytes.',
        'string' => ':attribute muss länger sein als :value Zeichen.',
        'array' => ':attribute muss mehr :value Elemente beinhalten.',
    ],
    'gte' => [
        'numeric' => ':attribute muss mindestens :value groß sein.',
        'file' => ':attribute muss mindestens :value Kilobytes enthalten.',
        'string' => ':attribute muss mindestens :value Zeichen enthalten.',
        'array' => ':attribute muss mindestens :value Elemente oder mehr beinhalten.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => ':attribute ist ungültig.',
    'in_array' => ':attribute existiert nicht in :other.',
    'integer' => ':attribute muss ein Integer-Wert sein.',
    'ip' => ':attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6-Adresse sein.',
    'json' => ':attribute muss ein gültiges JSON-Objekt sein.',
    'lt' => [
        'numeric' => ':attribute muss kleiner sein als :value.',
        'file' => ':attribute muss kleiner sein als :value Kilobytes.',
        'string' => ':attribute muss weniger als :value Zeichen lang sein.',
        'array' => ':attribute muss weniger als :value Elemente beinhalten.',
    ],
    'lte' => [
        'numeric' => ':attribute darf maximal :value groß sein.',
        'file' => ':attribute darf maximal :value Kilobytes groß sein.',
        'string' => ':attribute darf maximal :value Zeichen lang sein.',
        'array' => ':attribute darf maximal :value Elemente beinhalten.',
    ],
    'max' => [
        'numeric' => ':attribute darf nicht größer sein als :max.',
        'file' => ':attribute darf nicht größer sein als :max Kilobytes.',
        'string' => ':attribute darf nicht größer sein als :max Zeichen.',
        'array' => ':attribute darf nicht mehr als :max Elemente besitzen.',
    ],
    'mimes' => ':attribute muss einem der folgenden Dateitypen entspechen: :values.',
    'mimetypes' => ':attribute muss einem der folgenden Dateitypen entspechen: :values.',
    'min' => [
        'numeric' => ':attribute muss mindestens :min groß sein.',
        'file' => ':attribute muss mindestens :min Kilobytes groß sein.',
        'string' => ':attribute muss mindestens :min Zeichen lang sein.',
        'array' => ':attribute muss mindestens :min Elemente besitzeb.',
    ],
    'not_in' => ':attribute ist ungültig.',
    'not_regex' => 'Die Formatierung von :attribute ist ungültig.',
    'numeric' => ':attribute muss ein Zahl sein.',
    'password' => 'Das Passwort ist falsch.',
    'present' => ':attribute muss aktiv sein.',
    'regex' => 'Die Formatierung von :attribute ist ungültig.',
    'required' => ':attribute darf nicht leer sein.',
    'required_if' => ':attribute darf nicht leer sein, wenn :other = :value entspricht.',
    'required_unless' => ':attribute darf leer sein, außer :other liegt innerhalb von :values.',
    'required_with' => ':attribute darf nicht leer sein, wenn :values aktiv ist.',
    'required_with_all' => ':attribute darf nicht leer sein, wenn :values aktiv sind.',
    'required_without' => ':attribute darf nicht leer sein, wenn :values nicht aktiv ist.',
    'required_without_all' => ':attribute darf nicht leer sein, wenn keines von :values aktiv ist.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => ':attribute muss :size groß sein.',
        'file' => ':attribute muss :size Kilobytes groß sein.',
        'string' => ':attribute muss :size Zeichen beinhalten.',
        'array' => ':attribute muss :size Elemente beinhalten.',
    ],
    'starts_with' => ':attribute muss mit einem folgender Werte beginnen: :values.',
    'string' => ':attribute muss ein Text sein.',
    'timezone' => ':attribute muss eine gültige Zeitzone sein.',
    'unique' => ':attribute wird bereits benutzt.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',
    'url' => ':attribute Format ist ungültig.',
    'uuid' => ':attribute muss eine gültige UUID sein.',

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
            'rule-name' => 'custom-message',
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
