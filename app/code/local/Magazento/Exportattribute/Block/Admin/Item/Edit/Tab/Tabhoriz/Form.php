<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Block_Admin_Item_Edit_Tab_Tabhoriz_Form extends Mage_Adminhtml_Block_Widget_Form {


    protected function _prepareForm() {
        $model = Mage::registry('exportattribute_item');

        $form = new Varien_Data_Form(array('id' => 'edit_form_item', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('item_');

        if (!$model->getScope()) $model->setData('scope','all');

        $fieldset = $form->addFieldset('base_fieldset_automation', array('legend' => Mage::helper('exportattribute')->__('General settings'), 'class' => 'fieldset-wide'));

        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', array(
                'name' => 'item_id',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('exportattribute')->__('Title'),
            'name'  => 'title',
            'required' => true,
        ));

        $fieldset->addField('filename', 'text', array(
            'label' => Mage::helper('exportattribute')->__('Filename'),
            'name'  => 'filename',
            'required' => true,
            'note'  => Mage::helper('exportattribute')->__('example: export_june (without extension)'),
        ));

        $fieldset->addField('path', 'text', array(
            'label' => Mage::helper('exportattribute')->__('Path'),
            'name'  => 'path',
            'required' => true,
            'note'  => Mage::helper('exportattribute')->__('example: "export/" or "/" for base path (path must be writeable)'),
        ));

        $scopes = array(
            array('value' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE, 'label' => Mage::helper('catalog')->__('Store View')),
            array('value' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE, 'label' => Mage::helper('catalog')->__('Website')),
            array('value' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL, 'label' =>Mage::helper('catalog')->__('Global')),
            array('value' => 'all', 'label' => Mage::helper('exportattribute')->__('All Scopes')),
        );

        $fieldset->addField('only_manual', 'select', array(
            'name' => 'only_manual',
            'label' => Mage::helper('exportattribute')->__('Export Only Manual Items'),
            'title' => Mage::helper('exportattribute')->__('Export Only Manual Items'),
            'required' => true,
            'value' => 1,
            'options' => array(
                '1' => Mage::helper('exportattribute')->__('Yes'),
                '0' => Mage::helper('exportattribute')->__('No'),
            ),
        ));

        $fieldset->addField('scope', 'select', array(
            'name'  => 'scope',
            'label' => Mage::helper('catalog')->__('Scope'),
            'title' => Mage::helper('catalog')->__('Scope'),
            'note'  => Mage::helper('catalog')->__('Attribute value scope'),
            'values'=> $scopes
        ));



        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
