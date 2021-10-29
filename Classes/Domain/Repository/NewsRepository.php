<?php

namespace Visol\Newscatinvite\Domain\Repository;

use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Service\CategoryService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Visol\Newscatinvite\Domain\Model\Invitation;

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
class NewsRepository extends \GeorgRinger\News\Domain\Repository\NewsRepository
{

    /**
     * Returns a category constraint created by
     * a given list of categories and a junction string
     *
     * @param QueryInterface $query
     * @param  string $categories
     * @param  string $conjunction
     * @param  boolean $includeSubCategories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
     */
    protected function createCategoryConstraint(
        QueryInterface $query,
        $categories,
        $conjunction,
        $includeSubCategories = false
    ) {
        $constraint = null;
        $categoryConstraints = [];
        $invitationConstraints = [];

        // If "ignore category selection" is used, nothing needs to be done
        if (empty($conjunction)) {
            return $constraint;
        }

        if (!is_array($categories)) {
            $categories = GeneralUtility::intExplode(',', $categories, true);
        }
        foreach ($categories as $category) {
            if ($includeSubCategories) {
                $subCategories = GeneralUtility::trimExplode(
                    ',',
                    CategoryService::getChildrenCategories($category, 0, '', true),
                    true
                );
                $subCategoryConstraint = [];
                $subCategoryConstraint[] = $query->contains('categories', $category);
                if (count($subCategories) > 0) {
                    foreach ($subCategories as $subCategory) {
                        $subCategoryConstraint[] = $query->contains('categories', $subCategory);
                    }
                }
                if ($subCategoryConstraint) {
                    $categoryConstraints[] = $query->logicalOr($subCategoryConstraint);
                }
            } else {
                $categoryConstraints[] = $query->contains('categories', $category);
                $invitationConstraints[] = $query->logicalAnd(
                    $query->equals('invitations.category', $category),
                    $query->equals('invitations.status', Invitation::STATUS_APPROVED)
                );
            }
        }

        if ($categoryConstraints) {
            switch (strtolower($conjunction)) {
                case 'or':
                    $categoryConstraint = $query->logicalOr($categoryConstraints);
                    break;
                case 'notor':
                    $categoryConstraint = $query->logicalNot($query->logicalOr($categoryConstraints));
                    break;
                case 'notand':
                    $categoryConstraint = $query->logicalNot($query->logicalAnd($categoryConstraints));
                    break;
                case 'and':
                default:
                    $categoryConstraint = $query->logicalAnd($categoryConstraints);
            }
        }

        if ($invitationConstraints) {
            $invitationConstraint = $query->logicalOr($invitationConstraints);
        }

        $constraint = $query->logicalOr(
            $categoryConstraint,
            $invitationConstraint
        );

        return $constraint;
    }

/**
 * Get the count of news records by month/year and
 * returns the result compiled as array
 *
 * @param DemandInterface $demand
 *
 * @return array
 */
public function countByDate(DemandInterface $demand)
{
    $data = [];
    $sql = $this->findDemandedRaw($demand);

    // Get the month/year into the result
    $field = $demand->getDateField();
    $field = empty($field) ? 'datetime' : $field;

    $where = substr($sql, strpos($sql, 'WHERE '));

    $join = '';

    $categoryUidPattern = '/`tx_newscatinvite_domain_model_invitation`\.`category` = (\d+)/';
    preg_match_all($categoryUidPattern, $where, $matches);
    if ($matches) {
        $jointWhere = [];
        foreach($matches[1] as $categoryUid) {
            $jointWhere[] = 'tx_newscatinvite_domain_model_invitation.category = ' . $categoryUid . ' AND tx_newscatinvite_domain_model_invitation.status = 1';
        }
        $jointWhereString = implode(' OR ', $jointWhere);
        $join = ' LEFT JOIN (SELECT * FROM tx_newscatinvite_domain_model_invitation WHERE ' . $jointWhereString . ' GROUP BY tx_newscatinvite_domain_model_invitation.news) tx_newscatinvite_domain_model_invitation ON tx_news_domain_model_news.uid=tx_newscatinvite_domain_model_invitation.news ';
    }

    $sql = 'SELECT FROM_UNIXTIME(' . $field . ', "%m") AS "_Month",' . ' FROM_UNIXTIME(' . $field . ', "%Y") AS "_Year" ,' . ' count(FROM_UNIXTIME(' . $field . ', "%m")) as count_month,' . ' count(FROM_UNIXTIME(' . $field . ', "%y")) as count_year' . ' FROM tx_news_domain_model_news ' . $join . $where;

    if (TYPO3_MODE === 'FE') {
        $sql .= $GLOBALS['TSFE']->sys_page->enableFields('tx_news_domain_model_news');
    } else {
        $sql .= BackendUtility::BEenableFields('tx_news_domain_model_news') . BackendUtility::deleteClause(
                'tx_news_domain_model_news'
            );
    }
    // strip unwanted order by
    $sql = $GLOBALS['TYPO3_DB']->stripOrderBy($sql);

    // group by custom month/year fields
    $orderDirection = strtolower($demand->getOrder());
    if ($orderDirection !== 'desc' && $orderDirection != 'asc') {
        $orderDirection = 'asc';
    }
    $sql .= ' GROUP BY _Month, _Year ORDER BY _Year ' . $orderDirection . ', _Month ' . $orderDirection;
    $res = $GLOBALS['TYPO3_DB']->sql_query($sql);
    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
        $data['single'][$row['_Year']][$row['_Month']] = $row['count_month'];
    }
    $GLOBALS['TYPO3_DB']->sql_free_result($res);

    // Add totals
    if (is_array($data['single'])) {
        foreach ($data['single'] as $year => $months) {
            $countOfYear = 0;
            foreach ($months as $month) {
                $countOfYear += $month;
            }
            $data['total'][$year] = $countOfYear;
        }
    }

    return $data;
    }
}
