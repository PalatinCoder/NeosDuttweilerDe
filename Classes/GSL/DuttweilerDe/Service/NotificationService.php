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
	 * @Flow\InjectConfiguration(path="notificationService.debugOutput")
	 * @var boolean
	 */
	protected $debugOutput;

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
	 * Enables/Disabled debug output (via curl)
	 *
	 * @param boolean $enabled
	 * @return void
	 */
	public function setDebugOutput($enabled) {
		$this->debugOutput = $enabled;
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
			($this->enabled) &&
			($node->getNodeType()->isOfType('GSL.DuttweilerDe.Pages:ChronikItem')) &&
			($targetWorkspace->getName() == 'live') //&&
			// check if node is in scope of the api; that is node itself is under the first ten and node's parent is first child
			//($node->getParent()->getIndex() == 0) &&
			//($node->getIndex() < 10)
			)
		) 
		{ return; }
		
		$url = "https://android.googleapis.com/gcm/send";
		if ($this->debugOutput) {
			$url = "http://localhost/not-existing-url";
		}

		$nodeData = array(
					'title' => $node->getProperty('title'),
					'subheadline' => $node->getProperty('subheadline'),
					'nodeName' => $node->getName() 
				);

		$post = array(
				'to' => '/topics/duttweiler-news',
				'delay_while_idle' => true,
				'data' => $nodeData
			);
		$headers = array(
				'Authorization: key='.GcmKeyProvider::getServerKey(),
				'Content-Type: application/json'
			);

		$ch = curl_init($url);	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post) );
		curl_setopt($ch, CURLOPT_REFERER, "http://www.duttweiler.de/api/news.json");

		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new GcmException('Gcm Server Communication Error', 1434308758);
		}

		if ($httpCode != 200 && $httpCode != 404) {
			throw new GcmException('Gcm Internal Error, returned '.$httpCode.', result is '.$result, 1434308786);
		}

		curl_close($ch);

		if ($this->debugOutput) {
			$ch_debug = curl_init("http://localhost/not-existing-url/");
			curl_setopt($ch_debug, CURLOPT_URL, "http://localhost/not-existing-url/");
			curl_setopt($ch_debug, CURLOPT_REFERER, $httpCode);
			curl_setopt($ch_debug, CURLOPT_USERAGENT, $result);
			curl_exec($ch_debug);
			curl_close($ch_debug);
		}
	}
}
?>
