<?php
namespace GSL\DuttweilerDe\TypoScript;

/*
 * This file is part of the GSL.DuttweilerDe Site package.
 *
 * (c) Jan Syring-Lingenfelder - www.jan-sl.de
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\Exception as TypoScriptException;

/**
 * A TypoScript Menu object, which adds headers to an alphabetically sorted list
 */
class AbisZMenuImplementation extends \TYPO3\Neos\TypoScript\MenuImplementation
{
    /**
     * @param array $menuLevelCollection
     * @return array
     */
    protected function buildMenuLevelRecursive(array $menuLevelCollection)
    {
        $items = array();
        foreach ($menuLevelCollection as $key => $currentNode) {
            $currentLetter = substr($currentNode->getLabel(), 0, 1);
            $currentLetter = str_replace("Ä", "A", $currentLetter);
            $currentLetter = str_replace("Ö", "O", $currentLetter);
            $currentLetter = str_replace("Ü", "U", $currentLetter);
            $previousLetter = $key > 0 ? substr($menuLevelCollection[$key-1]->getLabel(), 0, 1) : 'Z';
            $previousLetter = str_replace("Ä", "A", $previousLetter);
            $previousLetter = str_replace("Ö", "O", $previousLetter);
            $previousLetter = str_replace("Ü", "U", $previousLetter);
            if ($currentLetter != $previousLetter) {
                $items[] = array(
                    'label' => $currentLetter,
                    'isHeading' => true
                );
            }

            $item = $this->buildMenuItemRecursive($currentNode);
            if ($item === null) {
                continue;
            }

            $items[] = $item;
        }

        return $items;
    }
}
