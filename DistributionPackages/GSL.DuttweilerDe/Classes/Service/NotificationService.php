<?php
namespace GSL\DuttweilerDe\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Error\Messages\Notice;
use Neos\Flow\Mvc\FlashMessageContainer;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\Workspace;
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
			&& ($targetWorkspace->getName() == 'live')
			&& (!$node->getNodeType()->isOfType('Neos.Neos:ContentCollection'))
			)
		) { return; }

		$q = new FlowQuery([$node]);
		/** @var NodeInterface $documentNode */
		$documentNode = $q->closest('[instanceof Neos.Neos:Document]')->get(0);

		if (!(
			($documentNode !== null)
			&& ($documentNode->getNodeType()->isOfType('GSL.DuttweilerDe.Pages:ChronikItem'))
			&& (!$documentNode->isHidden()) && ($documentNode->getHiddenBeforeDateTime() < new \Neos\Flow\Utility\Now)
		    // check if node is in scope of the api
		    // Current ChronikBranch is the first child?
			&& ($documentNode->getParent()->getParent()->getChildNodes('GSL.DuttweilerDe.Pages:ChronikBranch', 1, 0)[0] == $documentNode->getParent())
            // node is among the first ten? (i.e. index is smaller than the tenth index or we have less than ten nodes anyway)
			&& (
				  count($documentNode->getParent()->getChildNodes('GSL.DuttweilerDe.Pages:ChronikItem', 10)) <= 10
				  || ($documentNode->getIndex() < $documentNode->getParent()->getChildNodes('GSL.DuttweilerDe.Pages:ChronikItem', 1, 10)[0]->getIndex())
				 )
			// we don't have a noficiation for this document already
			&& (!$this->pushNotificationRepository->findOneByNodeName($documentNode->getName()))
			)
		) 
		{ return; }

		/** @var PushNotification $notification */
		$notification = new PushNotification($documentNode->getProperty('title'), $documentNode->getProperty('subheadline'), $documentNode->getName());
		$this->pushNotificationRepository->add($notification);

		$this->flashMessageContainer->addMessage(new Notice('Push Notification "%s" generiert', null, array($documentNode->getProperty('title')), 'Push Notification'));
	}
}
?>
