<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Block_Admin_Item_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('exportattribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('exportattribute')->__('Attribute Export Profile'));
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
         

    protected function _beforeToHtml() {
        $this->addTab('form_section_item', array(
            'label' => Mage::helper('exportattribute')->__('General information'),
            'title' => Mage::helper('exportattribute')->__('General information'),
            'content' => $this->getLayout()->createBlock('exportattribute/admin_item_edit_tab_tabhoriz')->toHtml(),
        ));

        $this->addTab('related', array(
            'label' => Mage::helper('catalog')->__('Manual Items'),
            'url' => $this->getUrl('*/*/related', array('_current' => true)),
            'class' => 'ajax',
        ));

        return parent::_beforeToHtml();
    }

}