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
	 * @param array $arguments the arguments for this operation.
	 * First argument is property to filter by, must be of date type.
	 * Second argument is date to check by, must be of date type.
	 * @return mixed
	 */
	public function evaluate(FlowQuery $flowQuery, array $arguments) {
		if (!isset($arguments[0]) || empty($arguments[0])) {
			throw new \TYPO3\Eel\FlowQuery\FlowQueryException('FilterDateInFuture() needs date property name by which nodes should be filtered', 1332492263);
		} else if (!isset($arguments[1]) || empty($arguments[1])) {
			throw new \TYPO3\Eel\FlowQuery\FlowQueryException('FilterDateInFuture() needs reference date to filter by', 1332493263);
		} else {
			$nodes = $flowQuery->getContext();
			$filterByPropertyPath = $arguments[0];
			$now = $arguments[1];

			$filteredNodes = $this->getFilterDateInFuture($nodes, $now, $filterByPropertyPath);

			$flowQuery->setContext($filteredNodes);
		}
	}

	/**
	 * @param array  $nodes
	 * @param Node   $object
	 * @param string $filterByPropertyPath
	 *
	 * @return array
	 */
	private function getFilterDateInFuture($nodes, $now, $filterByPropertyPath) {
		$filteredNodes = array();
		/** @var Node $node */
		foreach ($nodes as $node) {
			$date = $node->getProperty($filterByPropertyPath);

			$interval = $now->diff($date);

			if ($interval->invert == 0) {
				$filteredNodes[] = $node;
			}
		}

		return $filteredNodes;
	}
}

