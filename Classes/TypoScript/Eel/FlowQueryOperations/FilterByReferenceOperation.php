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
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;

/**
 * FlowQuery operation to filter by properties of type reference or references
 */
class FilterByReferenceOperation extends AbstractOperation {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	static protected $shortName = 'filterByReference';

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
	 * First argument is property to filter by, must be of reference of references type.
	 * Second is object to filter by, must be Node.
	 * @return mixed
	 */
	public function evaluate(FlowQuery $flowQuery, array $arguments) {
		if (!isset($arguments[0]) || empty($arguments[0])) {
			throw new \Neos\Eel\FlowQuery\FlowQueryException('FilterByReference() needs reference property name by which nodes should be filtered', 1332492263);
		} else if (!isset($arguments[1]) || empty($arguments[1])) {
			throw new \Neos\Eel\FlowQuery\FlowQueryException('FilterByReference() needs object by which nodes should be filtered', 1332493263);
		} else {
			$nodes = $flowQuery->getContext();
			$filterByPropertyPath = $arguments[0];
			/** @var Node|array $object */
			$object = $arguments[1];

			if (is_array($object)) {
				$filteredNodes = $this->getFilterNodeByReferences($nodes, $object, $filterByPropertyPath);
			} else {
				$filteredNodes = $this->getFilterNodeByReference($nodes, $object, $filterByPropertyPath);
			}

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
	private function getFilterNodeByReference($nodes, Node $object, $filterByPropertyPath) {
		$filteredNodes = array();
		/** @var Node $node */
		foreach ($nodes as $node) {
			$propertyValue = $node->getProperty($filterByPropertyPath);
			if (is_array($propertyValue)){
				if (in_array($object, $propertyValue)) {
					$filteredNodes[] = $node;
				}
			} else {
				if ($object == $propertyValue) {
					$filteredNodes[] = $node;
				}
			}
		}

		return $filteredNodes;
	}

	/**
	 * @param array   $nodes
	 * @param array   $objects
	 * @param string $filterByPropertyPath
	 *
	 * @return array
	 */
	private function getFilterNodeByReferences($nodes, $objects, $filterByPropertyPath) {
		$filteredNodes = array();
		/** @var Node $node */
		foreach ($nodes as $node) {
			$propertyValue = $node->getProperty($filterByPropertyPath);
			foreach ($objects as $object) {
				if (is_array($propertyValue)){
					if (in_array($object, $propertyValue)) {
						$filteredNodes[] = $node;
						break;
					}
				} else {
					if ($object == $propertyValue) {
						$filteredNodes[] = $node;
						break;
					}
				}
			}
		}

		return $filteredNodes;
	}
}

