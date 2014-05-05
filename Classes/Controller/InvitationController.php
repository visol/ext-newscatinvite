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
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$invitations = $this->invitationRepository->findAll();
		$this->view->assign('invitations', $invitations);
	}

	/**
	 * action show
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @return void
	 */
	public function showAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$this->view->assign('invitation', $invitation);
	}

	/**
	 * action new
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $newInvitation
	 * @ignorevalidation $newInvitation
	 * @return void
	 */
	public function newAction(\Visol\Newscatinvite\Domain\Model\Invitation $newInvitation = NULL) {
		$this->view->assign('newInvitation', $newInvitation);
	}

	/**
	 * action create
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $newInvitation
	 * @return void
	 */
	public function createAction(\Visol\Newscatinvite\Domain\Model\Invitation $newInvitation) {
		$this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		$this->invitationRepository->add($newInvitation);
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @ignorevalidation $invitation
	 * @return void
	 */
	public function editAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$this->view->assign('invitation', $invitation);
	}

	/**
	 * action update
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @return void
	 */
	public function updateAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		$this->invitationRepository->update($invitation);
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Visol\Newscatinvite\Domain\Model\Invitation $invitation
	 * @return void
	 */
	public function deleteAction(\Visol\Newscatinvite\Domain\Model\Invitation $invitation) {
		$this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		$this->invitationRepository->remove($invitation);
		$this->redirect('list');
	}

}