<?php
class EM_Slideshow2_Model_Navtype extends Varien_Object
{
    static public function getOptionArray()
    {
        return array(
            'none'    	=> Mage::helper('slideshow2')->__('None'),
            'bullet'    => Mage::helper('slideshow2')->__('Bullet'),
            'thumb'    	=> Mage::helper('slideshow2')->__('Thumb')
        );
    }
}