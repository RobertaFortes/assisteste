<?php
/** @var $installer Mage_Catalog_Model_Resource_Setup */
$installer  = $this;

	$pathFile = Mage::getBaseDir('var').DS.'[EM_Slideshow2]update_1-0-0.txt';
	if(file_exists($pathFile)){
		echo 'Updating EM Slideshow2 version 1.0.0 , please come back in some minutes ...';
		exit;
	}
	file_put_contents($pathFile,'Updating EM Slideshow2 version 1.0.0');

	$installer->getConnection()->addColumn(
		$installer->getTable('slideshow2/slider'),
		'identifier',
		'VARCHAR(100) NULL'
	);

	$installer->getConnection()->addColumn(
		$installer->getTable('slideshow2/slider'),
		'description',
		array(
			'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
			'length'     => '2M',
			'comment'   => 'Slideshow Description'
		)
	);

	Mage::getModel("slideshow2/update")->version("1.0.0");
	unlink($pathFile);

$installer->endSetup(); 