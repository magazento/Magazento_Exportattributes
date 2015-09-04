<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Model_Export
{
    private $scope = null;
    private $items = null;
    private $path = null;
    private $fileName = null;
    private $log = null;




    /*
     * Loads collection for manually selected items
     * */
    public function loadManualCollection($itemIds) {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection');
        $collection->addFieldToFilter('main_table.attribute_id', array('in' => $itemIds));

        return $collection;
    }


    /*
     * Get Item list for selected Profile
     * */
    public function loadCollection($profile) {
        // Set Filters
        $this->path = $profile->getPath();
        $this->fileName = $profile->getFilename();
        $this->scope = $profile->getScope();
        if ($items = $profile->getRelatedId()) {
            $this->$items = $items ;
        }

        // Items Collection
        $collection = Mage::getResourceModel('catalog/product_attribute_collection');
        if ($this->scope != 'all') $collection->addFieldToFilter('is_global',$this->scope);
        return $collection;
    }

    /*
     * Export Items
     */
    public function exportItemsForProfile($profileId) {
        $profile = Mage::getModel('exportattribute/item')->load($profileId);
        $collection   = $this->loadCollection($profile);


        // Add manual selected items to our collection
        if ($profile->getData('only_manual'))  {
            $collection = $this->loadManualCollection($profile->getData('related_id'));
        } else {
            $manualCollection  = $this->loadManualCollection($profile->getData('related_id'));
            foreach ($manualCollection as $manualItem) {
                $found = false;
                foreach ($collection as $item) {
                    if ($item->getId() == $manualItem->getId()) {
                        $found = true;
                        continue;
                    }
                }
                if (!$found) $collection->addItem($manualItem);
            }
        }

        $total = 0;
        $poArray = new ExSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Root></Root>');
        foreach ($collection as $item) {

            $total++;
            $this->log = 'Item Id: '.$item->getId().'<br/>';
            $nodeItem             = $poArray->addChild('Item');

            // Item values
            $nodeItemValues       = $nodeItem->addChild('ItemValues');
            foreach ($item->getData() as $k => $v) {
                $nodeItemValues->addChild($k, $v);
            }

            // Item Labels
            $nodeItemLabels      = $nodeItem->addChild('FrontendLabels');
            $itemLabels          = $this->getLabelValues($item);
            foreach ($itemLabels as $k=>$v) {
                $nodeItemLabels->addChild('store_'.$k, $v);
            }

//            var_dump($item->getData());
//            var_dump($this->getLabelValues($item));
//            var_dump($nodeItem);
//            exit();

            // Item Options
            $values = $item->getData('option_values');
            if (is_null($values)) {
                $values = array();
                $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                    ->setAttributeFilter($item->getId())
                    ->setPositionOrder('desc', true)
                    ->load();

                // Item Values
                $nodeOptions       = $nodeItem->addChild('Option');

                foreach ($optionCollection as $option) {
                    $value = array();
                    $value['id'] = $option->getId();
                    $value['sort_order'] = $option->getSortOrder();


                    $nodeValue    = $nodeOptions->addChild('Value');
                    foreach ($value as $k => $v) {
                        $nodeValue->addChild($k, $v);
                    }

                    $nodeStore       = $nodeValue->addChild('Store');
                    foreach ($this->getStores() as $store) {

                        $storeValues = $this->getStoreOptionValues($item, $store->getId());
                        if (isset($storeValues[$option->getId()])) {
//                            $value['store'.$store->getId()] = htmlspecialchars($storeValues[$option->getId()]);
                            $nodeStore->addChild('store_'.$store->getId(), htmlspecialchars($storeValues[$option->getId()]));
                        }
                        else {
//                            $value['store'.$store->getId()] = '';
                            $nodeStore->addChild('store_'.$store->getId(), '');
                        }
                    }


//                    $values[] = $value;
                }
            }
        }


        $XML = $poArray->asXML();

        $fileName = $this->path.$this->fileName.'.xml';
        $file = Mage::getBaseDir().'/'.$fileName;
        $fileUrl = Mage::app()->getStore(0)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $fileName;
        $fileUrl = '<a target="_blank" href="'.$fileUrl.'">'.$fileUrl.'</a>';

        file_put_contents($file,$XML);

        $result = array(
            'total'=>$total,
            'fileUrl'=>$fileUrl
        );

        return $result;

    }

    /**
     * Retrieve stores collection with default store
     *
     * @return Mage_Core_Model_Mysql4_Store_Collection
     */
    public function getStores()
    {
        $stores = Mage::getModel('core/store')
            ->getResourceCollection()
            ->setLoadDefault(true)
            ->load();
        return $stores;
    }

    /**
     * Retrieve attribute option values for given store id
     *
     * @param integer $storeId
     * @return array
     */
    public function getStoreOptionValues($attr, $storeId)
    {
        $values = $attr->getData('store_option_values_'.$storeId);
        if (is_null($values)) {
            $values = array();
            $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setAttributeFilter($attr->getId())
                ->setStoreFilter($storeId, false)
                ->load();
            foreach ($valuesCollection as $item) {
                $values[$item->getId()] = $item->getValue();
            }
            $attr->setData('store_option_values_'.$storeId, $values);
        }
        return $values;
    }

    /**
     * Retrieve frontend labels of attribute for each store
     *
     * @return array
     */
    public function getLabelValues($attr)
    {
        $values = array();
        $values[0] = $attr->getFrontend()->getLabel();
        // it can be array and cause bug
        $frontendLabel = $attr->getFrontend()->getLabel();
        if (is_array($frontendLabel)) {
            $frontendLabel = array_shift($frontendLabel);
        }
        $storeLabels = $attr->getStoreLabels();
        foreach ($this->getStores() as $store) {
            if ($store->getId() != 0) {
                $values[$store->getId()] = isset($storeLabels[$store->getId()]) ? $storeLabels[$store->getId()] : '';
            }
        }
        return $values;
    }



}

class ExSimpleXMLElement extends SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node= dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}