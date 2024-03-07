<?php

$observers = [
    [
        'eventname'   => '\fileconverter_flasksoffice\event\conversion_upload',
        'callback'    => '\fileconverter_flasksoffice\event\conversion_observer::upload',
        'internal'    => false
    ],
    [
        'eventname'   => '\fileconverter_flasksoffice\event\conversion_download',
        'callback'    => '\fileconverter_flasksoffice\event\conversion_observer::download',
        'internal'    => false
    ],
    [
        'eventname'   => '\fileconverter_flasksoffice\event\conversion_file_is_converted',
        'callback'    => '\fileconverter_flasksoffice\event\conversion_observer::file_is_converted',
        'internal'    => false
    ],
    [
        'eventname'   => '\fileconverter_flasksoffice\event\conversion_process_error',
        'callback'    => '\fileconverter_flasksoffice\event\conversion_observer::process_error',
        'internal'    => false
    ],
];
