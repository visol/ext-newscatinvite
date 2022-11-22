<?php

namespace Visol\Newscatinvite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Visol\Newscatinvite\Domain\Model\Invitation;
use Visol\Newscatinvite\Domain\Model\BackendUserGroup;
use TYPO3\CMS\Fluid\View\StandaloneView;
use GeorgRinger\News\Domain\Model\Category;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use Visol\Newscatinvite\Domain\Repository\InvitationRepository;
use Visol\Newscatinvite\Domain\Repository\BackendUserGroupRepository;
use Visol\Newscatinvite\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class InvitationSendCommand extends Command
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
     * @var PersistenceManager
     */
    protected $persistenceManager;

    protected SymfonyStyle $io;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $this->invitationRepository = GeneralUtility::makeInstance(InvitationRepository::class);
        $this->backendUserGroupRepository = GeneralUtility::makeInstance(BackendUserGroupRepository::class);
        $this->backendUserRepository = GeneralUtility::makeInstance(BackendUserRepository::class);
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        $this->extensionConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'newscatinvite',
            'newscatinvite'
        );
    }

    protected function configure()
    {
        $this
            ->addOption(
                'overrideRecipient',
                '',
                InputOption::VALUE_OPTIONAL,
                'Override the recipient with this address - for testing purposes',
                ''
            );
    }

    /**
     * Send pending invitations
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $overrideRecipient = $input->getOption('overrideRecipient');

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
                $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
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
                if ($invitation->getCreator() && $invitation->getCreator()->getEmail() !== '') {
                    $replyTo = $invitation->getCreator()->getEmail();
                }
                $emailIsSent = $this->sendEmail($recipientArray, $sender, $subject, $content, $replyTo);
                if ($emailIsSent) {
                    $invitation->setSent(true);
                    $this->invitationRepository->update($invitation);
                }
            }
        }

        $this->persistenceManager->persistAll();
        return 0;
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
        $message->html($content);
        $message->send();

        return $message->isSent();
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
