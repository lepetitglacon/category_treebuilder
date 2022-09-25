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
