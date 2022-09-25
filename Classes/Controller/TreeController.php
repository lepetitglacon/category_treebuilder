<?php

declare(strict_types=1);

namespace Petitglacon\CategorytreeBuilder\Controller;


use JetBrains\PhpStorm\ArrayShape;

/**
 * This file is part of the "CategoryTree Builder" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 
 */

/**
 * TreeController
 */
class TreeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var int
     */
    private int $uidCounter = 0;

    /**
     * @var array
     */
    private array $tree = [];

    /**
     * @var array
     */
    private array $parents = [];

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function buildAction(): \Psr\Http\Message\ResponseInterface
    {

        // $args = $this->request->getArguments();
        $args = $_POST;

        if (isset($args["textCategoryTree"])) {
            $textCategoryTree = $args["textCategoryTree"];
                $parents = [];
                $lastTabCount = 0;
                foreach(explode("\\n", $textCategoryTree) as $line) {

                    // if root parent
                    if (substr_count($line, "\\t") == 0) {
                        $category = $this->createCategory(0, $this->uidCounter, $line);
                        $this->addParent($category, 0);
                        $this->addCategoryToTree($parents);
                        $this->addUidCount();
                        $lastTabCount = 0;
                        continue;
                    }

                    // count \t
                    if ($tabsCount = substr_count($line, "\\t")) {
                        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(["tabcount" => $tabsCount, "lasttabcount" => $lastTabCount, "parent" => $parent, "line" => $line]);

                        $category = $this->createCategory($parents[$tabsCount]["uid"], $this->uidCounter, $line);
                        $this->addCategoryToTree($category);
                        $this->addParent($category, $tabsCount);
                        $this->addUidCount();
                        $lastTabCount = $tabsCount;
                    }
                }

                \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->tree);

            exit;
        } else {
            $this->addFlashMessage("No categories");
            return $this->htmlResponse();
        }

        return $this->htmlResponse();
    }

    #[ArrayShape(["pid" => "", "uid" => "", "title" => ""])]
    private function createCategory($pid, $uid, $title): array
    {
        return ["pid" => $pid, "uid" => $uid, "title" => $title];
    }

    private function addUidCount() {
        $this->uidCounter++;
    }

    private function addCategoryToTree($category) {
        $this->tree[$this->uidCounter] = $category;
    }

    private function addParent($category, $tabCount) {
        if ($tabCount == 0) {
            $this->parents = [];
        }
        if (count($this->parents) < 1) {
            $this->parents[0] = [];
        }
        $this->parents[$tabCount] = $category;
    }
}
