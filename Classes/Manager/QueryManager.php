<?php
declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Manager;

use Petitglacon\CategoryTreebuilder\Object\Category;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
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

    private function getQueryBuilder() {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $queryBuilder->getRestrictions()->removeAll();
        return $queryBuilder;
    }

    /**
     * @param Category[] $categories
     * @return array
     */
    public function insertOrUpdateCategories(Category|array $categories): array
    {
        $notToDeleteUids = [];
        $return = [
            'insert' => 0,
            'update' => 0,
            'delete' => 0
        ];

        $updatedCategories = array_filter($categories, function ($category) {
            return $category->isUpdated();
        });
        $toUpdate = [];
        foreach ($updatedCategories as $cat) {
            $toUpdate[] = $cat->toArray();
            $notToDeleteUids[] = $cat->getUid();
        }

        $createdCategories = array_filter($categories, function ($category) {
            return !$category->isUpdated();
        });
        $toInsert = [];
        foreach ($createdCategories as $cat) {
            $toInsert[] = $cat->toArray();
            $notToDeleteUids[] = $cat->getUid();
        }

        if (!empty($toUpdate)) {
            $return['update'] = $this->bulkUpdate($toUpdate);
        }
        if (!empty($toInsert)) {
            $return['insert'] = $this->bulkInsert($toInsert);
        }
        if (!empty($notToDeleteUids)) {
            $return['delete'] = $this->softDelete($notToDeleteUids);
        }

        return $return;
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

    public function getCategoriesForFrontend() {
        $qb = $this->getQueryBuilder();
        $qb->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        return $qb
            ->select(self::TABLE.'.uid', self::TABLE.'.title', self::TABLE.'.pid', self::TABLE.'.parent', 'p.title as folder', 'p.uid as folderUid')
            ->from(self::TABLE)
            ->leftJoin(
                self::TABLE,
                'pages',
                'p',
                $qb->expr()->eq('p.uid', $qb->quoteIdentifier(self::TABLE.'.pid'))
            )
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

    public function insertCategory(Category $category) {
        $qb = $this->getQueryBuilder();
        $rowCount = $qb
            ->insert(self::TABLE)
            ->values($category->toArray())
            ->executeStatement();
        $uid = $qb->getConnection()->lastInsertId();
        return [
            'rows' => $rowCount,
            'uid' => $uid
        ];
    }

    public function getLastInsertedUidAfterInsert() {
        return $this->connection->lastInsertId('sys_category');
    }

    public function getLastInsertedUid() {
        return (int)$this->queryBuilder
            ->select('uid')
            ->from(self::TABLE)
            ->orderBy('uid', 'desc')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAllAssociative()[0]['uid'];
    }

    public function bulkInsert($categories) {
        return $this->connection->bulkInsert(
            self::TABLE,
            $categories,
            ['uid', 'pid', 'parent', 'title']
        );
    }

    public function update($category) {
        $qb = $this->getQueryBuilder();
        return (int)$qb
            ->update(self::TABLE)
            ->where(
                $qb->expr()->eq('uid', $qb->createNamedParameter($category['uid'], \PDO::PARAM_INT))
            )
            ->set('pid', $category['pid'])
            ->set('parent', $category['parent'])
            ->set('title', $category['title'])
            ->executeStatement();
    }

    public function updateParent($uid, $parent): int
    {
        $qb = $this->getQueryBuilder();
        return (int)$qb
            ->update(self::TABLE)
            ->where(
                $qb->expr()->eq('uid', $qb->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->set('parent', $parent)
            ->executeStatement();
    }

    public function bulkUpdate($arrayCategories) {
        $return = 0;
        foreach ($arrayCategories as $cat) {
            $return += $this->update($cat);
        }
        return $return;
    }

    private function softDelete($categoryUids) {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        return $queryBuilder
        ->update(self::TABLE)
            ->where(
                $queryBuilder->expr()->notIn('uid', $categoryUids)
            )
            ->set('deleted', 1)
            ->executeStatement();
    }


    /**
     * @param $uid
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCategory($uid) {
        $qb = $this->getQueryBuilder();
        $result = $qb
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $qb->expr()->eq('pid', $qb->createNamedParameter($uid, Connection::PARAM_INT))
            )
            ->executeQuery();
        return [
            $result->fetchAllAssociative()
        ];
    }
}