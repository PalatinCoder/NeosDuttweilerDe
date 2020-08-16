<?php
namespace GSL\DuttweilerDe\Eel\FlowQueryOperations;

use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations\AbstractOperation;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;

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
			throw new \Neos\Eel\FlowQuery\FlowQueryException('FilterDateInFuture() needs at least one date property name by which nodes should be filtered', 1332492263);
		} else {
			$nodes = $flowQuery->getContext();
			$now = new \Neos\Flow\Utility\Now;
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

