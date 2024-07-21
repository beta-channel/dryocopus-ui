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

    'accepted' => ':attributeを承認してください。',
    'active_url' => ':attributeが有効なURLではありません。',
    'after' => ':attributeは:dateより後の日付を指定してください。',
    'after_or_equal' => ':attributeは:date以降の日付を指定してください。',
    'alpha' => ':attributeはアルファベットのみ使用できます。',
    'alpha_dash' => ':attributeはアルファベットとダッシュ(-)及び下線(_)のみ使用できます。',
    'alpha_num' => ':attributeはアルファベットと数字のみ使用できます。',
    'array' => ':attributeは配列でなくてはなりません。',
    'before' => ':attributeは:dateより前の日付を指定してください。',
    'before_or_equal' => ':attributeは:date以前の日付を指定してください。',
    'between' => [
        'numeric' => ':attributeは:minから:maxの間で指定してください。',
        'file' => ':attributeは:minから:max KBの間で指定してください。',
        'string' => ':attributeは:minから:max文字の間で入力してください。',
        'array' => ':attributeは:minから:max個の間で指定してください。',
    ],
    'boolean' => ':attributeはtrueかfalseを指定してください。',
    'confirmed' => ':attributeと確認フィールドが一致していません。',
    'current_password' => ':attributeが正しくありません。',
    'date' => ':attributeは有効な日付を指定してください。',
    'date_equals' => ':attributeは:dateと同じ日付を指定してください。',
    'date_format' => ':attributeは:format形式で指定してください。',
    'different' => ':attributeと:otherは異なる内容を指定してください。',
    'digits' => ':attributeは:digits桁で指定してください。',
    'digits_between' => ':attributeは:minから:max桁の間で指定してください。',
    'dimensions' => ':attributeのサイズが正しくありません。',
    'distinct' => ':attributeには異なった値を指定してください。(重複する値が含まれています。)',
    'email' => ':attributeは有効なメールアドレスを指定してください。',
    'exists' => '指定された:attributeは存在していません。',
    'file' => ':attributeはファイルを指定してください。',
    'filled' => ':attributeに値を指定してください。',
    'gt' => [
        'numeric' => ':attributeは:valueより大きな値を指定してください。',
        'file' => ':attributeは:value KBより大きなファイルを指定してください。',
        'string' => ':attributeは:value文字より長いものを入力してください。',
        'array' => ':attributeは:value個より多いアイテムを指定してください。',
    ],
    'gte' => [
        'numeric' => ':attributeは:value以上の値を指定してください。',
        'file' => ':attributeは:value KB以上のファイルを指定してください。',
        'string' => ':attributeは:value文字以上で入力してください。',
        'array' => ':attributeは:value個以上のアイテムを指定してください。',
    ],
    'image' => ':attributeは画像ファイルを指定してください。',
    'in' => '指定された:attributeは正しくありません。',
    'in_array' => ':attributeには:otherの値を指定してください。',
    'integer' => ':attributeは整数で指定してください。',
    'ip' => ':attributeは有効なIPアドレスを指定してください。',
    'ipv4' => ':attributeは有効なIPv4アドレスを指定してください。',
    'ipv6' => ':attributeは有効なIPv6アドレスを指定してください。',
    'json' => ':attributeは有効なJSON文字列を指定してください。',
    'lt' => [
        'numeric' => ':attributeは:valueより小さな値を指定してください。',
        'file' => ':attributeは:value KBより小さなファイルを指定してください。',
        'string' => ':attributeは:value文字より短いものを入力してください。',
        'array' => ':attributeは:value個より少ないアイテムを指定してください。',
    ],
    'lte' => [
        'numeric' => ':attributeは:value以下の値を指定してください。',
        'file' => ':attributeは:value KB以下のファイルを指定してください。',
        'string' => ':attributeは:value文字以下で入力してください。',
        'array' => ':attributeは:value個以下のアイテムを指定してください。',
    ],
    'max' => [
        'numeric' => ':attributeは:max以下の値を指定してください。',
        'file' => ':attributeは:max KB以下のファイルを指定してください。',
        'string' => ':attributeは:max文字以下で入力してください。',
        'array' => ':attributeは:max個以下で指定してください。',
    ],
    'mimes' => ':attributeは拡張子が:valuesのファイルを指定してください。',
    'mimetypes' => ':attributeは:values形式のファイルを指定してください。',
    'min' => [
        'numeric' => ':attributeは:min以上の値を指定してください。',
        'file' => ':attributeは:min KB以上のファイルを指定してください。',
        'string' => ':attributeは:min文字以上で入力してください。',
        'array' => ':attributeは:min個以上で指定してください。',
    ],
    'not_in' => '指定された:attributeは正しくありません。',
    'not_regex' => ':attributeの形式が正しくありません。',
    'numeric' => ':attributeは数値を入力してください。',
    'present' => ':attributeが存在していません。',
    'regex' => ':attributeは正しい形式で入力してください。',
    'required' => ':attributeを入力してください。',
    'required_if' => ':attributeを入力してください。',
    'required_unless' => ':attributeを入力してください。',
    'required_with' => ':attributeを入力してください。',
    'required_with_all' => ':attributeを入力してください。',
    'required_without' => ':attributeを入力してください。',
    'required_without_all' => ':attributeを入力してください。',
    'same' => ':attributeは:otherと同じ値を指定してください。',
    'size' => [
        'numeric' => ':attributeは:sizeでなくてはなりません。',
        'file' => ':attributeは:size KBでなくてはなりません。',
        'string' => ':attributeは:size文字で入力してください。',
        'array' => ':attributeは:size個指定してください。',
    ],
    'string' => ':attributeは文字列を指定してください。',
    'timezone' => ':attributeは有効なゾーンを指定してください。',
    'unique' => '該当:attributeは既に存在しています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'url' => ':attributeに有効なURLを指定してください。',
    'uuid' => ':attributeに有効なUUIDを指定してください。',

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
        'current_password' => [
            'current_password' => ':attributeは正しくありません',
        ],
        'new_password' => [
            'min' => 'パスワードを8~24桁の半角英数字で指定してください',
            'max' => 'パスワードを8~24桁の半角英数字で指定してください',
            'password' => [
                'letters' => 'パスワードを8~24桁の半角英数字で指定してください',
            ]
        ],
        'new_password_confirm' => [
            'required_with' => 'パスワードをもう一度入力してください',
            'same' => 'パスワードは一致しません'
        ]
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
