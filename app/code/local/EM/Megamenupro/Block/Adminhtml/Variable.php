<?php
class EM_Megamenupro_Block_Adminhtml_Variable extends Mage_Core_Block_Template
{
	public function _prepareLayout(){
		$store_contact = $this->getStoreContact();
		$category = $this->getStoreCate();
		$icon = $this->getStoreIcon();

		$this->assign('store_contact', $store_contact);
		$this->assign('category', $category);
		$this->assign('icon', $icon);
		return parent::_prepareLayout();
	}

	public function getStoreContact(){
		$array = array(
			array(
				'link'	=>	'{{config path=\'web/unsecure/base_url\'}}',
				'label'	=>	$this->__("Base Unsecure URL")
			),array(
				'link'	=>	'{{config path=\'web/secure/base_url\'}}',
				'label'	=>	$this->__("Base Secure URL")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_general/name\'}}',
				'label'	=>	$this->__("General Contact Name")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_general/email\'}}',
				'label'	=>	$this->__("General Contact Email")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_sales/name\'}}',
				'label'	=>	$this->__("Sales Representative Contact Name")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_sales/email\'}}',
				'label'	=>	$this->__("Sales Representative Contact Email")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_custom1/name\'}}',
				'label'	=>	$this->__("Custom1 Contact Name")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_custom1/email\'}}',
				'label'	=>	$this->__("Custom1 Contact Email")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_custom2/name\'}}',
				'label'	=>	$this->__("Custom2 Contact Name")
			),array(
				'link'	=>	'{{config path=\'trans_email/ident_custom2/email\'}}',
				'label'	=>	$this->__("Custom2 Contact Email")
			),array(
				'link'	=>	'{{config path=\'general/store_information/name\'}}',
				'label'	=>	$this->__("Store Name")
			),array(
				'link'	=>	'{{config path=\'general/store_information/phone\'}}',
				'label'	=>	$this->__("Store Contact Telephone")
			),array(
				'link'	=>	'{{config path=\'general/store_information/address\'}}',
				'label'	=>	$this->__("Store Contact Address")
			)
		);
		return $array;
	}

	public function getStoreCate()
	{
		$rootCatId = Mage::app()->getStore()->getRootCategoryId();
		$catlistHtml = $this->getTreeCategories($rootCatId, false);
		return $catlistHtml;
	}

	public function getStoreIcon()
	{
		$path = Mage::getBaseDir('media') . DS . 'em' . DS . 'megamenupro' . DS . 'icon';
		$files = array();
		$dir = opendir($path);
		while ($f = readdir($dir)) {
			if(preg_match('/.jpg|.jpeg|.png|.gif$/', $f))
				array_push($files, '{{media url="em/megamenupro/icon/'.$f.'"}}');
		}
		closedir($dir);
		return $files;
	}

	public function getTreeCategories($parentId, $isChild, $level=0){
		$_helper= Mage::helper('catalog/category');
        $allCats = Mage::getModel('catalog/category')->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('is_active','1')
                    ->addAttributeToFilter('include_in_menu','1')
                    ->addAttributeToFilter('parent_id',array('eq' => $parentId));

        $class = ($isChild) ? "sub-cat-list emvariable-parent-".$parentId : "cat-list";
        $html = '<ul class="'.$class.'" ';
		if($level > 1)
			$html .= 'style="display:none"';
		$html .= '>';
        $children = Mage::getModel('catalog/category')->getCategories($parentId);
        foreach ($children as $category) {
			$subcats = $category->getChildren();

			if($category->getLevel() > 1){
				if($category->getChildrenCount() > 0){
					$html .= '<li class="has_sub">';
					$html .= '<div class="emvariable_subcat collapse">'.$category->getId().'</div>';
				}else
					$html .= '<li>';
				$html .= '<a onclick="insert_url(\''.$this->getStoreUrl($_helper->getCategoryUrl($category)).'\')" href="javascript:void(0);">'.$category->getName().'</a>'."";
			}

            if($subcats != ''){
                $html .= $this->getTreeCategories($category->getId(), true, $category->getLevel());
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

	public function getStoreUrl($url){
		if(!$url)	return "";
		else{
			$base = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
			$tmp = str_replace($base,"",$url);
			$tmp = str_replace("index.php/","",$tmp);

			return "{{store url=\'".$tmp."\'}}";
		}
	}

}