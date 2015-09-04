<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Admin_ExportController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('system/exportattribute')
                ->_addBreadcrumb(Mage::helper('exportattribute')->__('exportattribute'), Mage::helper('exportattribute')->__('exportattribute'))
                ->_addBreadcrumb(Mage::helper('exportattribute')->__('exportattribute Items'), Mage::helper('exportattribute')->__('exportattribute Items'))
        ;
        return $this;
    }


    public function indexAction() {

        if ($id = $this->getRequest()->getParam('item_id')) {

            try {
                $result = Mage::getModel('exportattribute/export')->exportItemsForProfile($id);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('exportattribute')->__('Items exported: %s', $result['total']));
                Mage::getSingleton('adminhtml/session')->addSuccess($result['fileUrl']);
                $this->_redirect('*/admin_item/index');

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/admin_item/index');
                return;
            }
        }
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('exportattribute/item');
    }

}