<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Model_Mysql4_Item extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('exportattribute/item', 'item_id');
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        $condition = $this->_getWriteAdapter()->quoteInto('item_id = ?', $object->getId());
        if ($object->getData('in_related')) $this->_getWriteAdapter()->delete($this->getTable('exportattribute/item_related'), $condition);
//        var_dump($object->getData('in_relateds') );
//        exit();

        $relateds = $object->getData('related');
        
        foreach ((array) $relateds as $related) {
            if ($related == 0) continue;
            $relatedArray = array();
            $relatedArray['item_id'] = $object->getId();
            $relatedArray['related_id'] = $related;
            $this->_getWriteAdapter()->insert($this->getTable('exportattribute/item_related'), $relatedArray);
        }        

        return parent::_afterSave($object);
    }
    
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        //RELATED
        $select = $this->_getReadAdapter()->select()
                        ->from($this->getTable('exportattribute/item_related'))
                        ->where('item_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $relatedArray = array();
            foreach ($data as $row) {
                $relatedArray[] = $row['related_id'];
            }
            $object->setData('related_id', $relatedArray);
        }

        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('exportattribute/item_related'), 'item_id=' . $object->getId());
    }

}