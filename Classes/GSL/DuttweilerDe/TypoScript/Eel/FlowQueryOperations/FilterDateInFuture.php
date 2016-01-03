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

use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Eel\FlowQuery\Operations\AbstractOperation;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * FlowQuery operation to filter by date property to select only the ones whose date is in future
 */
class FilterDateInFuture extends AbstractOperation {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	static protected $shortName = 'filterDateInFuture';

	/**
	 * {@inheritdoc}
	 *
	 * @var integer
	 */
	static protected $priority = 100;

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
		if (!isset($arguments[0]) || empty($arguments[0])) {
			throw new \TYPO3\Eel\FlowQuery\FlowQueryException('FilterDateInFuture() needs at least one date property name by which nodes should be filtered', 1332492263);
		} else {
			$nodes = $flowQuery->getContext();
			$now = new \TYPO3\Flow\Utility\Now;
			$filteredNodes = array();

			/** @var Node $node */
			foreach ($nodes as $node) {
				/** @var mixed $property */
				foreach ($arguments as $property) {
					$date = $node->getProperty($property);
					if ($date > $now) {
						$filteredNodes[] = $node;
						break;
					}
				}
			}
			$flowQuery->setContext($filteredNodes);
		}
	}
}

