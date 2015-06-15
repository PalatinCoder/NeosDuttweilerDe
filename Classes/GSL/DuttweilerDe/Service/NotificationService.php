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
		
		#$url = "http://localhost/not-existing-url";
		$url = "https://android.googleapis.com/gcm/send";

		$post = array(
				'to' => '/topics/duttweiler-news',
				'data' => array( 'message' => 'news='.$node->getName() )
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

		if ($httpCode != 200) {
			throw new GcmException('Gcm Internal Error, returned '.$httpCode, 1434308786);
		}

		\TYPO3\FLOW\var_dump($result);

		curl_close($ch);

		$ch_debug = curl_init("http://localhost/not-existing-url/");
		curl_setopt($ch_debug, CURLOPT_URL, "http://localhost/not-existing-url/");
		curl_setopt($ch_debug, CURLOPT_REFERER, $httpCode);
		curl_setopt($ch_debug, CURLOPT_USERAGENT, $result);
		curl_exec($ch_debug);
		curl_close($ch_debug);
	}
}
?>
