<?php

namespace Visol\Newscatinvite\Domain\Repository;

use GeorgRinger\News\Domain\Model\Category;
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
class BackendUserGroupRepository extends \TYPO3\CMS\Extbase\Domain\Repository\BackendUserGroupRepository
{

    /**
     * @param \GeorgRinger\News\Domain\Model\Category $category
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
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
