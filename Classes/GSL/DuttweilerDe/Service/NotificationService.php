<?php
namespace GSL\DuttweilerDe\Service;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Notice;
use TYPO3\Flow\Mvc\FlashMessageContainer;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\Workspace;
use GSL\DuttweilerDe\Domain\Repository\PushNotificationRepository;
use GSL\DuttweilerDe\Domain\Model\PushNotification;

/**
 * Service for sending notification via GCM when new nodes are published
 *
 * @Flow\Scope("singleton")
 */
class NotificationService {

	/**
	 * @Flow\InjectConfiguration(path="notificationService.enabled")
	 * @var boolean
	 */
	protected $enabled;

	/**
	 * Sets the state of the service
	 *
	 * @param boolean $enabled
	 * @return void
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}

	/**
	 * Returns the state of the service
	 *
	 * @return boolean
	 */
	public function getEnabled() {
		return $this->enabled;
	}

	/**
	 * @Flow\Inject
	 * @var PushNotificationRepository
	 */
	protected $pushNotificationRepository;

	/**
	 * @Flow\Inject
	 * @var FlashMessageContainer
	 */
	protected $flashMessageContainer;

	/**
	 * Check if the published node is a News and send GCM request
	 *
	 * @param NodeInterface $node
	 * @param Workspace $targetWorkspace
	 * @return void
	 */
	public function notifyNodePublished(NodeInterface $node, Workspace $targetWorkspace) {	
		if (!(
			($this->enabled)
			&& ($node->getNodeType()->isOfType('GSL.DuttweilerDe.Pages:ChronikItem'))
			&& ($targetWorkspace->getName() == 'live')
			&& (!$node->isHidden()) && ($node->getHiddenBeforeDateTime() < new \TYPO3\Flow\Utility\Now)
		    // check if node is in scope of the api
		    // Current ChronikBranch is the first child?
			&& ($node->getParent()->getParent()->getChildNodes('GSL.DuttweilerDe.Pages:ChronikBranch', 1, 0)[0] == $node->getParent())
            // node is among the first ten?
			&& ($node->getIndex() < $node->getParent()->getChildNodes('GSL.DuttweilerDe.Pages:ChronikItem', 1, 10)[0]->getIndex())
			)
		) 
		{ return; }

		/** @var PushNotification $notification */
		$notification = new PushNotification($node->getProperty('title'), $node->getProperty('subheadline'), $node->getName());
		$this->pushNotificationRepository->add($notification);

		$this->flashMessageContainer->addMessage(new Notice('Push Notification generiert', null, [], 'Push Notification'));
	}
}
?>
