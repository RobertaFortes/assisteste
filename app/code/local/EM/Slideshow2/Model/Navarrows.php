<?php
class EM_Slideshow2_Model_Navarrows extends Varien_Object
{
    static public function getOptionArray()
    {
        return array(
            'nexttobullets'		=> Mage::helper('slideshow2')->__('Next To Bullets'),
            'verticalcentered' 	=> Mage::helper('slideshow2')->__('Vertical Centered'),
            'none'    			=> Mage::helper('slideshow2')->__('None')
        );
    }
}