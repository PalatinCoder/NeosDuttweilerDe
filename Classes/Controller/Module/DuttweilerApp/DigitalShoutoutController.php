<?php
namespace GSL\DuttweilerDe\Controller\Module\DuttweilerApp;

/**
 * This file is part of the GSL.DuttweilerDe Neos Site Package
 *
 * @author Jan Syring-Lingenfelder
 */

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Message;
use Neos\Neos\Controller\Module\AbstractModuleController;
use \GSL\DuttweilerDe\Service\GcmHelper;

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
	protected function initializeView(\Neos\Flow\Mvc\View\ViewInterface $view) {
		parent::initializeView($view);
		$view->setOption('layoutPathAndFilename', '../Packages/Application/Neos.Neos/Resources/Private/Layouts/BackendModule.html');
		$view->setOption('partialRootPaths', array('../Packages/Application/Neos.Neos/Resources/Private/Partials'));
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
	 *
	 * @Flow\Validate(argumentName="$heading", type="\Neos\Flow\Validation\Validator\TextValidator")
	 * @Flow\Validate(argumentName="$heading", type="\Neos\Flow\Validation\Validator\NotEmptyValidator")
	 * @Flow\Validate(argumentName="$heading", type="\Neos\Flow\Validation\Validator\StringLengthValidator", options={ "minimum"=1, "maximum"=40 })
	 * @Flow\Validate(argumentName="$message", type="\Neos\Flow\Validation\Validator\TextValidator")
	 * @Flow\Validate(argumentName="$message", type="\Neos\Flow\Validation\Validator\StringLengthValidator", options={ "minimum"=1, "maximum"=300 })
     * @return void
     */
    public function sendAction($heading, $message) {
		$gcmHelper = new GcmHelper;
		$gcmHelper->sendGcmMessage(array('heading' => $heading, 'message' => $message), 'duttweiler-shoutout');

		$this->addFlashMessage('Die Nachricht "%s" mit dem Inhalt "%s" wurde gesendet.', 'Durchsage gesendet', Message::SEVERITY_OK, array(htmlspecialchars($heading), htmlspecialchars($message)), 1456841323);
		$this->redirect('index');
    }
}
?>
