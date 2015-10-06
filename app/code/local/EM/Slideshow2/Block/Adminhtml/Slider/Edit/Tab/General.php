<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_general', array('legend'=>Mage::helper('slideshow2')->__('General')));

		$fieldset->addField('name', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Name'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'name',
		));

		$fieldset->addField('identifier', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Identifier'),
		  'title'     => Mage::helper('slideshow2')->__('Identifier'),
		  'class'     => 'validate-xml-identifier',
		  'required'  => true,
		  'name'      => 'identifier',
		));

		$fieldset->addField('description', 'textarea', array(
			'label' 	=> Mage::helper('slideshow2')->__('Description'),
			'class' 	=> 'required-entry',
			'style'		=> 'width:60em',
			'required'	=> true,
			'name' 		=> 'description',
		));

		// status field
		$fieldset->addField('status_slideshow', 'select', array(
			'label'     => Mage::helper('slideshow2')->__('Status'),
			'title'     => Mage::helper('slideshow2')->__('Status'),
			'name'      => 'status_slideshow',
			'options'   => array(
				'1' => Mage::helper('slideshow2')->__('Enabled'),
				'0' => Mage::helper('slideshow2')->__('Disabled'),
			),
		));

		$fieldset->addField('delay', 'text', array(
		  'label'     	=> Mage::helper('slideshow2')->__('Delay'),
		  'required'  	=> true,
		  'name'     	=> 'delay',
		  'note'		=> Mage::helper('slideshow2')->__('Default : 5000 = 5 seconds')
		));

		$fieldset->addField('touch', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Touch Enabled'),
		  'name'      => 'touch',
		  'note'		=> Mage::helper('slideshow2')->__('Enable Swipe Function on touch devices'),
		  'values'    => array(
			  array(
				  'value'     => 'on',
				  'label'     => Mage::helper('slideshow2')->__('On'),
			  ),
			  array(
				  'value'     => 'off',
				  'label'     => Mage::helper('slideshow2')->__('Off'),
			  ),
		  ),
		));

		$fieldset->addField('stop_hover', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Stop On Hover'),
		  'name'      => 'stop_hover',
		  'note'		=> Mage::helper('slideshow2')->__('Stop the Timer when hovering the slider'),
		  'values'    => array(
			  array(
				  'value'     => 'on',
				  'label'     => Mage::helper('slideshow2')->__('On'),
			  ),
			  array(
				  'value'     => 'off',
				  'label'     => Mage::helper('slideshow2')->__('Off'),
			  ),
		  ),
		));

		$fieldset->addField('shuffle_mode', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Shuffle Mode'),
		  'name'      => 'shuffle_mode',
		  'note'		=> Mage::helper('slideshow2')->__('The first image of the slideshow will be randomly shown.'),
		  'values'    => array(
			  array(
				  'value'     => 'on',
				  'label'     => Mage::helper('slideshow2')->__('On'),
			  ),
			  array(
				  'value'     => 'off',
				  'label'     => Mage::helper('slideshow2')->__('Off'),
			  ),
		  ),
		));

		$fieldset->addField('stop_slider', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Stop Slider'),
		  'name'      => 'stop_slider',
		  'note'		=> Mage::helper('slideshow2')->__('On / Off to stop slider after some amount of loops / slides'),
		  'values'    => array(
			  array(
				  'value'     => 'on',
				  'label'     => Mage::helper('slideshow2')->__('On'),
			  ),
			  array(
				  'value'     => 'off',
				  'label'     => Mage::helper('slideshow2')->__('Off'),
			  ),
		  ),
		));

		$fieldset->addField('stop_after_loop', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Stop After Loops'),
		  'name'      => 'stop_after_loop',
		  'note'		=> Mage::helper('slideshow2')->__('Stop the slider after certain amount of loops. 0 related to the first loop.'),
		));

		$fieldset->addField('stop_at_slide', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Stop At Slide'),
		  'name'      => 'stop_at_slide',
		  'note'		=> Mage::helper('slideshow2')->__('Stop the slider at the given slide'),
		));

		$fieldset->addField('slider_type', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Slider Type'),
		  'class'     => 'slider_type',
		  'name'      => 'slider_type',
		  'values'    => array(
			  array(
				  'value'     => 'fixed',
				  'label'     => Mage::helper('slideshow2')->__('Fixed'),
			  ),
			  array(
				  'value'     => 'responsitive',
				  'label'     => Mage::helper('slideshow2')->__('Responsitive'),
			  ),
			  array(
				  'value'     => 'fullwidth',
				  'label'     => Mage::helper('slideshow2')->__('Fullwidth'),
			  ),
			  array(
				  'value'     => 'fullsrceen',
				  'label'     => Mage::helper('slideshow2')->__('FullScreen'),
			  ),
		  ),
		));
		
		$fieldset2 = $form->addFieldset('slideshow2_general2', array('legend'=>Mage::helper('slideshow2')->__('Slider Size')));

		$fieldset2->addField('size_width', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Slider Width'),
		  'name'      => 'slider_params[size_width]',
		));
		
		$fieldset2->addField('size_height', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Slider Height'),
		  'name'      => 'slider_params[size_height]',
		));

		$fieldset3 = $form->addFieldset('slideshow2_general3', array('legend'=>Mage::helper('slideshow2')->__('Responsive Sizes')));

		for($i=1;$i<=10;$i++){
			$fieldset3->addField('screen_width_'.$i, 'text', array(
			  'label'     => Mage::helper('slideshow2')->__('Screen Width %d',$i),
			  'name'      => 'slider_params[screen_width_'.$i.']',
			));

			$fieldset3->addField('slider_width_'.$i, 'text', array(
			  'label'     => Mage::helper('slideshow2')->__('Slider Width %d',$i),
			  'name'      => 'slider_params[slider_width_'.$i.']',
			));
		}

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