<?php
namespace DERHANSEN\SfEventMgt\Hooks;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Realurl automatic configuration
 */
class RealUrlAutoConfiguration
{
    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param array $params Default configuration
     * @return array Updated configuration
     */
    public function addSfEventConfig($params)
    {
        return array_merge_recursive(
            $params['config'],
            [
                'postVarSets' => [
                    '_DEFAULT' => [
                        'events' => [
                            [
                                'GETvar' => 'tx_sfeventmgt_pievent[controller]',
                                'noMatch' => 'bypass',
                            ],
                            [
                                'GETvar' => 'tx_sfeventmgt_pievent[action]',
                                'valueMap' => [
                                    'detail' => 'detail'
                                ]
                            ],
                            [
                                'GETvar' => 'tx_sfeventmgt_pievent[event]',
                                'lookUpTable' => [
                                    'table' => 'tx_sfeventmgt_domain_model_event',
                                    'id_field' => 'uid',
                                    'alias_field' => 'title',
                                    'useUniqueCache' => 1,
                                    'useUniqueCache_conf' => [
                                        'strtolower' => 1,
                                        'spaceCharacter' => '-',
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );
    }
}
