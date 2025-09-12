<?php

namespace Visol\Newscatinvite\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Service\CategoryService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Visol\Newscatinvite\Domain\Model\Invitation;

class NewsRepository extends \GeorgRinger\News\Domain\Repository\NewsRepository
{
    /**
     * Returns a category constraint created by
     * a given list of categories and a junction string
     *
     * @param  string $categories
     * @param  string $conjunction
     * @param  boolean $includeSubCategories
     *
     */
    protected function createCategoryConstraint(
        QueryInterface $query,
        $categories,
        $conjunction,
        $includeSubCategories = false
    ): ?ConstraintInterface {
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
                foreach ($subCategories as $subCategory) {
                    $subCategoryConstraint[] = $query->contains('categories', $subCategory);
                }
                if ($subCategoryConstraint !== []) {
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

        if ($categoryConstraints !== []) {
            switch (strtolower($conjunction)) {
                case 'or':
                    $categoryConstraint = $query->logicalOr(...$categoryConstraints);
                    break;
                case 'notor':
                    $categoryConstraint = $query->logicalNot($query->logicalOr(...$categoryConstraints));
                    break;
                case 'notand':
                    $categoryConstraint = $query->logicalNot($query->logicalAnd(...$categoryConstraints));
                    break;
                case 'and':
                default:
                    $categoryConstraint = $query->logicalAnd(...$categoryConstraints);
            }
        }

        if ($invitationConstraints !== []) {
            $invitationConstraint = $query->logicalOr(...$invitationConstraints);
        }

        $constraint = $query->logicalOr($categoryConstraint, $invitationConstraint);

        return $constraint;
    }

    /**
     * Get the count of news records by month/year and
     * returns the result compiled as array
     *
     *
     */
    public function countByDate(DemandInterface $demand): array
    {
        $data = [];
        $sql = $this->findDemandedRaw($demand);

        // strip unwanted order by
        $sql = $this->stripOrderBy($sql);

        // Get the month/year into the result
        $field = $demand->getDateField();
        $field = empty($field) ? 'datetime' : $field;

        $where = substr($sql, strpos($sql, 'WHERE '));

        $join = '';

        $categoryUidPattern = "/`tx_newscatinvite_domain_model_invitation`\.`category`\s*=\s*'(\d+)'/";
        preg_match_all($categoryUidPattern, $where, $matches);
        if ($matches[1]) {
            $jointWhere = [];
            foreach ($matches[1] as $categoryUid) {
                $jointWhere[] = 'tx_newscatinvite_domain_model_invitation.category = ' . $categoryUid . ' AND tx_newscatinvite_domain_model_invitation.status = 1';
            }
            $jointWhereString = implode(' OR ', $jointWhere);
            $join = ' LEFT JOIN (SELECT * FROM tx_newscatinvite_domain_model_invitation WHERE ' . $jointWhereString . ' GROUP BY tx_newscatinvite_domain_model_invitation.news) tx_newscatinvite_domain_model_invitation ON tx_news_domain_model_news.uid=tx_newscatinvite_domain_model_invitation.news ';
        }

        $sql = 'SELECT MONTH(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND ) AS "_Month",' .
            ' YEAR(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND) AS "_Year" ,' .
            ' count(MONTH(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND )) as count_month,' .
            ' count(YEAR(FROM_UNIXTIME(0) + INTERVAL ' . $field . ' SECOND)) as count_year' .
            ' FROM tx_news_domain_model_news ' . $join . $where;

        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable('tx_news_domain_model_news');

        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            $sql .= \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Domain\Repository\PageRepository::class)->enableFields('tx_news_domain_model_news');
        } else {
            $expressionBuilder = $connection
                ->createQueryBuilder()
                ->expr();
            $sql .= BackendUtility::BEenableFields('tx_news_domain_model_news') .
                ' AND ' . $expressionBuilder->eq('deleted', 0);
        }

        // group by custom month/year fields
        $orderDirection = strtolower($demand->getOrder());
        if ($orderDirection !== 'desc' && $orderDirection !== 'asc') {
            $orderDirection = 'asc';
        }
        $sql .= ' GROUP BY _Month, _Year ORDER BY _Year ' . $orderDirection . ', _Month ' . $orderDirection;

        $res = $connection->executeQuery($sql);
        while ($row = $res->fetchAssociative()) {
            $month = strlen($row['_Month']) === 1 ? ('0' . $row['_Month']) : $row['_Month'];
            $data['single'][$row['_Year']][$month] = $row['count_month'];
        }

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

    /**
     * Return stripped order sql
     *
     * BACKPORT of EXT:news can be removed after update
     */
    private function stripOrderBy(string $str): string
    {
        /** @noinspection NotOptimalRegularExpressionsInspection */
        return preg_replace('/(?:ORDER[[:space:]]*BY[[:space:]]*.*)+/i', '', trim($str));
    }
}
