<?php
return [
    'index' => [
        'scripts' => 'index-scripts'
    ],
    'add' => [
        'after_form_item' => 'common',
        'scripts' => 'common-scripts'
    ],
    'edit' => [
        'after_form_item' => 'common',
        'scripts' => 'common-edit-scripts,common-scripts'
    ],
    'select' => [
        'scripts' => 'select-scripts'
    ]
];