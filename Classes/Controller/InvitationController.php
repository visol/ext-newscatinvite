<?php

namespace Visol\Newscatinvite\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use Visol\Newscatinvite\Domain\Repository\BackendUserRepository;
use Visol\Newscatinvite\Domain\Repository\InvitationRepository;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use Visol\Newscatinvite\Domain\Model\Invitation;

class InvitationController extends ActionController
{
    /**
     * invitationRepository
     *
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * @var BackendUserRepository
     */
    protected $backendUserRepository;

    /**
     * @var ConfigurationManager
     */
    protected $configurationManager;

    public function __construct()
    {
        $this->configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction(): ResponseInterface
    {
        $categoryUidArray = $GLOBALS['BE_USER']->getCategoryMountPoints();

        if (empty($categoryUidArray)) {
            $invitations = $this->invitationRepository->findByStatus(0);
        } else {
            $invitations = $this->invitationRepository->findByStatusAndCategories(
                Invitation::STATUS_PENDING,
                $categoryUidArray
            );
        }

        $this->view->assign('invitations', $invitations);
        return $this->htmlResponse();
    }

    /**
     * action listArchive
     *
     * @return void
     */
    public function listArchiveAction(): ResponseInterface
    {
        $categoryUidArray = $GLOBALS['BE_USER']->getCategoryMountPoints();

        if (empty($categoryUidArray)) {
            $invitations = $this->invitationRepository->findAll();
        } else {
            $invitations = $this->invitationRepository->findByCategories($categoryUidArray);
        }

        $this->view->assign('invitations', $invitations);
        return $this->htmlResponse();
    }

    /**
     * action listCreatedInvitations
     *
     * @return void
     */
    public function listCreatedInvitationsAction(): ResponseInterface
    {
        $backendUserUid = (int)$GLOBALS['BE_USER']->user['uid'];
        $invitations = $this->invitationRepository->findByCreator($backendUserUid);
        $this->view->assign('invitations', $invitations);
        return $this->htmlResponse();
    }

    /**
     * action approve
     *
     * @param Invitation $invitation
     *
     * @return void
     * TODO permission check
     * @Extbase\IgnoreValidation("invitation")
     */
    public function approveAction(Invitation $invitation)
    {
        $backendUser = $this->backendUserRepository->findByUid($GLOBALS["BE_USER"]->user["uid"]);
        $invitation->setStatus(Invitation::STATUS_APPROVED);
        $invitation->setApprovingBeuser($backendUser);
        $this->invitationRepository->update($invitation);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'approveMessage',
                'Newscatinvite'
            ),
            '',
            AbstractMessage::OK
        );

        $this->redirect('list');
    }

    /**
     * action reject
     *
     * @param Invitation $invitation
     *
     * @return void
     * TODO permission check*
     * @Extbase\IgnoreValidation("invitation")
     */
    public function rejectAction(Invitation $invitation)
    {
        $backendUser = $this->backendUserRepository->findByUid($GLOBALS["BE_USER"]->user["uid"]);
        $invitation->setStatus(Invitation::STATUS_REJECTED);
        $invitation->setApprovingBeuser($backendUser);
        $this->invitationRepository->update($invitation);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'rejectMessage',
                'Newscatinvite'
            ),
            '',
            AbstractMessage::OK
        );

        $this->redirect('list');
    }

    /**
     * action remove
     *
     * @param Invitation $invitation
     *
     * @return void
     * TODO permission check
     * @Extbase\IgnoreValidation("invitation")
     */
    public function removeAction(Invitation $invitation)
    {
        $this->invitationRepository->remove($invitation);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'deleteMessage',
                'Newscatinvite'
            ),
            '',
            AbstractMessage::OK
        );

        $this->redirect('listArchive');
    }

    public function injectInvitationRepository(InvitationRepository $invitationRepository): void
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository): void
    {
        $this->backendUserRepository = $backendUserRepository;
    }
}
