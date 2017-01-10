<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `lstname` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `aso` varchar(255) NOT NULL;
");

$installer->endSetup();
