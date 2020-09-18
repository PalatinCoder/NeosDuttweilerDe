<?php
namespace GSL\DuttweilerDe\NodeCreationHandler;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\Neos\Ui\NodeCreationHandler\NodeCreationHandlerInterface;

class PodcastEpisodeHandler implements NodeCreationHandlerInterface {
  
  /**
   * Set the properties for a podcast episode
   *
   * @param NodeInterface $node The newly created node
   * @param array $data incoming data from the creationDialog
   * @return void
   */
  public function handle(NodeInterface $node, array $data)
  {
     if ($node->getNodeType()->isOfType('GSL.DuttweilerDe:Document.Podcast.Episode')) {
         if (isset($data['title'])) {
             $node->setProperty('title', $data['title']);
         }
         if (isset($data['date'])) {
           $node->setProperty('date', $data['date']);
         }
         if (isset($data['audio'])) {
           $audio = $node->findNamedChildNode(NodeName::fromString('main'))->findNamedChildNode(NodeName::fromString('audio'));
           $audio->setProperty('assets', array($data['audio']));
         }
     }
  }
}
