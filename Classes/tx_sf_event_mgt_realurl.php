<?php
class tx_Pievent_Event_realurl {

    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param       array           $params Default configuration
     * @param       tx_realurl_autoconfgen          $pObj   Parent object
     * @return      array           Updated configuration
     */
    function addSfEventConfig($params, &$pObj) {
        return array_merge_recursive(
            $params['config'], array(
                'init' => array(
                    'calculateChashIfMissing' => true
                    ),
                'postVarSets' => array(
                    '_DEFAULT' => array(
                        'Events' => array(
                            array(
                                'GETvar' => 'tx_sfeventmgt_pievent[controller]',
                                'noMatch' => 'bypass',
                            ),
                            array(
                                'GETvar' => 'tx_sfeventmgt_pievent[action]',
                                'valueMap' => array(
                                    'detail' => 'detail'
                                )
                            ),
                            array(
                                'GETvar' => 'tx_sfeventmgt_pievent[event]',
                                'lookUpTable' => array(
                                    'table' => 'tx_sfeventmgt_domain_model_event',
                                    'id_field' => 'uid',
                                    'alias_field' => 'title',
                                    'useUniqueCache' => 1,
                                    'useUniqueCache_conf' => array(
                                        'strtolower' => 1,
                                        'spaceCharacter' => '-',
                                    ),
                                ),
                            ),
                        ),
                    )
                )
            )
        );
    }
}
