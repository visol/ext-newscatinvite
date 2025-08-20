<?php

namespace Visol\Newscatinvite\Persistence;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Persistence\EntityRemovedFromPersistenceEvent;
use TYPO3\CMS\Extbase\Event\Persistence\EntityUpdatedInPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory;
use Visol\Newscatinvite\Domain\Model\Invitation;

class InvitationDeleteTranslationSynchronizer
{
    protected ConnectionPool $connectionPool;
    protected DataMapFactory $dataMapFactory;

    public function __construct(
        ConnectionPool $connectionPool,
        DataMapFactory $dataMapFactory
    ) {
        $this->connectionPool = $connectionPool;
        $this->dataMapFactory = $dataMapFactory;
    }

    public function __invoke(EntityRemovedFromPersistenceEvent $event): void
    {
        $object = $event->getObject();
        if (! $object instanceof Invitation) {
            return;
        }

        $tableName = $this->dataMapFactory->buildDataMap(Invitation::class)->getTableName();

        $this->deleteTranslations($tableName, $object->getUid());
    }

    protected function deleteTranslations(string $tableName, int $uid): void
    {
        $translationOriginalPointerField = $GLOBALS['TCA'][$tableName]['ctrl']['transOrigPointerField'] ?? null;
        if (!isset($translationOriginalPointerField)) {
            return;
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder
            ->delete($tableName)
            ->where(
                $queryBuilder->expr()->eq(
                    $translationOriginalPointerField,
                    $queryBuilder->createNamedParameter($uid, \TYPO3\CMS\Core\Database\Connection::PARAM_INT)
                )
            )
            ->executeStatement();
    }
}
