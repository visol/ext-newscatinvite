<?php

namespace Visol\Newscatinvite\Update;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class TranslationUpdateWizard implements UpgradeWizardInterface
{
    protected ConnectionPool $connectionPool;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    public function getIdentifier(): string
    {
        return 'newscatinvite_insert_translations';
    }

    public function getTitle(): string
    {
        return 'Invitations Translations Update Wizard';
    }

    public function getDescription(): string
    {
        return 'Insert translation records for translations';
    }

    public function executeUpdate(): bool
    {
        $tableName = 'tx_newscatinvite_domain_model_invitation';
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder
            ->select(...[
                'invitation.uid AS l10n_parent',
                'invitation.pid AS pid',
                'invitation.status AS status',
                'invitation.sent AS sent',
                'invitation.category AS category',
                'invitation.approving_beuser AS approving_beuser',
                'news_translation.uid AS news',
                'news_translation.sys_language_uid AS sys_language_uid',
            ])
            ->from($tableName, 'invitation')
            ->join(
                'invitation',
                'tx_news_domain_model_news',
                'news',
                'invitation.news = news.uid'
            )
            ->join(
            'news',
            'tx_news_domain_model_news',
            'news_translation',
            'news_translation.l10n_parent = news.uid'
            );

        $inserts = $queryBuilder
            ->executeQuery()
            ->fetchAllAssociative();

        if (empty($inserts)) {
            return true;
        }

        $connection = $this->connectionPool->getConnectionForTable($tableName);
        $connection->bulkInsert($tableName, $inserts, array_keys($inserts[0]));
        return true;
    }

    public function updateNecessary(): bool
    {
        return true;
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
