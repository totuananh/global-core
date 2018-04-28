<?php

namespace Gobiz\Activity;

use DateTime;
use Gobiz\Support\OptionsAccess;

/**
 * Class ActivityLogFilter
 *
 * @package Gobiz\Activity
 * @property int $partner_id
 * @property int|null $creator_id
 * @property string|null $creator_username
 * @property array|null $action
 * @property array|null $object Đối tượng muốn lấy log (VD: ['package' => 123])
 * @property DateTime|null $time_from
 * @property DateTime|null $time_to
 * @property int $page Page hiện tại (page = -1 là lấy tất cả log)
 * @property int $per_page
 */
class ActivityLogFilter extends OptionsAccess
{
    /**
     * Make the options config
     *
     * @return array
     */
    protected function makeConfig()
    {
        return [
            'partner_id' => [
                static::PARAM_REQUIRED => true,
                static::PARAM_NORMALIZER => 'int',
            ],
            'creator_id' => [
                static::PARAM_NORMALIZER => 'int',
            ],
            'creator_username' => [
                static::PARAM_NORMALIZER => 'string',
            ],
            'action' => [
                static::PARAM_NORMALIZER => function ($action) {
                    return is_null($action) ? $action : (array)$action;
                },
            ],
            'object' => [
                static::PARAM_ALLOWED_TYPES => ['null', 'array'],
            ],
            'time_from' => [
                static::PARAM_NORMALIZER => 'date',
            ],
            'time_to' => [
                static::PARAM_NORMALIZER => 'date',
            ],
            'page' => [
                static::PARAM_ALLOWED_TYPES => 'int',
                static::PARAM_DEFAULT => 1,
            ],
            'per_page' => [
                static::PARAM_ALLOWED_TYPES => 'int',
                static::PARAM_DEFAULT => 20,
            ],
        ];
    }
}