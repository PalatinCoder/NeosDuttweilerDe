<?php
namespace GSL\DuttweilerDe\Eel\FlowQueryOperations;

use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations\AbstractOperation;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;

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

