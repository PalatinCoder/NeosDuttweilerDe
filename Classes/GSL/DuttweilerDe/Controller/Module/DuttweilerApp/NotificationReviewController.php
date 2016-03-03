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
use \GSL\DuttweilerDe\Service\GcmHelper;
use GSL\DuttweilerDe\Domain\Repository\PushNotificationRepository;
use GSL\DuttweilerDe\Domain\Model\PushNotification;

/**
 * The NotificationManagement Controller
 *
 * @Flow\Scope("singleton")
 */
class NotificationReviewController extends AbstractModuleController {

	/**
	 *
	 * @Flow\Inject
	 * @var PushNotificationRepository
	 */
	protected $pushNotificationRepository;

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
	 * Show a list of all pending push notifications
	 * 
	 * @return void
	 */
	public function indexAction() {

		$pendingNotifications = [];

		foreach ($this->pushNotificationRepository->findAll() as $notification) {
			$pendingNotifications[] = [
				'heading' => $notification->getHeadline(),
				'subheading' => $notification->getSubheadline(),
				'issueDate' => $notification->getIssueDate(),
				'id' => $notification->getId()
			];
		}

		$this->view->assign('pendingNotifications', $pendingNotifications);
	}

	/**
	 * Send a single notification
	 *
	 * @param int $notificationId Identifier of the notification
	 * @return void
	 */
	public function sendAction($notificationId) {
		$this->addFlashMessage('Dummy: send %d', 'Dummy Impelementation', Message::SEVERITY_OK, array($notificationId));
		$this->redirect('index');
	}

	/**
	 * Discard a single notification
	 *
	 * @param int $notificationId The identifier of the notification
	 * @return void
	 */
	public function discardAction($notificationId) {
		$this->addFlashMessage('Dummy: discard %d', 'Dummy Impelementation', Message::SEVERITY_OK, array($notificationId));
		$this->redirect('index');
	}

	/**
	 * Send all notifications
	 *
	 * @return void
	 */
	public function sendAllAction() {
		$this->addFlashMessage('Dummy: sendAll', 'Dummy Impelementation');
		$this->redirect('index');
	}

	/**
	 * Discard all notifications
	 *
	 * @return void
	 */
	public function discardAllAction() {
		$this->addFlashMessage('Dummy: discardAll', 'Dummy Impelementation');
		$this->redirect('index');
	}
}
?>
