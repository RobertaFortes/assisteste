<?php
class Piloti_ImportWinetagData_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function convertHtmlFileToArray($file) {
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($file);

		$elements = $doc->getElementsByTagName('tr');

		$array = array();
		$i = $j = 0;
		if (!is_null($elements)) {
			foreach ($elements as $element) {
				$nodes = $element->childNodes;
				$j = 0;
				foreach ($nodes as $node) {
					if ($i != 0) {
						$array[$i][$j] = $node->nodeValue;
						$j++;
					}
				}
				$i++;
			}
		}
		return $array;
	}

	public function readArrayAndSaveData($array) {

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

		// Array ( [0] => ID
		// 		[1] => 
		// 		[2] => MENU_CATEGORY
		// 		[3] => 
		// 		[4] => NAME
		// 		[5] => 
		// 		[6] => DESCRIPTION
		// 		[7] => 
		// 		[8] => PRICE
		// 		[9] => 
		// 		[10] => OBS
		// 		[11] => 
		// 		[12] => TYPE
		// 		[13] => 
		// 		[14] => CHARACTERISTICS
		// 		[15] => 
		// 		[16] => IMAGE_PATH
		// 		[17] => 
		// 		)

		ignore_user_abort(true);
		set_time_limit(0);
		ini_set('max_execution_time', 0);
		$qtd_produtos_inseridos = $qtd_produtos_atualizados = $i = 0;

		foreach ($array as $key => $value) {
			$i++;

			$product_sku = 'wt-' . utf8_decode($value[0]); // 1023
			$product_category = utf8_decode($value[2]); // Cervejas Especiais || Cervejas Tradicionais || Drinks
			$product_name = utf8_decode($value[4]); // 1795 Pilsener
			$product_description = utf8_decode($value[6]); // string gigante
			$product_price = utf8_decode($value[8]); // 22,8
			$product_obs = utf8_decode($value[10]); // 500 ml
			$product_type = utf8_decode($value[12]); // cerveja || drink || licor || whisky
			$product_characteristics = utf8_decode($value[14]); // 4,7/Pilsner/Rep_blica Tcheca/
			$product_image = utf8_decode($value[16]); // http://res.cloudinary.com/tagmeapi/image/fetch/http://www.winetag.com.br/pubimg/products/1795.jpg

			// PRICE
			$product_price = str_replace(',', '.', $product_price);
			$product_price = str_pad($product_price, 5, "0", STR_PAD_RIGHT);
			// PRICE

			// ATTRIBUTES
			$attributes = explode('/', $product_characteristics);
			$attribute_volume = $this->getVolumeByString($product_obs);

			$pos = strpos($product_name, 'Kit');
			if($pos === false) {
				$attribute_pais = (isset($attributes["2"])) ? $attributes["2"] : '';
				$attribute_teor_alcoolico = (isset($attributes["0"])) ? $attributes["0"] : '';
				$attribute_estilos = (isset($attributes["1"])) ? $attributes["1"] : '';
			} else {
				$attribute_pais = (isset($attributes["1"])) ? $attributes["1"] : '';
				$attribute_teor_alcoolico = '';
				$attribute_estilos = (isset($attributes["0"])) ? $attributes["0"] : '';
			}
			$attribute_teor_alcoolico = $this->getTeorAlcoolicoByString($attribute_teor_alcoolico);
			$attribute_pais = $this->getPaisByString($attribute_pais);
			$attribute_estilos = $this->getEstilosByString($attribute_estilos);
			// ATTRIBUTES

			// IMAGE
			$has_image = false;
			$check_image = explode('/', $product_image);
			if( (strpos($check_image[count($check_image)-1], '.jpg') !== false) || (strpos($check_image[count($check_image)-1], '.png') !== false) ) {
				$has_image = true;
				$name_tmp = $check_image[count($check_image)-1];
				$ext = strrev($name_tmp);
				$ext = strstr($ext, '.', true);
				$ext = strrev($ext);
				$imageName = "var/images/tmp_product_image_" . $i . "_" . time() . "." . $ext;
				$imageContent = file_get_contents($product_image);
				is_dir('var/images') || @mkdir('var/images');
				file_put_contents($imageName, $imageContent);
			}
			// IMAGE

			$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$product_sku);

			if($product_type == 'cerveja' && $product):
				// update

				$storeId = 0;
				$data = array(
					'name' => $product_name,
					'price' => $product_price,
					'cost' => $product_price,
					'estilos' => $attribute_estilos,
					'pais' => $attribute_pais,
					'teor_alcoolico' => $attribute_teor_alcoolico,
					'description' => $product_description,
					'short_description' => $product_description,
					'volume' => $attribute_volume
					);
				Mage::getSingleton('catalog/product_action')->updateAttributes(array($product->getId()), $data, $storeId);
				unset($product);
				$qtd_produtos_atualizados++;
			elseif($product_type == 'cerveja'):
				$product = Mage::getModel('catalog/product');
				try{
					$product
						// ->setStoreId(1) //you can set data in store scope
						->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
						->setAttributeSetId(77) //ID of a attribute set named 'default'
						->setTypeId('simple') //product type
						->setCreatedAt(strtotime('now')) //product creation time
						// ->setUpdatedAt(strtotime('now')) //product update time

						->setSku($product_sku) //SKU
						->setName($product_name) //product name
						->setWeight(4.0000)
						->setStatus(1) //product status (1 - enabled, 2 - disabled)
						->setTaxClassId(0) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
						->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
						// ->setManufacturer(28) //manufacturer id
						// ->setColor(24)
						->setNewsFromDate(date("m/d/Y")) //product set as new from
						->setNewsToDate(date("m/d/Y")) //product set as new to
						// ->setCountryOfManufacture('AF') //country of manufacture (2-letter country code)

						->setPrice($product_price) //price in form 11.22
						->setCost($product_price) //price in form 11.22

						->setEstilos($attribute_estilos) //price in form 11.22
						->setPais($attribute_pais) //price in form 11.22
						->setTeor_alcoolico($attribute_teor_alcoolico) //price in form 11.22
						->setVolume($attribute_volume) //price in form 11.22
						// ->setSpecialPrice(00.44) //special price in form 11.22
						// ->setSpecialFromDate('06/1/2014') //special price from (MM-DD-YYYY)
						// ->setSpecialToDate('06/30/2014') //special price to (MM-DD-YYYY)
						// ->setMsrpEnabled(1) //enable MAP
						// ->setMsrpDisplayActualPriceType(1) //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
						// ->setMsrp(99.99) //Manufacturer's Suggested Retail Price

						// ->setMetaTitle('test meta title 2')
						// ->setMetaKeyword('test meta keyword 2')
						// ->setMetaDescription('test meta description 2')

						->setDescription($product_description)
						->setShortDescription($product_description);
						if($has_image) {
							$product->
							setMediaGallery(array('images' => array(), 'values' => array()))//media gallery initialization
							->addImageToMediaGallery($imageName, array('image', 'thumbnail', 'small_image'), false, false); //assigning image, thumb and small image to media gallery
						}
						$product->setStockData(array(
										   'use_config_manage_stock' => 0, //'Use config settings' checkbox
										   'manage_stock'=>1, //manage stock
										   'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
										   'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
										   'is_in_stock' => 1, //Stock Availability
										   'qty' => 999 //qty
									   )
						)

						->setCategoryIds(array(3, 35)); //assign product to categories
					$product->save();
					$qtd_produtos_inseridos++;
					echo 'ok';
					unset($product);
				}catch(Exception $e){
					echo ($e->xdebug_message); die;
					Mage::log($e->getMessage());
				}
			endif;
			!$has_image || @unlink($imageName);
		}

		$array_resultado = array(
			"qtd_produtos_inseridos" => $qtd_produtos_inseridos,
			"qtd_produtos_existentes" => $i,
			"qtd_produtos_atualizados" => $qtd_produtos_atualizados
		);
		return $array_resultado;
	}

	/* montando csv de importação do magento */
	// $list = array(
	// 	"store",
	// 	"websites",
	// 	"attribute_set",
	// 	"type",
	// 	"category_ids",
	// 	"sku",
	// 	"has_options",
	// 	"name",
	// 	"manufacturer",
	// 	"url_key",
	// 	"meta_title",
	// 	"meta_description",
	// 	"image",
	// 	"small_image",
	// 	"thumbnail",
	// 	"gift_message_available",
	// 	"options_container",
	// 	"custom_design",
	// 	"url_path",
	// 	"country_of_manufacture",
	// 	"msrp_enabled",
	// 	"msrp_display_actual_price_type",
	// 	"page_layout",
	// 	"weight",
	// 	"price",
	// 	"cost",
	// 	"minimal_price",
	// 	"special_price",
	// 	"msrp",
	// 	"description",
	// 	"meta_keyword",
	// 	"in_depth",
	// 	"dimension",
	// 	"model",
	// 	"activation_information",
	// 	"short_description",
	// 	"custom_layout_update",
	// 	"color",
	// 	"status",
	// 	"tax_class_id",
	// 	"visibility",
	// 	"is_recurring",
	// 	"em_featured",
	// 	"em_deal",
	// 	"em_hot",
	// 	"special_from_date",
	// 	"special_to_date",
	// 	"custom_design_from",
	// 	"custom_design_to",
	// 	"news_from_date",
	// 	"news_to_date",
	// 	"qty",
	// 	"min_qty",
	// 	"use_config_min_qty",
	// 	"is_qty_decimal",
	// 	"backorders",
	// 	"use_config_backorders",
	// 	"min_sale_qty",
	// 	"use_config_min_sale_qty",
	// 	"max_sale_qty",
	// 	"use_config_max_sale_qty",
	// 	"is_in_stock",
	// 	"low_stock_date",
	// 	"notify_stock_qty",
	// 	"use_config_notify_stock_qty",
	// 	"manage_stock",
	// 	"use_config_manage_stock",
	// 	"stock_status_changed_auto",
	// 	"use_config_qty_increments",
	// 	"qty_increments",
	// 	"use_config_enable_qty_inc",
	// 	"enable_qty_increments",
	// 	"is_decimal_divided",
	// 	"stock_status_changed_automatically",
	// 	"use_config_enable_qty_increments",
	// 	"product_name",
	// 	"store_id",
	// 	"product_type_id",
	// 	"product_status_changed",
	// 	"product_changed_websites",
	// 	"thumbnail_label",
	// 	"small_image_label",
	// 	"image_label",
	// 	"processor",
	// 	"memory",
	// 	"hardrive",
	// 	"screensize",
	// 	"computer_manufacturers",
	// 	"shoe_type",
	// 	"gender",
	// 	"shoe_size",
	// 	"shirt_size",
	// 	"country_orgin",
	// 	"finish",
	// 	"room",
	// 	"megapixels",
	// 	"ram_size",
	// 	"cpu_speed",
	// 	"max_resolution",
	// 	"response_time",
	// 	"contrast_ratio",
	// 	"harddrive_speed",
	// 	"price_type",
	// 	"sku_type",
	// 	"weight_type",
	// 	"price_view",
	// 	"shipment_type",
	// 	"samples_title",
	// 	"links_title",
	// 	"links_purchased_separately",
	// 	"links_exist"
	// );

	public function makeCSVFromArray($array){
		// $csv = array('id|categoria|nome|descricao|preco|observacao|tipo|caracteristicas|imagem');
		// $csv = array('type_id|_attribute_set|tax_class_id|weight|sku|name|price|description|short_description');
		$csv = array('"type_id"|"_attribute_set"|"tax_class_id"|"weight"|"sku"|"name"|"price"|"description"|"short_description"|"visibility"|"_root_category"|"_category"|"_product_websites"|"status"|"qty"|"is_in_stock"');
		
		// Array ( [0] => ID
		// 		[1] => 
		// 		[2] => MENU_CATEGORY
		// 		[3] => 
		// 		[4] => NAME
		// 		[5] => 
		// 		[6] => DESCRIPTION
		// 		[7] => 
		// 		[8] => PRICE
		// 		[9] => 
		// 		[10] => OBS
		// 		[11] => 
		// 		[12] => TYPE
		// 		[13] => 
		// 		[14] => CHARACTERISTICS
		// 		[15] => 
		// 		[16] => IMAGE_PATH
		// 		[17] => 
		// 		)

		foreach ($array as $key => $value) {
			$str = '';
			// $str .= utf8_decode($value[0]).'|'.utf8_decode($value[2]).'|'.utf8_decode($value[4]).'|'.utf8_decode($value[6]).'|'.utf8_decode($value[8]).'|'.utf8_decode($value[10]).'|'.utf8_decode($value[12]).'|'.utf8_decode($value[14]).'|'.utf8_decode($value[16]);
			// $str .= 'simple|Default|0|0|'.utf8_decode($value[0]).'|'.utf8_decode($value[4]).'|'.utf8_decode($value[8]).'|'.utf8_decode($value[6]).'|'.utf8_decode($value[14]);
			$str .= '"simple"|"Default"|"0"|"0"|"'.utf8_decode($value[0]).'"|"'.utf8_decode($value[4]).'"|"'.utf8_decode($value[8]).'"|"'.utf8_decode(str_replace('"', "'", $value[6])).'"|"'.utf8_decode($value[14].'"|"4"|"Default Category"|"3,37"|"base"|"1"|"10"|"1"');
			$csv[] = $str;
		}

		return $csv;
	}

	public function getTeorAlcoolicoByString($teor) {

		if(!empty($teor)) {
			$teor_int = (int)$teor + 1;
			if($teor_int < 4) $teor_int = 4;
			if($teor_int > 10) $teor_int = 11;
			switch ($teor_int) {
				case "4":
					return "154"; break;
				case "5":
					return "148"; break;
				case "6":
					return "149"; break;
				case "7":
					return "150"; break;
				case "8":
					return "151"; break;
				case "9":
					return "152"; break;
				case "10":
					return "153"; break;
				case "11":
					return "147"; break;
				default:
					return '';
					break;
			}
		} else {
			return '';
		}
	}

	public function getPaisByString($pais) {
		switch(utf8_encode($pais)) {
			case "Alemanha":
				return "138"; 
			break;
			case "Austria":
			case "Áustria":
				return "137"; 
			break;
			case "Bélgica":
			case "Belgica":
				return "136"; 
			break;
			case "Brasil":
				return "135"; 
			break;
			case "Dinamarca":
				return "161"; 
			break;
			case "Escócia":
			case "Escocia":
				return "163"; 
			break;
			case "Espanha":
				return "162"; 
			break;
			case "Estados Unidos":
				return "166";; 
			break;
			case "Holanda":
				return "165"; 
			break;
			case "Inglaterra":
				return "158"; 
			break;
			case "Itália":
			case "Italia":
				return "164"; 
			break;
			case "Jamaica":
				return "159"; 
			break;
			case "República Tcheca":
			case "Republica Tcheca":
				return "167"; 
			break;
			case "Uruguai":
				return "160"; 
			break;
			default:
				return ''; 
			break;
		}
	}

	public function getEstilosByString($estilo) {
		switch($estilo) {
			case "Alcool Free": 
				return "226"; 
			break;
			case "Alcool Free": 
				return "176"; 
			break;
			case "Ale ": 
				return "196"; 
			break;
			case "Ale Red": 
				return "221"; 
			break;
			case "Ale Rood": 
				return "169"; 
			break;
			case "Amber Lager": 
				return "186"; 
			break;
			case "Ambree": 
				return "235"; 
			break;
			case "American Brown Ale": 
				return "172"; 
			break;
			case "American Pale Ale": 
				return "177"; 
			break;
			case "American Wheat": 
				return "199"; 
			break;
			case "Barley Wine": 
				return "205"; 
			break;
			case "Belgian Blond Ale": 
				return "204"; 
			break;
			case "Belgian Blonde Ale": 
				return "243"; 
			break;
			case "Belgian Dark Strong Ale ": 
				return "225"; 
			break;
			case "Belgian Dark Strong Ale": 
				return "230"; 
			break;
			case "Belgian Golden Strong Ale": 
				return "237"; 
			break;
			case "Belgian Golden Strong Ale": 
				return "224"; 
			break;
			case "Belgian Pale Ale": 
				return "239"; 
			break;
			case "Belgian Pale Ale": 
				return "241"; 
			break;
			case "Belgian Specialty Ale": 
				return "234"; 
			break;
			case "Belgian Specialty Ale": 
				return "238"; 
			break;
			case "Bitter ": 
				return "218"; 
			break;
			case "Bitter Ale": 
				return "195"; 
			break;
			case "Black IPA": 
				return "173"; 
			break;
			case "Blond ": 
				return "236"; 
			break;
			case "Blond Ale": 
				return "197"; 
			break;
			case "Bock ": 
				return "184"; 
			break;
			case "Bohemian Pilsen": 
				return "194"; 
			break;
			case "Bohemian Pilsener": 
				return "185"; 
			break;
			case "Brown Ale": 
				return "247"; 
			break;
			case "Brown Red ": 
				return "244"; 
			break;
			case "Brut": 
				return "223"; 
			break;
			case "Dark Strong Ale ": 
				return "168"; 
			break;
			case "Double IPA": 
				return "215"; 
			break;
			case "Dry Stout": 
				return "131"; 
			break;
			case "Dubbel ": 
				return "240"; 
			break;
			case "Dunkel ": 
				return "188"; 
			break;
			case "English Brown Ale": 
				return "220"; 
			break;
			case "English Pale Ale": 
				return "229"; 
			break;
			case "Extra IPA": 
				return "217"; 
			break;
			case "Farmhouse Ale": 
				return "216"; 
			break;
			case "Fruit Beer": 
				return "132"; 
			break;
			case "Fruit Wheat Beer": 
				return "213"; 
			break;
			case "Golden Ale": 
				return "219"; 
			break;
			case "Golden Strong Ale": 
				return "222"; 
			break;
			case "Imperial IPA": 
				return "206"; 
			break;
			case "Imperial Stout": 
				return "249"; 
			break;
			case "IPA": 
				return "133"; 
			break;
			case "Keller": 
				return "210"; 
			break;
			case "Lager ": 
				return "180"; 
			break;
			case "Lagers ": 
				return "190"; 
			break;
			case "Munich Helles": 
				return "231"; 
			break;
			case "Pale Ale ": 
				return "191"; 
			break;
			case "Pilsen ": 
				return "200"; 
			break;
			case "Pilsen Premium": 
				return "248"; 
			break;
			case "Pilsner ": 
				return "134"; 
			break;
			case "Porter ": 
				return "233"; 
			break;
			case "Premium American Lager": 
				return "209"; 
			break;
			case "Premium American Lager": 
				return "212"; 
			break;
			case "Premium American Lager": 
				return "183"; 
			break;
			case "Quadrupel ": 
				return "242"; 
			break;
			case "Rauchbier ": 
				return "193"; 
			break;
			case "Red Ale": 
				return "174"; 
			break;
			case "Red IPA": 
				return "175"; 
			break;
			case "Robust Porter": 
				return "208"; 
			break;
			case "Saison ": 
				return "178"; 
			break;
			case "Schwarzbier ": 
				return "250"; 
			break;
			case "Session IPA": 
				return "214"; 
			break;
			case "Smoked IPA": 
				return "198"; 
			break;
			case "Smoked Porter": 
				return "171"; 
			break;
			case "Specialty Ale": 
				return "246"; 
			break;
			case "Standard American Lager": 
				return "181"; 
			break;
			case "Stout ": 
				return "202"; 
			break;
			case "Strong Ale": 
				return "170"; 
			break;
			case "Strong Dark Ale": 
				return "211"; 
			break;
			case "Strong Golden Ale": 
				return "192"; 
			break;
			case "Strong Pale Ale": 
				return "187"; 
			break;
			case "Tripel ": 
				return "189"; 
			break;
			case "Urweisse ": 
				return "201"; 
			break;
			case "Vienna": 
				return "245"; 
			break;
			case "Vienna Lager": 
				return "179"; 
			break;
			case "Weiss ": 
				return "227"; 
			break;
			case "Weizen": 
				return "203"; 
			break;
			case "Weizenbock": 
				return "228"; 
			break;
			case "Weizenbock ": 
				return "182"; 
			break;
			case "Witbier": 
				return "207"; 
			break;
			case "Wood Aged Beer": 
				return "232"; 
			break;
			default:
				return ''; 
			break;
		}
	}

	public function getVolumeByString($volume) {
		$volume_int = (int)$volume + 1;
		switch($volume_int) {
			case "250ml": 
			case "250 ml": 
				return "255"; 
			break;
			case "265ml": 
			case "265 ml": 
				return "256"; 
			break;
			case "300ml": 
			case "300 ml": 
				return "257"; 
			break;
			case "310ml": 
			case "310 ml": 
				return "258"; 
			break;
			case "330ml": 
			case "330 ml": 
				return "259"; 
			break;
			case "335ml": 
			case "335 ml": 
				return "260"; 
			break;
			case "355ml": 
			case "355 ml": 
				return "261"; 
			break;
			case "355ml": 
			case "355 ml": 
				return "262"; 
			break;
			case "375ml": 
			case "375 ml": 
				return "263"; 
			break;
			case "400ml": 
			case "400 ml": 
				return "264"; 
			break;
			case "440ml": 
			case "440 ml": 
				return "155"; 
			break;
			case "500ml": 
			case "500 ml": 
				return "156"; 
			break;
			case "550ml": 
			case "550 ml": 
				return "157"; 
			break;
			case "600ml": 
			case "600 ml": 
				return "252"; 
			break;
			case "650ml": 
			case "650 ml": 
				return "253"; 
			break;
			case "750ml": 
			case "750 ml": 
				return "254"; 
			break;
			case "1L": 
			case "1 L": 
				return "251"; 
			break;
			case "Dose": 
				return "265"; 
			break;
			case "Ou Caipirinha": 
				return "266"; 
			break;
			case "Smirnoff": 
				return "267"; 
			break;
			case "Tripel": 
				return "268"; 
			break;
			default:
				return ''; break;
		}
	}

} // end of class
?>