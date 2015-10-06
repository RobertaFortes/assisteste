<?php
class EM_Slideshow2_Model_Update extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('slideshow2/update');
    }
	
	public function version($ver="")
    {
		if($ver == "1.0.0")	$this->version_100();

        return true;
    }

	protected function version_100()
    {
		$helper = Mage::helper("slideshow2");
		$collection =  Mage::getModel('slideshow2/slider')->getCollection();

		if($collection->getSize() > 0){
			foreach($collection as $value){
				$iden	=	$value->getIdentifier();
				$desc	=	$value->getDescription();
				$images		=	unserialize(base64_decode($value->getImages()));
				$slider		=	unserialize($value->getSliderParams());
				$position	=	unserialize($value->getPosition());
				$appearance	=	unserialize($value->getAppearance());
				$navigation	=	unserialize($value->getNavigation());
				$thumbnail	=	unserialize($value->getThumbnail());
				$visibility	=	unserialize($value->getVisibility());
				$trouble	=	unserialize($value->getTrouble());

				if($iden == "" )	$value->setIdentifier("autoupdate_".$value->getId()."_".strtolower(str_replace(" ","_",$value->getName())));
				if($desc == "" )	$value->setDescription($helper->__("Coming soon"));
				if($images != "" ) 		$value->setImages($helper->emslider_encode($images,JSON_HEX_TAG));
				if($slider != "" )		$value->setSliderParams($helper->emslider_encode($slider));
				if($position != "" ) 	$value->setPosition($helper->emslider_encode($position));
				if($appearance != "" ) 	$value->setAppearance($helper->emslider_encode($appearance));
				if($navigation != "" ) 	$value->setNavigation($helper->emslider_encode($navigation));
				if($thumbnail != "" ) 	$value->setThumbnail($helper->emslider_encode($thumbnail));
				if($visibility != "" ) 	$value->setVisibility($helper->emslider_encode($visibility));
				if($trouble != "" ) 	$value->setTrouble($helper->emslider_encode($trouble));

				$value->save();
			}
		}
		return true;
    }

}