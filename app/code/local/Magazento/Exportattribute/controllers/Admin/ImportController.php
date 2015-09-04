<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Admin_ImportController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/exportattribute')
            ->_addBreadcrumb(Mage::helper('exportattribute')->__('exportattribute'), Mage::helper('exportattribute')->__('exportattribute'))
            ->_addBreadcrumb(Mage::helper('exportattribute')->__('exportattribute Items'), Mage::helper('exportattribute')->__('exportattribute Items'));
        return $this;
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['xml_file']['name']) && $_FILES['xml_file']['name'] != '') {
                try {
                    $xmlFile = $_FILES["xml_file"]["tmp_name"];
                } catch (Exception $e) {
                    echo $e;
                    exit();
                }
            }

            $result = Mage::getModel('exportattribute/import')->importFromFile($xmlFile);
            foreach ($result['errors'] as $error) {
                Mage::getSingleton('adminhtml/session')->addError($error);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('exportattribute')->__('Items imported: %s', $result['total']));
            $this->_redirect('*/admin_item/index');
        }
    }

    public function indexAction()
    {
        $this->loadLayout(array('default', 'editor'))
            ->_setActiveMenu('system/exportattribute');

        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('exportattribute/admin_import_edit'))
            ->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('exportattribute/item');
    }

}