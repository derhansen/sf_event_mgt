<?php

declare(strict_types=1);

return [
    \DERHANSEN\SfEventMgt\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
    ],
    \DERHANSEN\SfEventMgt\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
    ],
    \DERHANSEN\SfEventMgt\Domain\Model\Content::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'altText' => [
                'fieldName' => 'altText'
            ],
            'titleText' => [
                'fieldName' => 'titleText'
            ],
            'colPos' => [
                'fieldName' => 'colPos'
            ],
            'CType' => [
                'fieldName' => 'CType'
            ],
        ],
    ],
];
