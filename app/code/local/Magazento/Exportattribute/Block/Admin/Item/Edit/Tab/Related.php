<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportattribute_Block_Admin_Item_Edit_Tab_Related extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
       
        parent::__construct();
        $this->setId('related');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {

        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareColumns() {
         $this->addColumn('in_related', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'field_name'=> 'related_list[]',
            'values'    => $this->_getSelectedItems(),
            'align'     => 'center',
            'index'     => 'attribute_id'
        ));

        $this->addColumn('attribute_id', array(
            'header'=> Mage::helper('sales')->__('Id'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'attribute_id',
        ));

        $this->addColumn('attribute_code', array(
            'header'=>Mage::helper('eav')->__('Attribute Code'),
            'sortable'=>true,
            'index'=>'attribute_code'
        ));

        $this->addColumn('frontend_label', array(
            'header'=>Mage::helper('eav')->__('Attribute Label'),
            'sortable'=>true,
            'index'=>'frontend_label'
        ));

        $this->addColumnAfter('is_visible', array(
            'header'=>Mage::helper('catalog')->__('Visible'),
            'sortable'=>true,
            'index'=>'is_visible_on_front',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('catalog')->__('Yes'),
                '0' => Mage::helper('catalog')->__('No'),
            ),
            'align' => 'center',
        ), 'frontend_label');

        $this->addColumnAfter('is_global', array(
            'header'=>Mage::helper('catalog')->__('Scope'),
            'sortable'=>true,
            'index'=>'is_global',
            'type' => 'options',
            'options' => array(
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE =>Mage::helper('catalog')->__('Store View'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>Mage::helper('catalog')->__('Website'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL =>Mage::helper('catalog')->__('Global'),
            ),
            'align' => 'center',
        ), 'is_visible');

        $this->addColumn('is_searchable', array(
            'header'=>Mage::helper('catalog')->__('Searchable'),
            'sortable'=>true,
            'index'=>'is_searchable',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('catalog')->__('Yes'),
                '0' => Mage::helper('catalog')->__('No'),
            ),
            'align' => 'center',
        ), 'is_user_defined');

        $this->addColumnAfter('is_filterable', array(
            'header'=>Mage::helper('catalog')->__('Use in Layered Navigation'),
            'sortable'=>true,
            'index'=>'is_filterable',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('catalog')->__('Filterable (with results)'),
                '2' => Mage::helper('catalog')->__('Filterable (no results)'),
                '0' => Mage::helper('catalog')->__('No'),
            ),
            'align' => 'center',
        ), 'is_searchable');

        $this->addColumnAfter('is_comparable', array(
            'header'=>Mage::helper('catalog')->__('Comparable'),
            'sortable'=>true,
            'index'=>'is_comparable',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('catalog')->__('Yes'),
                '0' => Mage::helper('catalog')->__('No'),
            ),
            'align' => 'center',
        ), 'is_filterable');




        return parent::_prepareColumns();
    }

  public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/relatedGrid', array('_current' => true));
    }


  protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_related') {
            $relatedIds = $this->_getSelectedItems();

            if (empty($relatedIds)) {
                $relatedIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('item_id', array('in'=>$relatedIds));
            } else {
                if($relatedIds) {
                    $this->getCollection()->addFieldToFilter('item_id', array('nin'=>$relatedIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    
    protected function _getSelectedItems()
    {
        $id = Mage::app()->getFrontController()->getRequest()->get('item_id');
        $model = Mage::getModel('exportattribute/item')->load($id);
        $relatedItems = $model->getData('related_id');
        return $relatedItems;
    }    
 
}

?>
