<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'scan' => [
        'paths' => [
            BASE_PATH . '/app',
        ],
        'ignore_annotations' => [
            'mixin',
            'author',
            'date',
            'note',
            'description',
            'email',
            'api',
            'apiDefine',
            'apiDeprecated',
            'apiDescription',
            'apiError',
            'apiErrorExample',
            'apiExample',
            'apiGroup',
            'apiHeader',
            'apiHeaderExample',
            'apiIgnore',
            'apiName',
            'apiParam',
            'apiParamExample',
            'apiPermission',
            'apiPrivate',
            'apiSampleRequest',
            'apiSuccess',
            'apiSuccessExample',
            'apiUse',
            'apiVersion',
            'func',
            'modifier'
        ],
    ],
];
