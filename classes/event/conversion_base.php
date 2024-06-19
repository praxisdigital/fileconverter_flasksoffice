<?php

namespace fileconverter_flasksoffice\event;

use context;
use context_system;
use core\event\base;
use core_files\conversion;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

/**
 * @method static static create(array $data = [])
 */
abstract class conversion_base extends base
{
    protected function init(): void
    {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    public function get_description(): string
    {
        return $this->other['log']['message'] ?? '';
    }

    public static function create_by_progress(
        string $message,
        conversion $conversion,
        array $log_context = [],
        ?context $context = null,
    ): static
    {
        $context ??= context_system::instance();
        $log_context['message'] = $message;
        $log_context['component'] = 'fileconverter_flasksoffice';
        $log_context['conversion_id'] = $conversion->get('id') ?? 0;
        $log_context['conversion_status'] = self::get_status_text(
            $conversion->get('status') ?? conversion::STATUS_FAILED
        );

        return static::create([
            'context' => $context,
            'other' => [
                'log' => $log_context
            ],
        ]);
    }

    private static function get_status_text(int $status): string
    {
        switch ($status) {
            case conversion::STATUS_FAILED:
                return 'failed';
            case conversion::STATUS_PENDING:
                return 'pending';
            case conversion::STATUS_IN_PROGRESS:
                return 'in_progress';
            case conversion::STATUS_COMPLETE:
                return 'complete';
            default:
                return 'unknown';
        }
    }
}
