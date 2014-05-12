<?php
namespace Visol\Newscatinvite\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Jonas Renggli <jonas.renggli@visol.ch>, visol digitale Dienstleistungen GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


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

		$invitations = $this->invitationRepository->findByStatus(0);
		$this->view->assign('invitations', $invitations);
	}

	/**
	 * action listArchive
	 *
	 * @return void
	 */
	public function listArchiveAction() {
		$invitations = $this->invitationRepository->findAll();
		$this->view->assign('invitations', $invitations);
	}

	/**
	 * action approve
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @ignorevalidation $invitation
	 * @dontvalidate  $invitation
	 * @return void
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
	 */
	public function rejectAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$backendUser = $this->backendUserRepository->findByUid($GLOBALS["BE_USER"]->user["uid"]);
		$invitation->setStatus(\Visol\Newscatinvite\Domain\Model\Invitation::STATUS_REJECTED);
		$invitation->setApprovingBeuser($backendUser);
		$this->invitationRepository->update($invitation);

		$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('rejectMessage', 'Newscatinvite'), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

		$this->redirect('list');
	}

}