<?php

namespace Visol\Newscatinvite\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BackendUserRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findByUsergroups(array $usergroups): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('be_users')->createQueryBuilder();

        $constraints = [];
        foreach ($usergroups as $usergroup) {
            $constraints[] = $queryBuilder->expr()->inSet('bu.usergroup', $queryBuilder->createNamedParameter((int)$usergroup, Connection::PARAM_INT));
        }

        return $queryBuilder->select('*')
            ->from('be_users', 'bu')
            ->where($queryBuilder->expr()->and(...$constraints))->orderBy('username')->executeQuery()
            ->fetchAllAssociative();
    }
}
