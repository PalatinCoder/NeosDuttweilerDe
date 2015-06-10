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
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @param ControllerInterface $controller
	 * @return void
	 */
	
	public function notifyNodePublished(RequestInteface $request, ResponseInterface $response, ControllerInterface $controller) {
		if (!$response instanceof Response || !$controller instanceof NodeController) {
			return;
		}
		$arguments = $controller->getControllerContext()->getArguments();
		if (!$arguments->hasArgument('node')) {
			return;
		}
		$node = $arguments->getArgument('node')->getValue();
		if (!$node instanceof NodeInterface) {
			return;
		}
		if ($node->getContext()->getWorkspaceName() !== 'live') {
			return;
		}

		// unstable from here 

		if (!$node->getNodeType()->isOfType('GSL.DuttweilerDe.Pages:ChronikItem')) {
			return;
		}

		var_dump ($node);
		
		// check if node is new or changed
}
