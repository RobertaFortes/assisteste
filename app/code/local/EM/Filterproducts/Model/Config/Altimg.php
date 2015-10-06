<?php
class EM_Filterproducts_Model_Config_Altimg
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
		return array(
			array('label' => Mage::helper("filterproducts")->__("- None -") , 'value' => ""),
			array('label' => Mage::helper("filterproducts")->__("Base Image") , 'value' => "image"),
			array('label' => Mage::helper("filterproducts")->__("Small Image") , 'value' => "small_image"),
			array('label' => Mage::helper("filterproducts")->__("Thumbnail") , 'value' => "thumbnail")
		);
    }
}