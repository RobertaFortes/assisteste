<?php
class EM_Em0120settings_Adminhtml_Em0120settings_LinkController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function faqsAction() {
		$this->getResponse()->setRedirect('http://www.emthemes.com/faqs.html');
	}
	
	public function requestinstallationAction() {
		$this->getResponse()->setRedirect('http://emthemes.com/request-installation.html');
	}
	
	public function technicalsupportAction() {
		$this->getResponse()->setRedirect('http://emthemes.com/support.html');
	}
	
	public function contactusAction(){
		$this->getResponse()->setRedirect('http://emthemes.com/contact.html');
	}

	public function service_moreAction() {
		$this->getResponse()->setRedirect('http://emthemes.com/services.html');
	}
}