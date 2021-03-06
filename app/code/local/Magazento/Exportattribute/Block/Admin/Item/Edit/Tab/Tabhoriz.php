<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Block_Admin_Item_Edit_Tab_Tabhoriz extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('general_tab');
        $this->setDestElementId('exportattribute_tabs_form_section_item_content');
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    protected function _prepareLayout()
    {
//        $this->addTab('content', array(
//            'label'     => $this->__('Additional'),
//            'content'   => $this->getLayout()->createBlock('exportattribute/admin_item_edit_tab_tabhoriz_additional')->toHtml(),
//            'active'    => true
//        ));
        $this->addTab('form', array(
            'label'     => $this->__('General'),
            'content'   => $this->getLayout()->createBlock('exportattribute/admin_item_edit_tab_tabhoriz_form')->toHtml(),
            'active'    => true
        ));

        return parent::_prepareLayout();
    }
}