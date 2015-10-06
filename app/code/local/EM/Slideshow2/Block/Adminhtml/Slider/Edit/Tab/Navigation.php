<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Navigation extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('slideshow2_navigation', array('legend'=>Mage::helper('slideshow2')->__('Navigation')));

		$fieldset->addField('nav_type', 'select', array(
			'label'     => Mage::helper('slideshow2')->__('Navigation Type'),
			'name'      => 'navigation[nav_type]',
			'note'		=> Mage::helper('slideshow2')->__('Display type of the navigation bar (Default:none)'),
			'values'    => Mage::getModel('slideshow2/navtype')->getOptionArray()
		));

		$fieldset->addField('nav_arrows', 'select', array(
			'label'     => Mage::helper('slideshow2')->__('Navigation Arrows'),
			'name'      => 'navigation[nav_arrows]',
			'note'		=> Mage::helper('slideshow2')->__('Display position of the Navigation Arrows (** By navigation Type Thumb arrows always centered or none visible)'),
			'values'    => Mage::getModel('slideshow2/navarrows')->getOptionArray()
		));

		$fieldset->addField('nav_style', 'select', array(
			'label'     => Mage::helper('slideshow2')->__('Navigation Style'),
			'name'      => 'navigation[nav_style]',
			'note'		=> Mage::helper('slideshow2')->__('if NavigationType "bullet" selected'),
			'values'    => Mage::getModel('slideshow2/navstyle')->getOptionArray()
		));

		$fieldset->addField('nav_offset_hor', 'text', array(
			'label'     => Mage::helper('slideshow2')->__('Nav. Offset Horizontal'),
			'name'      => 'navigation[nav_offset_hor]',
			'note'		=> Mage::helper('slideshow2')->__('The Bar is centered but could be moved this pixel count left(e.g. -10) or right (Default: 0) ** By resizing the banner, it will be always centered !'),
		))->setAfterElementHtml(' px');

		$fieldset->addField('nav_offset_vert', 'text', array(
			'label'     => Mage::helper('slideshow2')->__('Nav. Offset Vertical'),
			'name'      => 'navigation[nav_offset_vert]',
			'note'		=> Mage::helper('slideshow2')->__('The Bar is bound to the bottom but could be moved this pixel count up (e. g. -20) or down (Default: 20)'),
		))->setAfterElementHtml(' px');
		
		$fieldset->addField('nav_always_on', 'select', array(
			'label'     => Mage::helper('slideshow2')->__('Always Show Navigation'),
			'name'      => 'navigation[nav_always_on]',
			'note'		=> Mage::helper('slideshow2')->__('Always show the navigation and the thumbnails.'),
			'values'    => array(
				array(
					'value'     => 'true',
					'label'     => Mage::helper('slideshow2')->__('Yes'),
				),
				array(
					'value'     => 'false',
					'label'     => Mage::helper('slideshow2')->__('No'),
				),
			),
		));

		$fieldset->addField('hide_thumbs', 'text', array(
			'label'     => Mage::helper('slideshow2')->__('Hide Navitagion After'),
			'name'      => 'navigation[hide_thumbs]',
			'note'		=> Mage::helper('slideshow2')->__('Time after that the Navigation and the Thumbs will be hidden(Default: 200 ms)'),
		));

		if ( Mage::getSingleton('adminhtml/session')->getSlideshow2Data() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshow2Data());
			Mage::getSingleton('adminhtml/session')->setSlideshow2Data(null);
		} elseif ( Mage::registry('slideshow2_data') ) {
			$form->setValues(Mage::registry('slideshow2_data')->getData());
		}
		return parent::_prepareForm();
	}
}