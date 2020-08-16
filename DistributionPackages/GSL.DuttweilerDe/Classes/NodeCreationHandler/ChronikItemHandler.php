<?php
namespace GSL\DuttweilerDe\NodeCreationHandler;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Neos\Ui\NodeCreationHandler\NodeCreationHandlerInterface;

class ChronikItemHandler implements NodeCreationHandlerInterface {
  
  /**
   * Set subheadline and date for the new chronik item
   *
   * @param NodeInterface $node The newly created node
   * @param array $data incoming data from the creationDialog
   * @return void
   */
  public function handle(NodeInterface $node, array $data)
  {
     if ($node->getNodeType()->isOfType('GSL.DuttweilerDe.Pages:ChronikItem')) {
         if (isset($data['title'])) {
             $node->setProperty('title', $data['title']);
         }
         if (isset($data['subheadline'])) {
           $node->setProperty('subheadline', $data['subheadline']);
         }
         if (isset($data['date'])) {
           $node->setProperty('date', \DateTime::createFromFormat('d.m.Y', $data['date']));
         }
     }
  }
}
