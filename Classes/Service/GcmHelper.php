<?php
namespace GSL\DuttweilerDe\Service;

use Neos\Flow\Annotations as Flow;

/**
 * Google Cloud Messaging helper class
 *
 * @author Jan Syring-Lingenfelder
 * @Flow\Scope("singleton")
 */

class GcmHelper {

	/**
	* @Flow\InjectConfiguration(path="googleCloudMessaging.debugOutput")
	* @var boolean
	*/
	protected $debugOutput;

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
	 * Send a GCM message
	 *
	 * @param array payload Key/value array containing the payload to send
	 * @param string channel The GCM Channel to send to
	 *
	 * @return void
	 */
	public function sendGcmMessage($payload, $channel) {
		$url = "https://android.googleapis.com/gcm/send";
		if ($this->debugOutput) {
			$url = "http://localhost/not-existing-url";
		}

		$post = array(
			'to' => '/topics/'.$channel,
			'delay_while_idle' => true,
			'data' => $payload
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
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);

		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new GcmException('Gcm Server Communication Error', 1434308758);
		}

		if (($httpCode != 200) && (!$this->debugOutput || $httpCode != 404)) 
		{
			throw new GcmException('Gcm Internal Error, returned '.$httpCode.', result is '.$result, 1434308786);
		}

		curl_close($ch);

		if ($this->debugOutput) {
			$ch_debug = curl_init("http://localhost/not-existing-url/");
			curl_setopt($ch_debug, CURLOPT_URL, "http://localhost/not-existing-url/".json_encode($post));
			curl_setopt($ch_debug, CURLOPT_REFERER, $httpCode);
			curl_exec($ch_debug);
			curl_close($ch_debug);
		}
	}
}
?>