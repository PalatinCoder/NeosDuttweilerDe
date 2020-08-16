<?php
namespace GSL\DuttweilerDe\Controller\Module;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Controller\Module\AbstractModuleController;

/**
 * @Flow\Scope("singleton")
 */
class FacebookSharingController extends AbstractModuleController {
  
  /**
   * @Flow\Inject
   * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
   */
  protected $contextFactory;
  
  /**
   * @return void
   */
  protected function initializeAction() {
    parent::initializeAction();
  }

  /**
   * @return void
   */
  protected function initializeView(\Neos\Flow\Mvc\View\ViewInterface $view) {
    parent::initializeView($view);
    $view->setOption('layoutPathAndFilename', '../Packages/Application/Neos.Neos/Resources/Private/Layouts/BackendModule.html');
    $view->setOption('partialRootPaths', array('../Packages/Application/Neos.Neos/Resources/Private/Partials'));
  }

  /**
   * Show a form to enter a message
   * 
   * @return void
   */
  public function indexAction() {
    
    /** @var \Neos\ContentRepository\Domain\Service\Context */
    $context = $this->contextFactory->create();
    
    /** @var \Neos\ContentRepository\Domain\Model\NodeInterface */
    /* Identifier of /a-bis-z/c/chronik */
    $chronikRootNode = $context->getNodeByIdentifier('529a59e2-1849-2782-471c-7635f47167de');
    $chronikBranch = $chronikRootNode->getChildNodes('GSL.DuttweilerDe.Pages:ChronikBranch', 1)[0];

    $this->view->assign('node', $chronikBranch);
  }
}
