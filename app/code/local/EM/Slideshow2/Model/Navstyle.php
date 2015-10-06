<?php
class EM_Slideshow2_Model_Navstyle extends Varien_Object
{
    static public function getOptionArray()
    {
        return array(
            'preview1'		=> Mage::helper('slideshow2')->__('Style 1'),
            'preview2' 		=> Mage::helper('slideshow2')->__('Style 2'),
            'preview3' 		=> Mage::helper('slideshow2')->__('Style 3'),
            'preview4' 		=> Mage::helper('slideshow2')->__('Style 4'),
			'round-old' 	=> Mage::helper('slideshow2')->__('Old Round'),
            'round' 		=> Mage::helper('slideshow2')->__('Round'),
            'square-old' 	=> Mage::helper('slideshow2')->__('Old Square'),
            'square' 		=> Mage::helper('slideshow2')->__('Square'),
            'navbar-old' 	=> Mage::helper('slideshow2')->__('Old Navbar'),
            'navbar' 		=> Mage::helper('slideshow2')->__('Navbar')
        );
    }
}