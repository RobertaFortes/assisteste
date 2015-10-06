<?php

$installer = $this;

$installer->startSetup();
Mage::helper('em0120settings/sample')->importSampleData($installer);
$installer->endSetup();
?>