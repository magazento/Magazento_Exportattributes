<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Model_Import
{
    private $XML = null;
    private $importModel = null;
    private $errors = array();

    /*
     * Import Items
     */
    public function importFromFile($xmlFile)
    {
        $total = 0;
        $xmlContents = file_get_contents($xmlFile);
        $this->XML = simplexml_load_string($xmlContents);

        foreach ($this->XML as $item) {
            try {

                $total++;
                $this->importModel = Mage::getModel('catalog/resource_eav_attribute');

                // Item Values
                $itemValues = json_decode(json_encode($item->ItemValues));
                foreach ($itemValues as $k=>$v) {
                    $this->importModel->setData($k, (string)$v);
                }


                // Item Labels
                $itemLabels  = json_decode(json_encode($item->FrontendLabels));
                $labelsArray['frontend_label'] = array();
                foreach ($itemLabels as $k=>$v) {
                    if ($v) {
                        $storeId = str_replace('store_','',$k);
                        $labelsArray['frontend_label'][$storeId] = $v;
                    }
                }

                $this->importModel->addData($labelsArray);
                $this->importModel->save();

                // Item Options
                $optionsArray = array();
                $orderArray = array();
                $options = $item->Option->Value;
                $i=0;
                foreach ($options as $value) {
                    $i++;
                    $id = (string)$value->id;
                    if (!$id) $id = 'option_'.$i;
                    $optionsArray[$id] = array();

                    $order = (string)$value->sort_order;
                    $orderArray[$id] = $order;

                    $storeValues = json_decode(json_encode($value->Store));
                    foreach ($storeValues as $k=>$storeValue) {
                        $v = (string)$storeValue;
                        if ($v) {
                            $storeId = str_replace('store_','',$k);
                            $optionsArray[$id][$storeId] = $v;
                        }
                    }
                }

//                var_dump($optionsArray);
//                exit();

                $data['option']['value'] = $optionsArray;
                $data['option']['order'] = $orderArray;
                $this->importModel->addData($data);
                $this->importModel->save();

//                var_dump($data);
//                exit();



            } catch(Exception $e) {
                $total--;
                $this->errors[] = $item->ItemValues->attribute_code . ' : ' . $e->getMessage();
            }
        }

        $result = array(
            'total' => $total,
            'errors' => $this->errors,
        );


        return $result;

    }

}

