<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Block_Admin_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form data
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('import_general', array(
            'legend' => Mage::helper('core')->__('Import Attribute')
        ));

        $fieldset->addField('xml_file', 'file', array(
            'label'     => Mage::helper('core')->__('XML file'),
            'name'      => 'xml_file',
            'value'     => 'Upload',
            'disabled'  => false,
            'required'  => true,
        ));


        $form->setAction($this->getUrl('*/*/save'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}