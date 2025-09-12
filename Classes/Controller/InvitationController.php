<?php

namespace Visol\Newscatinvite\Controller;

use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDown\DropDownItem;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Visol\Newscatinvite\Domain\Repository\BackendUserRepository;
use Visol\Newscatinvite\Domain\Repository\InvitationRepository;
use Visol\Newscatinvite\Domain\Model\Invitation;

#[AsController]
class InvitationController extends ActionController
{
    private ModuleTemplate $moduleTemplate;

    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * @var BackendUserRepository
     */
    protected $backendUserRepository;

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    ) {
    }

    public function initializeAction(): void
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->setDocHeader();
    }

    public function listAction(): ResponseInterface
    {
        $categoryUidArray = $this->getBackendUserAuthentication()->getCategoryMountPoints();
        if ($categoryUidArray === []) {
            $invitations = $this->invitationRepository->findByStatus(Invitation::STATUS_PENDING);
        } else {
            $invitations = $this->invitationRepository->findByStatusAndCategories(
                Invitation::STATUS_PENDING,
                $categoryUidArray
            );
        }
        $this->moduleTemplate->assign('invitations', $invitations);
        $this->moduleTemplate->assign('settings', $this->settings);
        return $this->moduleTemplate->renderResponse('Invitation/List');
    }

    public function listArchiveAction(): ResponseInterface
    {
        $categoryUidArray = $this->getBackendUserAuthentication()->getCategoryMountPoints();

        if (empty($categoryUidArray)) {
            $invitations = $this->invitationRepository->findAll();
        } else {
            $invitations = $this->invitationRepository->findByCategories($categoryUidArray);
        }

        $this->moduleTemplate->assign('invitations', $invitations);
        $this->moduleTemplate->assign('settings', $this->settings);
        return $this->moduleTemplate->renderResponse('Invitation/ListArchive');
    }

    public function listCreatedInvitationsAction(): ResponseInterface
    {
        $backendUserUid = (int)$this->getBackendUserAuthentication()->user['uid'];
        $invitations = $this->invitationRepository->findBy(['creator' => $backendUserUid]);
        $this->moduleTemplate->assign('invitations', $invitations);
        $this->moduleTemplate->assign('settings', $this->settings);
        return $this->moduleTemplate->renderResponse('Invitation/ListCreatedInvitations');
    }

    /**
     * TODO permission check
     */
    #[Extbase\IgnoreValidation(['argumentName' => 'invitation'])]
    public function approveAction(Invitation $invitation): ResponseInterface
    {
        $backendUser = $this->backendUserRepository->findByUid($this->getBackendUserAuthentication()->user['uid']);
        $invitation->setStatus(Invitation::STATUS_APPROVED);
        $invitation->setApprovingBeuser($backendUser);
        $this->invitationRepository->update($invitation);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'approveMessage',
                'Newscatinvite'
            ),
        );

        return $this->redirect('list');
    }

    /**
     * TODO permission check*
     */
    #[Extbase\IgnoreValidation(['argumentName' => 'invitation'])]
    public function rejectAction(Invitation $invitation): ResponseInterface
    {
        $backendUser = $this->backendUserRepository->findByUid($this->getBackendUserAuthentication()->user["uid"]);
        $invitation->setStatus(Invitation::STATUS_REJECTED);
        $invitation->setApprovingBeuser($backendUser);
        $this->invitationRepository->update($invitation);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'rejectMessage',
                'Newscatinvite'
            ),
        );

        return $this->redirect('list');
    }

    /**
     * TODO permission check
     */
    #[Extbase\IgnoreValidation(['argumentName' => 'invitation'])]
    public function removeAction(Invitation $invitation): ResponseInterface
    {
        $this->invitationRepository->remove($invitation);
        $this->addFlashMessage(
            LocalizationUtility::translate(
                'deleteMessage',
                'Newscatinvite'
            ),
        );

        return $this->redirect('listArchive');
    }

    public function setDocHeader(): void
    {
        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $dropdownLabel = LocalizationUtility::translate(
            'LLL:EXT:newscatinvite/Resources/Private/Language/locallang.xlf:submoduleDropdownLabel'
        );
        $dropDownButton = $buttonBar->makeDropDownButton()->setLabel($dropdownLabel)->setTitle(
            $dropdownLabel
        )->setShowLabelText($dropdownLabel);

        $dropDownButton->addItem(
            GeneralUtility::makeInstance(DropDownItem::class)->setLabel(
                LocalizationUtility::translate(
                    'LLL:EXT:newscatinvite/Resources/Private/Language/locallang.xlf:submoduleOverviewTitle'
                )
            )->setHref(
                $this->uriBuilder->setArguments(
                    [
                        'controller' => 'Invitation',
                        'action' => 'list',
                    ]
                )->buildBackendUri()
            )
        );
        $dropDownButton->addItem(
            GeneralUtility::makeInstance(DropDownItem::class)->setLabel(
                LocalizationUtility::translate(
                    'LLL:EXT:newscatinvite/Resources/Private/Language/locallang.xlf:submoduleArchiveTitle'
                )
            )->setHref(
                $this->uriBuilder->setArguments(
                    [
                        'controller' => 'Invitation',
                        'action' => 'listArchive',
                    ]
                )->buildBackendUri()
            )
        );
        $dropDownButton->addItem(
            GeneralUtility::makeInstance(DropDownItem::class)->setLabel(
                LocalizationUtility::translate(
                    'LLL:EXT:newscatinvite/Resources/Private/Language/locallang.xlf:submoduleCreatedInvitationsTitle'
                )
            )->setHref(
                $this->uriBuilder->setArguments(
                    [
                        'controller' => 'Invitation',
                        'action' => 'listCreatedInvitations',
                    ]
                )->buildBackendUri()
            )
        );

        $buttonBar->addButton($dropDownButton);
    }

    public function injectInvitationRepository(InvitationRepository $invitationRepository): void
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository): void
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

}
