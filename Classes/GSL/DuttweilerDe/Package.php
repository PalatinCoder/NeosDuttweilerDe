<?php
namespace GSL\DuttweilerDe;

use TYPO3\Flow\Package\Package as BasePackage;

class Package extends BasePackage {

	/**
	 * Register slots for to get notified on new nodes, to push them to DuttweilerApp via GCM
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		#$dispatcher = $bootstrap->getSignalSlotDispatcher();
		#$dispatcher->connect('TYPO3\Neos\Service\PublishingService', 'nodePublished', 'GSL\DuttweilerDe\Service\NotificationService', 'notifyNodePublished');
	}
}
