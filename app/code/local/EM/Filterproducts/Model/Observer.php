<?php
class EM_Filterproducts_Model_Observer
{
    public function cleanBestSellerCache($observer)
	{
		Mage::app()->cleanCache(array(EM_Filterproducts_Block_List::BEST_SELLER_CACHE_TAG));
	}
}
?>
