<?php

namespace fileconverter_flasksoffice\event;

use core\event\base;
use local_pxsdk\app\v16\factory;
use local_pxsdk\app\v16\logger\interfaces\logger;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

class conversion_observer
{
    private const LOG_KEY_MESSAGE = 'message';
    private const LOG_KEY_CONTEXT = 'context';

    private static ?logger $logger = null;

    /**
     * @codeCoverageIgnore
     */
    public static function file_is_converted(base $event): void
    {
        try {
            if (!self::is_debug_mode()) {
                return;
            }
            if (!self::is_loggable($event)) {
                return;
            }
            self::log_info(self::get_log_context($event));
        } catch (\Throwable) { }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function upload(base $event): void
    {
        try {
            if (!self::is_debug_mode()) {
                return;
            }
            if (!self::is_loggable($event)) {
                return;
            }
            self::log_info(self::get_log_context($event));
        } catch (\Throwable) { }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function download(base $event): void
    {
        try {
            if (!self::is_debug_mode()) {
                return;
            }
            if (!self::is_loggable($event)) {
                return;
            }
            self::log_info(self::get_log_context($event));
        } catch (\Throwable) { }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function process_error(base $event): void
    {
        try {
            if (!self::is_loggable($event)) {
                return;
            }
            self::log_emergency(self::get_log_context($event));
        } catch (\Throwable) { }
    }

    private static function get_logger(): logger
    {
        if (self::$logger === null) {
            self::$logger = factory::make()->logger()->datadog()->logger();
            self::$logger->set_preset_context([
                'site' => self::get_host(),
                'component' => 'fileconverter_flasksoffice',
                'platform' => 'moodle',
            ]);
        }
        return self::$logger;
    }

    private static function get_host(): string
    {
        global $CFG;
        if (empty($CFG->wwwroot)) {
            return 'localhost';
        }

        $host = parse_url($CFG->wwwroot, PHP_URL_HOST);
        if (empty($host)) {
            $parts = explode('://', $CFG->wwwroot);
            $host = count($parts) > 1 ? $parts[1] : $parts[0] ?? 'localhost';
        }
        return $host;
    }

    private static function is_sdk_installed(): bool
    {
        return class_exists(factory::class);
    }

    private static function is_loggable(base $event): bool
    {
        return self::is_sdk_installed()
            && self::has_log_context($event);
    }

    private static function log_info(array $context): void
    {
        [
            self::LOG_KEY_MESSAGE => $message,
            self::LOG_KEY_CONTEXT => $optional_context,
        ] = self::get_log_data($context);

        self::get_logger()->info($message, $optional_context);
    }

    private static function log_emergency(array $context): void
    {
        [
            self::LOG_KEY_MESSAGE => $message,
            self::LOG_KEY_CONTEXT => $optional_context,
        ] = self::get_log_data($context);

        self::get_logger()->info($message, $optional_context);
    }

    private static function get_log_data(array $context): array
    {
        $message = $context['message'] ?? '';
        unset(
            $context['message'],
            $context['uid']
        );
        return [
            self::LOG_KEY_MESSAGE => $message,
            self::LOG_KEY_CONTEXT => $context,
        ];
    }

    private static function get_log_context(base $event): array
    {
        return $event->other['log'] ?? [];
    }

    private static function is_debug_mode(): bool
    {
        global $CFG;
        return $CFG->debugdeveloper ?? false;
    }

    private static function has_log_context(base $event): bool
    {
        return isset($event->other['log']);
    }
}
