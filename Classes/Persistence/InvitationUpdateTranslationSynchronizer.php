<?php

namespace Visol\Newscatinvite\Persistence;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Persistence\EntityUpdatedInPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory;
use Visol\Newscatinvite\Domain\Model\Invitation;

class InvitationUpdateTranslationSynchronizer
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

    public function __invoke(EntityUpdatedInPersistenceEvent $event): void
    {
        $object = $event->getObject();
        if (! $object instanceof Invitation) {
            return;
        }

        $tableName = $this->dataMapFactory->buildDataMap(Invitation::class)->getTableName();
        $uid = $object->getUid();

        $this->triggerLocalizationSynchronization($tableName, $uid);
    }

    protected function triggerLocalizationSynchronization(string $table, ?int $uid): void
    {
        $synchronizedFields = $this->getSynchronizedFields($table);
        $dataMap = [
            $table => [
                $uid => $this->getCurrentValuesFromDb($table, $uid, $synchronizedFields),
            ],
        ];
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($dataMap, []);
        $dataHandler->process_datamap();
    }

    /**
     * Get a list of fields that will be synchronized between translations
     * by the DataHandler.
     */
    protected function getSynchronizedFields(string $table): array
    {
        return array_keys(array_filter(
            $GLOBALS['TCA'][$table]['columns'],
            fn(array $columnTca ,string $column): bool =>
                in_array($columnTca['l10n_mode'] ?? '', ['exclude', 'mergeIfNotBlank'])
                || (bool)($columnTca['config']['behaviour']['allowLanguageSynchronization'] ?? null),
            ARRAY_FILTER_USE_BOTH,
        ));
    }

    protected function getCurrentValuesFromDb(string $tableName, int $uid, array $fieldNames): array
    {
        $fieldNames = array_merge(['uid'], $fieldNames);
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder
            ->select(...$fieldNames)
            ->from($tableName)->where($queryBuilder->expr()->eq(
            'uid',
            $queryBuilder->createNamedParameter($uid, \TYPO3\CMS\Core\Database\Connection::PARAM_INT)
        ))->executeQuery();

        $result = $statement->fetchAssociative();
        if ($result === false) {
            throw new \RuntimeException('Unable to fetch record', 1670004992265);
        }
        return $result;
    }
}
