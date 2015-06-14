<?php
namespace GSL\DuttweilerDe\Service;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ControllerInterface;
use TYPO3\Flow\Mvc\RequestInterface;
use TYPO3\Flow\Mvc\ResponseInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
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
	 * Check if the published node is a News and send GCM request
	 *
	 * @param NodeInterface $node
	 * @return void
	 */
	
	public function notifyNodePublished(NodeInterface $node) {	
		
		if (!$node->getNodeType()->isOfType('GSL.DuttweilerDe.Pages:ChronikItem')) {
			return;
		}
		
		$url = "http://localhost/not-existing-url";

		$ch = curl_init($url);	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, "News: ".$node->getProperty('title'));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		curl_exec($ch);

		curl_close($ch);
	}
}
?>
