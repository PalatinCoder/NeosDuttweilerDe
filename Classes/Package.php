<?php
namespace GSL\DuttweilerDe;

use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\Core\Bootstrap;

class Package extends BasePackage {

	/**
	 * Register slots for to get notified on new nodes, to push them to DuttweilerApp via GCM
	 *
	 * @param \Neos\Flow\Core\Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('Neos\ContentRepository\Domain\Model\Workspace', 'afterNodePublishing', 'GSL\DuttweilerDe\Service\NotificationService', 'notifyNodePublished');
	}
}
