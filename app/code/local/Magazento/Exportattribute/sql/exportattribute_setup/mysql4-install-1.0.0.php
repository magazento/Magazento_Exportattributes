<?php
/*
* @category   Magazento
* @package    Magazento_Exportattribute
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/


$installer = $this;
$installer->startSetup();
$installer->run("

--
-- Table structure for table `magazento_exportattribute_item`
--

CREATE TABLE IF NOT EXISTS {$this->getTable('magazento_exportattribute_item')} (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT ' Id',
  `filename` varchar(32) DEFAULT NULL COMMENT 'Filename',
  `title` varchar(255) DEFAULT NULL COMMENT 'Title',
  `path` varchar(255) DEFAULT NULL COMMENT 'Path',
  `scope` varchar(255) DEFAULT NULL COMMENT 'Scope',
  `only_manual` varchar(255) DEFAULT NULL COMMENT 'Only Manual',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `{$this->getTable('magazento_exportattribute_item_related')}` (
  `item_id` smallint(6) unsigned DEFAULT NULL,
  `related_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




");




$installer->endSetup();
?>