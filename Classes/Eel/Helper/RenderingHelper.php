<?php
namespace GSL\DuttweilerDe\Eel\Helper;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Neos\Domain\Service\UserInterfaceModeService;

/**
 * (basically the same as the neos:rendering.inEditMode ViewHelper)
 */
class RenderingHelper implements ProtectedContextAwareInterface {
  
  /**
   * @Flow\Inject
   * @var UserInterfaceModeService
   */
  protected $userInterfaceModeService;
  
  /**
   * Check if the node is rendered in some edit mode
   *
   * @param NodeInterface $contextNode
   * @return boolean
   */
 public function inEditMode() {
    $mode = $this->userInterfaceModeService->findModeByCurrentUser();
    return $mode->isEdit();
  }
  
  /**
   * Nothing scary going on here, everything safe
   *
   * @param string $methodName
   * @return boolean
   */
  public function allowsCallOfMethod($methodName) {
    return true;
  }
}