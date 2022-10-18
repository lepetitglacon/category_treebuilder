<?php
declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Manager;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class QueryManager
{

    const TABLE = 'sys_category';

    /**
     * @var QueryBuilder
     */
    private QueryBuilder $queryBuilder;

    /**
     * @var Connection $connection
     */
    private Connection $connection;

    public function __construct()
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $this->queryBuilder->getRestrictions()->removeAll();
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE);
    }

    public function insertCategories($categories): int
    {
        return $this->connection->bulkInsert(
            self::TABLE,
            $categories,
            ['uid', 'pid', 'parent', 'title']
        );
    }

    public function truncate($table = self::TABLE) {
        $this->connection->truncate($table);
    }

    public function getCategoriesFromDatabase() {
        return $this->queryBuilder
            ->select('uid', 'title', 'parent')
            ->from(self::TABLE)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getCategoriesForExport() {
        return $this->queryBuilder
            ->select('uid', 'pid','parent', 'title')
            ->from(self::TABLE)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}