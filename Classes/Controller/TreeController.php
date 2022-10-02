<?php

declare(strict_types=1);

namespace Petitglacon\CategorytreeBuilder\Controller;


use JetBrains\PhpStorm\ArrayShape;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * TreeController
 */
class TreeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var int
     */
    private int $depthCounter = 0;

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
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $queryBuilder->getRestrictions()->removeAll();
        $result = $queryBuilder
            ->select('uid', 'title', 'parent')
            ->from('sys_category')
            ->executeQuery();
        $arr = $result->fetchAllAssociative();

        if (empty($arr)) {
            $this->view->assign("info", "Aucune catégorie");
            return $this->htmlResponse();
        }

        $new = [];
        foreach ($arr as $a){
            $this->depthCounter = 0;
            $a['depth'] = $this->findDepth($a, $arr);
            $new[$a['parent']][] = $a;
        }
        $tree = $this->createTree($new, $new[0]);

        $this->view->assignMultiple([
            "tree" => $tree,
            "jsonTree" => json_encode($tree)
        ]);
        return $this->htmlResponse();
    }

    private function findDepth($category, $array): int
    {
        $depth = 0;
        $cat = $category;
        while (!empty($cat['parent'])) {
            $depth++;
            $cat = $array[$cat['parent']-1];
        }
        return $depth;
    }

    private function createTree(&$list, $parents): array
    {
        $tree = [];
        foreach ($parents as $key => $category){
//            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($category, "category");
//            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(isset($list[$category['uid']]), "in list");
            if(isset($list[$category['uid']])){
                $category['children'] = $this->createTree($list, $list[$category['uid']]);
            }
            $tree[] = $category;
        }
        return $tree;
    }

    private function addDepth() {
        $this->depthCounter++;
    }

    private function addUidCount() {
        $this->uidCounter++;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function buildAction()
    {
        $returnArray = [];
        $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
        $rootPid = StringUtility::getUniqueId("NEW");
        $args = $_POST;

        if (isset($args["textCategoryTree"])) {

            $categoryTreeFromForm = str_replace("\"", "", $args["textCategoryTree"]);
            $data = [];
            $categoriesPid = 3;

            foreach(explode("\\n", $categoryTreeFromForm) as $lineCount => $line) {

                if (empty($line)) {
                    $returnArray["errors"][] = "Line $lineCount is empty";
                    break;
                }

                $tabsCount = substr_count($line, "\\t");

                // if category parent is root
                if (substr_count($line, "\\t") == 0) {
                    $category = $this->createCategory($rootPid, StringUtility::getUniqueId("NEW"), $line);
                }

                // else count tabs (\t)
                if ($tabsCount > 0) {
                    // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(["tabcount" => $tabsCount, "lasttabcount" => $lastTabCount, "parent" => $parent, "line" => $line]);
                    $category = $this->createCategory($this->parents[$tabsCount-1]["uid"], StringUtility::getUniqueId("NEW"), $line);
                }

                $this->addParent($category, $tabsCount);
                $this->addCategoryToTree($category);
                $this->addUidCount();
            }

            // truncate table
            GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('sys_category')
                ->truncate('sys_category');

            foreach ($this->tree as $uid => $category) {
                $data['sys_category'][$uid] = [
                    "parent" => $category['pid'],
                    "title" => $category["title"],
                    "pid" => $categoriesPid
                ];
            }

            $dataHandler->start($data, []);
            $dataHandler->process_datamap();

            if ($dataHandler->errorLog !== []) {
                \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('Error(s) while creating categories');
                foreach ($dataHandler->errorLog as $log) {
                    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($log);
                }
            }


        } else {
            $this->addFlashMessage("No categories");
            return $this->htmlResponse();
        }

        $this->view->assignMultiple([
            "title" => "titre"
        ]);

        $this->redirect("index");
    }

    #[ArrayShape(["pid" => "", "uid" => "", "title" => ""])]
    private function createCategory($pid, $uid, $title): array
    {
        return ["pid" => $pid, "uid" => $uid, "title" => str_replace(["\\t", "\""], [""], $title)];
    }

    private function addCategoryToTree($category) {
        $this->tree[$category["uid"]] = $category;
    }

    private function addParent($category, $tabCount) {

        // reset parent array
        if ($tabCount == 0) {
            $this->parents[$tabCount] = $category;
        }

        $this->parents[$tabCount] = $category;
    }
}
