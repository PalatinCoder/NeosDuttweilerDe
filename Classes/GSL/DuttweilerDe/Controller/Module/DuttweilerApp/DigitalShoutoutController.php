<?php
namespace GSL\DuttweilerDe\Controller\Module\DuttweilerApp;

/**
 * This file is part of the GSL.DuttweilerDe Neos Site Package
 *
 * @author Jan Syring-Lingenfelder
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Neos\Controller\Module\AbstractModuleController;

/**
 * The DigitalShoutout Controller
 *
 * @Flow\Scope("singleton")
 */
class DigitalShoutoutController extends AbstractModuleController {

	/**
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();
	}

	/**
	 * @return void
	 */
	protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view) {
		parent::initializeView($view);
		$view->setLayoutPathAndFilename('../Packages/Application/TYPO3.Neos/Resources/Private/Layouts/BackendModule.html');
		$view->setPartialRootPath('../Packages/Application/TYPO3.Neos/Resources/Private/Partials');
	}

	/**
	 * Show a form to enter a message
	 * 
	 * @return void
	 */
	public function indexAction() {
	}

    /**
     * Send a message to GCM
     *
     * @param string $heading The Heading
     * @param string $message The Message
     * @Flow\Validate(argumentName="heading", type="\TYPO3\Flow\Validation\Validator\NotEmptyValidator")
     * @return void
     */
    public function sendAction($heading, $message) {
		$this->addFlashMessage('Die Nachricht "%s" mit dem Inhalt "%s" wurde gesendet.', 'Durchsage gesendet', Message::SEVERITY_OK, array(htmlspecialchars($heading), htmlspecialchars($message)), 1456841323);
		$this->redirect('index');
    }
}
?>
