<?php
/**
 * @methods:
 * - get[Section]_[ConfigName]($defaultValue = '')
 */
class EM_Em0120settings_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function __call($name, $args) {
		if (method_exists($this, $name))
			call_user_func_array(array($this, $name), $args);
			
		elseif (preg_match('/^get([^_][a-zA-Z0-9_]+)$/', $name, $m)) {
			$segs = explode('_', $m[1]);
			foreach ($segs as $i => $seg){
				//$segs[$i] = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg));
				$seg = preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg);
				$seg = preg_replace('/([A-Z])([A-Z])/', '$1_$2', $seg);
				$segs[$i] = strtolower(preg_replace('/([A-Z])([A-Z])/', '$1_$2', $seg));
			}
			$value = Mage::getStoreConfig('em0120/'.implode('/', $segs));
			if (!$value) $value = @$args[0];
			return $value;
		}
		
		else 
			call_user_func_array(array($this, $name), $args);
	}
    
    /**
     * @return array
     * Get css config
    */    
    public function getAllCssConfig() {
        /** Mang luu tru bien duoi dang less */
        $configs = array();
        $skinUrl = 'frontend/default/em0120/css/';
        $stripesUrl = 'skin/frontend/default/em0120/images/stripes/';
        
        /** import less file */
		$variables_url = Mage::getDesign()->getSkinUrl('css/less/theme.less');
        $function_url = Mage::getDesign()->getSkinUrl('css/less/functions.less');		
		$configs['@variables_url'] = "\"{$variables_url}\"";
        $configs['@function_url'] = "\"{$function_url}\"";
        
        /** Lay bien tu file less.php. File less luu gia tri mac dinh cua bien. 
            Ko khai bao gia tri mac dinh cua bien trong file config.xml do co the ra gia tri null => less.js ko lay duoc bien
            Chi config bien google font va bien google font weight
        */   
		$typoArray = require_once(Mage::getBaseDir('skin') . DS . $skinUrl . 'less/less.php');
		foreach($typoArray as $typo => $value){
            $configValue = Mage::getStoreConfig('em0120/typography/'.$typo) ? Mage::getStoreConfig('em0120/typography/'.$typo) : $value;
            if (preg_match("/\\s/",$configValue)) {
				$configs["@{$typo}"] = "~\"$configValue\"";
			}
			else{	
				$configs["@{$typo}"] = "{$configValue}";
			}
		}
        
		/** Backgroung Image */        
        /** Khai bao bien luu duong dan background image trong less */        
		$image_bg_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $configs['@image_bg_url'] = "~\"{$image_bg_url}\"";
        
        $page_bg_image = Mage::getStoreConfig('em0120/typography/page_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('em0120/typography/page_bg_file')
			: (Mage::getStoreConfig('em0120/typography/page_bg_image') ? 'skin/frontend/default/em0120/images/stripes/'.Mage::getStoreConfig('em0120/typography/page_bg_image') : 'skin/frontend/default/em0120/images/stripes/blank.gif');
        
        $header_bg_image = Mage::getStoreConfig('em0120/typography/header_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('em0120/typography/header_bg_file')
			: (Mage::getStoreConfig('em0120/typography/header_bg_image') ? 'skin/frontend/default/em0120/images/stripes/'.Mage::getStoreConfig('em0120/typography/header_bg_image') : 'skin/frontend/default/em0120/images/stripes/blank.gif');
        
        $body_bg_image = Mage::getStoreConfig('em0120/typography/body_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('em0120/typography/body_bg_file')
			: (Mage::getStoreConfig('em0120/typography/body_bg_image') ? 'skin/frontend/default/em0120/images/stripes/'.Mage::getStoreConfig('em0120/typography/body_bg_image') : 'skin/frontend/default/em0120/images/stripes/blank.gif');
        
        $footer_bg_image = Mage::getStoreConfig('em0120/typography/footer_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('em0120/typography/footer_bg_file')
			: (Mage::getStoreConfig('em0120/typography/footer_bg_image') ? 'skin/frontend/default/em0120/images/stripes/'.Mage::getStoreConfig('em0120/typography/footer_bg_image') : 'skin/frontend/default/em0120/images/stripes/blank.gif');
                            
		$configs['@page_bg_image'] = "~\"{$page_bg_image}\"";
        $configs['@header_bg_image'] = "~\"{$header_bg_image}\"";
		$configs['@body_bg_image'] = "~\"{$body_bg_image}\"";
		$configs['@footer_bg_image'] = "~\"{$footer_bg_image}\""; 
        
        /** custom css file */      
		if($additionalCssFilesString = explode(',', Mage::getStoreConfig('em0120/typography/additional_css_file'))){
            $i=0;
            foreach($additionalCssFilesString as $add){
                if (preg_match("/.less/",$add)) {
                    $configs['additional_css_file'][$i] = Mage::getDesign()->getSkinUrl('css/').$add;
                }
                $i++;
            }
        } /*
        if($additionalCssFilesString = Mage::getStoreConfig('em0120/typography/additional_css_file')){
			$configs['custom_css_files'] = Mage::getDesign()->getSkinUrl('css/').$additionalCssFilesString;
		}*/
        /** return less variable array */       
		return $configs;
	}
	
	public function insertStaticBlock($dataBlock) {
		// insert a block to db if not exists
		$block = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('identifier', $dataBlock['identifier'])->getFirstItem();
		if (!$block->getId())
			$block->setData($dataBlock)->save();
		return $block;
	}
	
	public function insertPage($dataPage) {
		$page = Mage::getModel('cms/page')->getCollection()->addFieldToFilter('identifier', $dataPage['identifier'])->getFirstItem();
		if (!$page->getId())
			$page->setData($dataPage)->save();
		return $page;
	}
	
	// For search by category
    public function getCategoriesCustomSearch($parent,$curId){
		$result = '';
		if($parent->getLevel() == 1){
            $result = "<option value='0'>".$this->getCatNameCustom($parent)."</option>";
		}			
		else{
			$result = "<option value='".$parent->getId()."' ";
			
			if($curId){
				if($curId	==	$parent->getId()) $result .= " selected='selected'";
			}
			$result .= ">".$this->getCatNameCustom($parent)."</option>";			
		}
		
		try{
			$children = $parent->getChildrenCategories();
			
			if(count($children) > 0){
				foreach($children as $cat){
					$result .= $this->getCategoriesCustomSearch($cat,$curId);
				}
			}
		}
		catch(Exception $e){
			return '';
		}
        //var_dump($result);
		return $result;
	}
	
	public function getCatNameCustom($category){
		$level = $category->getLevel();
		$html = '';
		for($i = 0;$i < $level;$i++){
			$html .= '&nbsp;&nbsp;&nbsp;';
		}
		if($level == 1)	return $html.' '.$this->__("All Categories");
		else return $html.' '.$category->getName();
	}
	
    public function checkMobilePhp() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checkmobile = $detect->isMobile();
        $checktablet = $detect->isTablet();
        if($checkmobile){
            if($checktablet){
                return false;
            }else{
                return true;
            }
            
        }else{
            return false;
        }
	}
	public function isShowOfferPrice($productPrice){
		if(!Mage::registry('current_product'))
			return false;
		return Mage::registry('current_product')->getId() == $productPrice->getId();
	}
    public function checkMobile() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checkmobile = $detect->isMobile();
        if($checkmobile){
            return 'true';            
        }else{
            return 'false';
        }
	}
    
    public function checkTabletPhp() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checktablet = $detect->isTablet();
        if($checktablet){
            return 'true';
        }else{
            return 'false';
        }
	}
    
    public function getActionReview(){
		$url = Mage::helper('core/url')->getCurrentUrl();
		$url_check = 'wishlist/index/configure';
		$url_check2 = 'checkout/cart/configure';
		if(stripos($url,$url_check)){
			$id = Mage::registry('current_product')->getId();
			return Mage::getUrl('review/product/post/', array('id' => $id,'_secure' => true));
		} else {
			if(stripos($url,$url_check2)){
				$id = Mage::getSingleton('catalog/session')->getLastViewedProductId();
				return Mage::getUrl('review/product/post/', array('id' => $id,'_secure' => true));
			}else{
				$productId = Mage::app()->getRequest()->getParam('id', false);
				return Mage::getUrl('review/product/post', array('id' => $productId,'_secure' => true));
			}
		}
	}
}
