<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `runsize` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `additionalrunsize` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `flatdimension` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `finisheddimension` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `stocktype` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `coatingfinish` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `bindery` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `finishing` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `imagepreview` text NOT NULL;
");

$installer->endSetup();
