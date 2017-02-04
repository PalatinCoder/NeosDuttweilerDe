<?php
namespace GSL\DuttweilerDe\TypoScript\Eel\FlowQueryOperations;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "GSL.DuttweilerDe".      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations\AbstractOperation;
use Neos\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * FlowQuery operation to order nodes by a date property
 */
class OrderByDate extends AbstractOperation {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	static protected $shortName = 'orderByDate';

	/**
	 * {@inheritdoc}
	 *
	 * @var integer
	 */
	static protected $priority = 200;

	/**
	 * {@inheritdoc}
	 *
	 * We can only handle TYPO3CR Nodes.
	 *
	 * @param mixed $context
	 * @return boolean
	 */
	public function canEvaluate($context) {
		return (isset($context[0]) && ($context[0] instanceof NodeInterface));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param FlowQuery $flowQuery the FlowQuery object
	 * @param array $arguments The names of properties to filter by.
	 * @return mixed
	 */
	public function evaluate(FlowQuery $flowQuery, array $arguments) {
		/*if (!isset($arguments[0]) || empty($arguments[0])) {
			throw new \Neos\Eel\FlowQuery\FlowQueryException('OrderByDate needs the name of the date property', 1453581087);
		} elseif (isset($arguments[1])) {
			throw new \Neos\Eel\FlowQuery\FlowQueryException('OrderByDate has only one argument', 1453581122);
		} else {*/
			$nodes = $flowQuery->getContext();
			/** 
			 * @var Node $nodea
			 * @var Node $nodeb
			 */
			usort($nodes, function($nodea, $nodeb) {
				return ($nodea->getProperty('date') > $nodeb->getProperty('date')) ? -1 : 1;
			});
			$flowQuery->setContext($nodes);
		//}
	}
}

