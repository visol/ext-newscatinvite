<?php

namespace Visol\Newscatinvite\Command;

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
use Visol\Newscatinvite\Domain\Model\Invitation;
use Visol\Newscatinvite\Domain\Model\BackendUserGroup;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use GeorgRinger\News\Domain\Model\Category;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use Visol\Newscatinvite\Domain\Repository\InvitationRepository;
use Visol\Newscatinvite\Domain\Repository\BackendUserGroupRepository;
use Visol\Newscatinvite\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class InvitationCommandController extends CommandController
{

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * @var BackendUserGroupRepository
     */
    protected $backendUserGroupRepository;

    /**
     * @var BackendUserRepository
     */
    protected $backendUserRepository;

    /**
     * TypoScript setup for module.tx_newscatinvite
     *
     * @var array
     */
    protected $extensionConfiguration;

    /**
     * persistenceManager
     *
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * Send pending invitations
     *
     * @param int $itemsPerRun
     * @param string $overrideRecipient Override the recipient with this address - for testing purposes
     */
    public function sendInvitationsCommand($itemsPerRun = 20, $overrideRecipient = '')
    {
        $this->initializeAction();

        $notSentInvitations = $this->invitationRepository->findPendingUnsentInvitations();
        foreach ($notSentInvitations as $invitation) {
            /** @var Invitation $invitation */
            $recipientArray = [];
            if ($invitation->getCategory() instanceof Category) {
                $userGroupsWithCurrentCategory = $this->backendUserGroupRepository->findByCategoryPermissions($invitation->getCategory());
                if ($userGroupsWithCurrentCategory->count()) {
                    $backendUserGroupsArray = [];
                    foreach ($userGroupsWithCurrentCategory as $usergroup) {
                        /** @var BackendUserGroup $usergroup */
                        $backendUserGroupsArray[] = $usergroup->getUid();
                    }
                    $backendUsers = $this->backendUserRepository->findByUsergroups($backendUserGroupsArray);
                    foreach ($backendUsers as $backendUser) {
                        if (!empty($backendUser['email'])) {
                            $recipientArray[] = $backendUser['email'];
                        }
                    }
                }
            }
            if (!empty($recipientArray)) {
                if (!empty($overrideRecipient)) {
                    // For testing purposes, the recipients can be overridden (defined in CommandController configuration)
                    $recipientArray = [$overrideRecipient];
                }

                $subject = 'Einladung zur Aufnahme einer News-Meldung';

                /** @var StandaloneView $standaloneView */
                $standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
                $standaloneView->setFormat('html');
                $templateRootPath = GeneralUtility::getFileAbsFileName($this->extensionConfiguration['view']['templateRootPath']);
                $templatePathAndFilename = $templateRootPath . 'Email/InvitationNotification.html';
                $standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
                $standaloneView->assignMultiple([
                    'invitation' => $invitation,
                    'settings' => $this->extensionConfiguration['settings']
                ]);
                $content = $standaloneView->render();
                $sender = ['typo3@unilu.ch' => 'TYPO3 Universität Luzern'];
                $replyTo = '';
                if ($invitation->getCreator()->getEmail() !== '') {
                    $replyTo = $invitation->getCreator()->getEmail();
                }
                $emailIsSent = $this->sendEmail($recipientArray, $sender, $subject, $content, $replyTo);
                if ($emailIsSent) {
                    $invitation->setSent(1);
                    $this->invitationRepository->update($invitation);
                }
            }
        }

        $this->persistenceManager->persistAll();
    }

    /**
     * @param array $recipient
     * @param array $sender
     * @param $subject
     * @param $content
     * @param string $replyTo
     * @param string $returnPath
     *
     * @return bool
     */
    protected function sendEmail(array $recipient, array $sender, $subject, $content, $replyTo = '', $returnPath = '')
    {
        /** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setTo($recipient);
        $message->setFrom($sender);
        if (!empty($replyTo) && GeneralUtility::validEmail($replyTo)) {
            $message->addReplyTo($replyTo);
        }
        if (!empty($returnPath) && GeneralUtility::validEmail($returnPath)) {
            $message->setReturnPath($returnPath);
        }
        $message->setSubject($subject);
        $message->setBody($content, 'text/html');
        $message->send();

        return $message->isSent();
    }

    /**
     * constructor
     */
    public function initializeAction()
    {
        $this->extensionConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'newscatinvite',
            'newscatinvite'
        );
    }

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    public function injectInvitationRepository(InvitationRepository $invitationRepository): void
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function injectBackendUserGroupRepository(BackendUserGroupRepository $backendUserGroupRepository): void
    {
        $this->backendUserGroupRepository = $backendUserGroupRepository;
    }

    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository): void
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }
}
