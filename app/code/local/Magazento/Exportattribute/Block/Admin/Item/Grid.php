<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Block_Admin_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('ExportattributeGrid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('exportattribute/item')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $baseUrl = $this->getUrl();

        $this->addColumn('item_id', array(
            'header' => Mage::helper('exportattribute')->__('Id'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'item_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('exportattribute')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('link', array(
            'header'    => Mage::helper('exportattribute')->__('Files '),
            'renderer'  => 'exportattribute/admin_item_grid_renderer_link',
        ));

        $this->addColumn('scope', array(
            'header'=>Mage::helper('catalog')->__('Scope'),
            'sortable'=>true,
            'index'=>'scope',
            'type' => 'options',
            'options' => array(
                'all' =>Mage::helper('catalog')->__('All Scopes'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE =>Mage::helper('catalog')->__('Store View'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>Mage::helper('catalog')->__('Website'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL =>Mage::helper('catalog')->__('Global'),
            ),
            'align' => 'center',
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('exportattribute')->__('Action'),
                    'index' => 'item_id',
                    'sortable' => false,
                    'filter' => false,
                    'no_link' => true,
                    'width' => '100px',
                    'renderer' => 'exportattribute/admin_item_grid_renderer_action'
        ));
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('massaction');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('exportattribute')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('exportattribute')->__('Are you sure?')
        ));


        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit',  array('item_id' => $row->getId(), 'type' => $row->getData('item_type')));
    }

}
