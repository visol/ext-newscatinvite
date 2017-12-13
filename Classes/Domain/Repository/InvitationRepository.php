<?php

namespace Visol\Newscatinvite\Domain\Repository;

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
class InvitationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    public function initializeObject()
    {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(false);
        $querySettings->setIgnoreEnableFields(true);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findByStatusAndCategories($status, array $categories)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('status', $status),
                $query->in('category', $categories),
                $query->greaterThan('category.uid', 0),
                $query->greaterThan('news', 0)
            )
        );


        return $query->execute();
    }

    public function findByCategories(array $categories)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->in('category', $categories),
                $query->greaterThan('category.uid', 0),
                $query->greaterThan('news', 0),
                $query->logicalOr(
                    $query->equals('status', \Visol\Newscatinvite\Domain\Model\Invitation::STATUS_APPROVED),
                    $query->equals('status', \Visol\Newscatinvite\Domain\Model\Invitation::STATUS_REJECTED)
                )
            )
        );

        return $query->execute();
    }

    public function findPendingUnsentInvitations()
    {
        $query = $this->createQuery();
        // we wait two minutes before sending an invitation because it might be "self-approved" by then
        $nowBeforeTwoMinutes = time() - 120;
        $query->matching(
            $query->logicalAnd(
                $query->equals('status', 0),
                $query->equals('sent', 0),
                $query->lessThan('tstamp', $nowBeforeTwoMinutes)
            )
        );

        return $query->execute();
    }
}
