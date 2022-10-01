<?php

declare(strict_types=1);

namespace Petitglacon\CategorytreeBuilder\Controller;

 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Core\Database\ConnectionPool;
 use TYPO3\CMS\Core\Database\Connection;

/**
 * This file is part of the "CategoryTree Builder" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 
 */

/**
 * CategoryController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {

        $tree = [];
        $tree[0] = [
            "uid" => 0,
            "title" => "CategoryRoot",
            "parent" => null
        ];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $result = $queryBuilder
            ->select('uid', 'title', 'parent')
            ->from('sys_category')
            ->executeQuery();
        $arr = $result->fetchAllAssociative();

        $new = array();
        foreach ($arr as $a){
            $new[$a['parent']][] = $a;
        }
        $tree = $this->createTree($new, array($arr[0]));

        $this->view->assign('tree', $tree);


        return $this->htmlResponse();
    }

    public function createTree(&$list, $parent): array
    {
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['uid']])){
                $l['children'] = $this->createTree($list, $list[$l['uid']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }
}
