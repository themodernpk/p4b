<?php

$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$this->getTable('cms_page')} ADD `background` varchar( 500 ) NOT NULL DEFAULT '';");

$installer->endSetup();

?>
