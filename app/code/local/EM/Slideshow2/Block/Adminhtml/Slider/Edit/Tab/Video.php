<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Video extends Mage_Adminhtml_Block_Widget_Form
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

	public function _toHtml(){
		$this->setTemplate('em_slideshow2/slider_video_upload.phtml');
		$video = $this->getVideo();

		$this->assign('count', count($video));
		$this->assign('video', $video);
		return parent::_toHtml();
	}

	public function getVideo(){
		$path = Mage::getBaseDir('media') . DS . 'em_slideshow' . DS . 'video';
		$files = array();
		$dir = opendir($path);
		while ($f = readdir($dir)) {
			if(preg_match('/.wma|.mp4|.avi$/', $f))
				array_push($files, $f);
		}
		closedir($dir);

		return $files;
	}
}