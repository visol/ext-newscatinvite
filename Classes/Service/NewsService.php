<?php

namespace Visol\Newscatinvite\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * InvitationController
 */
class NewsService implements SingletonInterface
{
    protected string $newsTable = 'tx_news_domain_model_news';
    protected string $categoryTable = 'sys_category';
    protected string $categoryMmTable = 'sys_category_record_mm';

    /**
     * Get a raw news record using TYPO3 DB API and add its related categories
     */
    public function getRawNewsRecordWithCategories(int $newsUid): array
    {
        $newsRecord = $this->findRawRecordByUid($newsUid);
        $q = $this->getQueryBuilder();
        $categories = $q->select('sys_category.*')
            ->from($this->categoryTable)
            ->innerJoin(
                $this->categoryTable,
                $this->categoryMmTable,
                $this->categoryMmTable,
                "$this->categoryTable.uid = $this->categoryMmTable.uid_local"
            )
            ->innerJoin(
                $this->categoryMmTable,
                $this->newsTable,
                $this->newsTable,
                "$this->categoryMmTable.uid_foreign = $this->newsTable.uid"
            )
            ->where(
                $q->expr()->eq("$this->categoryMmTable.uid_foreign", $newsRecord['uid'])
            )
            ->execute()
            ->fetchAllAssociative();
        $newsRecord['categories'] = $categories;
        return $newsRecord;
    }

    /**
     * Finds a record through the TYPO3 DB API, ignoring the frontend language.
     * This is used in the CommandController where mails for certain news records are generated.
     * Since there is no frontend context, no overlay can happen. Therefore, using the TYPO3 DB API
     * is the safest way to ensure getting the correct record.
     *
     * @param $newsUid
     *
     * @return array|FALSE|NULL
     */
    public function findRawRecordByUid($newsUid)
    {
        $q = $this->getQueryBuilder();
        return $q
            ->select('*')
            ->from($this->newsTable)
            ->where($q->expr()->eq('uid', (int)$newsUid))
            ->execute()
            ->fetchAssociative();
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        return $connectionPool->getQueryBuilderForTable($this->newsTable);
    }
}
