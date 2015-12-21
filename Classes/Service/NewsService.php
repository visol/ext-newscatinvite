<?php
namespace Visol\Newscatinvite\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\SingletonInterface;


/**
 * InvitationController
 */
class NewsService implements SingletonInterface {

    protected $newsTable = 'tx_news_domain_model_news';
    protected $categoryTable = 'sys_category';
    protected $categoryMmTable = 'sys_category_record_mm';

    /**
     * Get a raw news record using TYPO3 DB API and add its related categories
     *
     * @param $newsUid
     * @return array|FALSE|NULL
     */
    public function getRawNewsRecordWithCategories($newsUid) {
        $newsRecord = $this->findRawRecordByUid($newsUid);
        $this->getDatabaseConnection()->store_lastBuiltQuery = TRUE;
        $categoryQuery = $this->getDatabaseConnection()->exec_SELECT_mm_query('sys_category.*', $this->categoryTable, $this->categoryMmTable, $this->newsTable, 'AND uid_foreign=' . $newsRecord['uid']);
        $categories = array();
        while ($category = $this->getDatabaseConnection()->sql_fetch_assoc($categoryQuery)) {
            $categories[] = $category;
        }
        $newsRecord['categories'] = $categories;
        return $newsRecord;
    }

    /**
     * Finds a record through the TYPO3 DB API, ignoring the frontend language.
     * This is used in the CommandController where mails for certain news records are generated.
     * Since there is no frontend context, no overlay can happen. Therefore using the TYPO3 DB API
     * is the safest way to ensure getting the correct record.
     *
     * @param $newsUid
     * @return array|FALSE|NULL
     */
    public function findRawRecordByUid($newsUid) {
        return $this->getDatabaseConnection()->exec_SELECTgetSingleRow('*', $this->newsTable, 'uid=' . (int)$newsUid . ' AND NOT deleted');
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    public function getDatabaseConnection() {
        return $GLOBALS['TYPO3_DB'];
    }
}