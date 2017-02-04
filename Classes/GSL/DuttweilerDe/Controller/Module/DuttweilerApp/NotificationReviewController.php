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
	protected function initializeView(\Neos\Flow\Mvc\View\ViewInterface $view) {
		parent::initializeView($view);
		$view->setLayoutPathAndFilename('../Packages/Application/Neos.Neos/Resources/Private/Layouts/BackendModule.html');
		$view->setPartialRootPath('../Packages/Application/Neos.Neos/Resources/Private/Partials');
	}

	/**
	 * Show a list of all pending push notifications
	 * 
	 * @return void
	 */
	public function indexAction() {

		$pendingNotifications = [];

		/** @var PushNotification $notification */
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
		/** @var PushNotification $notification */
		$notification = $this->pushNotificationRepository->findOneById($notificationId);
		$this->makeGcmRequest($notification);
		$this->pushNotificationRepository->remove($notification);
		$this->addFlashMessage('Benachrichtigung "%s" gesendet', 'Benachrichtigung gesendet', Message::SEVERITY_OK, array($notification->getHeadline()));
		$this->redirect('index');
	}

	/**
	 * Discard a single notification
	 *
	 * @param int $notificationId The identifier of the notification
	 * @return void
	 */
	public function discardAction($notificationId) {
		/** @var PushNotification $notification */
		$notification = $this->pushNotificationRepository->findOneById($notificationId);
		$this->pushNotificationRepository->remove($notification);
		$this->addFlashMessage('Benachrichtigung "%s" verworfen', 'Benachrichtigung verworfen', Message::SEVERITY_OK, array($notification->getHeadline()));
		$this->redirect('index');
	}

	/**
	 * Send all notifications
	 *
	 * @return void
	 */
	public function sendAllAction() {
		/** @var PushNotification $notification */
		foreach($this->pushNotificationRepository->findAll() as $notification) {
			$this->makeGcmRequest($notification);
		}
		$this->pushNotificationRepository->removeAll();
		$this->addFlashMessage('Alle Benachrichtigungen gesendet');
		$this->redirect('index');
	}

	/**
	 * Discard all notifications
	 *
	 * @return void
	 */
	public function discardAllAction() {
		$this->pushNotificationRepository->removeAll();
		$this->addFlashMessage('Alle Benachrichtigungen verworfen');
		$this->redirect('index');
	}

	/**
	 * Send a given notification to the app via GCM
	 *
	 * @param PushNotification $notification
	 * @return void
	 */
	protected function makeGcmRequest(PushNotification $notification) {

		/** @var GcmHelper $gcmHelper */
		$gcmHelper = new GcmHelper;
		$gcmHelper->sendGcmMessage(array(
			'title' => $notification->getHeadline(),
			'subheadline' => $notification->getSubheadline(),
			'nodeName' => $notification->getNodeName()
			), 'duttweiler-news');
	}
}
?>
