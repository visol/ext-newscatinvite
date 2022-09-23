<?php

namespace Visol\Newscatinvite\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use GeorgRinger\News\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BackendUserGroupRepository extends Repository
{
    public function initializeObject()
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param Category $category
     *
     * @return array|QueryResultInterface
     */
    public function findByCategoryPermissions(Category $category)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categoryPerms', $category)
        );

        return $query->execute();
    }
}
