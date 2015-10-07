<?php
class Piloti_ImportWinetagData_ImportWinetagDataController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$postedForm = Mage::app()->getRequest()->getPost();
    	if (!empty($postedForm)) {
			$this->loadLayout();
			$this->_setActiveMenu('piloti'); 

			if (isset($_FILES["arquivo"])) {

				$folder = $_SERVER["DOCUMENT_ROOT"] . "/var/html/";
				@mkdir($folder);

				$storagename = date("Y-m-d") . '_' . time() . ".html";

				$upload = move_uploaded_file($_FILES["arquivo"]["tmp_name"], $folder . $storagename);

				if ($upload) {

					$file = $folder . $storagename;

					$arrayOfWinetagData = Mage::helper('piloti_importwinetagdata/data')->convertHtmlFileToArray($file);

					$result = Mage::helper('piloti_importwinetagdata/data')->readArrayAndSaveData($arrayOfWinetagData);

					// ini_set("auto_detect_line_endings", true);

					// $csvContent = Mage::helper('piloti_importwinetagdata/data')->makeCSVFromArray($arrayOfWinetagData);

					// $folderCsv = $_SERVER["DOCUMENT_ROOT"] . "/var/csv/";
					// @mkdir($folderCsv);
					// $csvFileName = date("Y-m-d H:i:s") . '_' . time() . ".csv";

					// $csvFile = $folderCsv . $csvFileName;
					// $file = fopen($csvFile,"w");


					// foreach ($csvContent as $line)
					// {
					// 	fputcsv($file,explode('|',$line), ',');
					// }

					// fclose($file);

					// $file_url = $csvFile;
					// $fileName = 'test-'.time().'.csv';
					// header('Content-Type: text/csv; charset=utf-8; name="'.$fileName.'"');
					// header("Content-Transfer-Encoding: Binary");
					// header("Content-disposition: attachment; filename=\"".$fileName."\"");
					// readfile($file_url); // do the double-download-dance (dirty but worky)
				}
			}

			// if ($result) {
				$htmlResponse = 'Arquivo executado com sucesso!<br /><br />';
//				$htmlResponse .= 'Total de produtos inseridos: ' . $result["qtd_produtos_inseridos"] . "<br /><br />";
//				$htmlResponse .= 'Total de produtos atualizados: ' . $result["qtd_produtos_atualizados"] . "<br /><br />";
//				$htmlResponse .= 'Total de produtos existentes: ' . $result["qtd_produtos_existentes"] . "<br /><br />";

				$block = $this->getLayout()
				->createBlock('core/text', 'example-block')
				->setText($htmlResponse);

			// } else {

			// 	$block = $this->getLayout()
			// 	->createBlock('core/text', 'example-block')
			// 	->setText('Deu erro!');
			// }
			$this->_addContent($block);

			$this->renderLayout();

		} else {
			$this->loadLayout();
			$this->_setActiveMenu('piloti'); 


			$formAction = $this->getUrl('adminhtml/ImportWinetagData/index');
			
			$formHtml = '<form name="form" action="'.$formAction.'" method="post" enctype="multipart/form-data">
							<input name="form_key" type="hidden" value="' . Mage::getSingleton('core/session')->getFormKey() . '" />
							<input type="file" name="arquivo">
							<input type="submit" name="enviar" value="Enviar">
						</form>';
		    //create a text block with the name of "example-block"
		    $block = $this->getLayout()
		    ->createBlock('core/text', 'example-block')
		    ->setText($formHtml);

			$this->_addContent($block);
		    
		    $this->renderLayout();
    	}
    }
} 