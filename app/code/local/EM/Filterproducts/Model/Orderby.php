<?php
class EM_Filterproducts_Model_Orderby extends Mage_Core_Model_Abstract 
{
    /**
     * @return array
     */
    public function toOptionArray()
	{
		$result[] = array('value' => 'name asc','label' =>  'Name ASC');
		$result[] = array('value' => 'name desc','label' =>  'Name DESC');
		$result[] = array('value' => 'price asc','label' =>  'Price ASC');
		$result[] = array('value' => 'price desc','label' =>  'Price DESC');
		return $result;
	}
}
?>
