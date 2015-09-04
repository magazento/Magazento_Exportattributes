<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Model_Mysql4_Item_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {
        $this->_init('exportattribute/item');
    }

    public function toOptionArray() {
        return $this->_toOptionArray('item_id', 'name');
    }

    public function addRelatedFilter($related) {

        $this->getSelect()->joinleft(
                        array('item_related' => $this->getTable('exportattribute/item_related')),
                        'main_table.item_id = item_related.item_id',
                        array()
                )
                ->distinct()                      
                ->where('item_related.related_id in (?) OR main_table.assign_relateds = 1', $related);

        return $this;
    }

    public function addNowFilter() {
        $now = Mage::getSingleton('core/date')->gmtDate();
        $where = "time_from < '" . $now . "' AND ((time_to > '" . $now . "') OR (time_to IS NULL))";
        $this->getSelect()->where($where);
    }

}