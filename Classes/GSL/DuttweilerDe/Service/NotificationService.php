<?php
namespace GSL\DuttweilerDe\Service;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ControllerInterface;
use TYPO3\Flow\Mvc\RequestInterface;
use TYPO3\Flow\Mvc\ResponseInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\Workspace;
use TYPO3\Flow\Http\Response;
use TYPO3\Neos\Controller\Frontend\NodeController;
use TYPO3\Flow\Log\SystemLoggerInterface;

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

		/** @var GcmHelper gcmHelper */
		$gcmHelper = new GcmHelper;

		$nodeData = array(
					'title' => $node->getProperty('title'),
					'subheadline' => $node->getProperty('subheadline'),
					'nodeName' => $node->getName() 
				);

		$gcmHelper->sendGcmMessage($nodeData, 'duttweiler-news');
	}
}
?>
