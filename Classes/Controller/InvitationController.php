<?php
namespace Visol\Newscatinvite\Controller;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Visol\Newscatinvite\Domain\Model\Invitation;


/**
 * InvitationController
 */
class InvitationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * invitationRepository
	 *
	 * @var \Visol\Newscatinvite\Domain\Repository\InvitationRepository
	 * @inject
	 */
	protected $invitationRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\BackendUserRepository
	 * @inject
	 */
	protected $backendUserRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;



	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		//$settings = $this->configurationManager->getConfiguration(\Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'Visol.Newscatinvite', 'Invitations');$settings = $this->configurationManager->getConfiguration(\Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'Visol.Newscatinvite', 'Invitations');
		//$settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'Visol.Newscatinvite', 'Invitations');$settings = $this->configurationManager->getConfiguration(\Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($settings);

		$categoryUidArray = $GLOBALS['BE_USER']->getCategoryMountPoints();

		if (empty($categoryUidArray)) {
			$invitations = $this->invitationRepository->findByStatus(0);
		} else {
			$invitations = $this->invitationRepository->findByStatusAndCategories(Invitation::STATUS_PENDING, $categoryUidArray);
		}

		$this->view->assign('invitations', $invitations);
	}

	/**
	 * action listArchive
	 *
	 * @return void
	 */
	public function listArchiveAction() {
		$categoryUidArray = $GLOBALS['BE_USER']->getCategoryMountPoints();

		if (empty($categoryUidArray)) {
			$invitations = $this->invitationRepository->findAll();
		} else {
			$invitations = $this->invitationRepository->findByCategories($categoryUidArray);
		}

		$this->view->assign('invitations', $invitations);
	}

	/**
	 * action listCreatedInvitations
	 *
	 * @return void
	 */
	public function listCreatedInvitationsAction() {
		$backendUserUid = (int)$GLOBALS['BE_USER']->user['uid'];
		$invitations = $this->invitationRepository->findByCreator($backendUserUid);
		$this->view->assign('invitations', $invitations);
	}

	/**
	 * action approve
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @ignorevalidation $invitation
	 * @dontvalidate  $invitation
	 * @return void
	 * TODO permission check
	 */
	public function approveAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$backendUser = $this->backendUserRepository->findByUid($GLOBALS["BE_USER"]->user["uid"]);
		$invitation->setStatus(\Visol\Newscatinvite\Domain\Model\Invitation::STATUS_APPROVED);
		$invitation->setApprovingBeuser($backendUser);
		$this->invitationRepository->update($invitation);

		$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('approveMessage', 'Newscatinvite'), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

		$this->redirect('list');
	}

	/**
	 * action reject
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @ignorevalidation $invitation
	 * @dontvalidate  $invitation
	 * @return void
	 * TODO permission check*
	 */
	public function rejectAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$backendUser = $this->backendUserRepository->findByUid($GLOBALS["BE_USER"]->user["uid"]);
		$invitation->setStatus(\Visol\Newscatinvite\Domain\Model\Invitation::STATUS_REJECTED);
		$invitation->setApprovingBeuser($backendUser);
		$this->invitationRepository->update($invitation);

		$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('rejectMessage', 'Newscatinvite'), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

		$this->redirect('list');
	}

	/**
	 * action remove
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @ignorevalidation $invitation
	 * @dontvalidate  $invitation
	 * @return void
	 * TODO permission check
	 */
	public function removeAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$this->invitationRepository->remove($invitation);

		$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('deleteMessage', 'Newscatinvite'), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

		$this->redirect('listArchive');
	}

}