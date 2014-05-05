<?php
namespace Visol\Newscatinvite\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Jonas Renggli <jonas.renggli@visol.ch>, visol digitale Dienstleistungen GmbH
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Visol\Newscatinvite\Controller\InvitationController.
 *
 * @author Jonas Renggli <jonas.renggli@visol.ch>
 */
class InvitationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Visol\Newscatinvite\Controller\InvitationController
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getMock('Visol\\Newscatinvite\\Controller\\InvitationController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllInvitationsFromRepositoryAndAssignsThemToView() {

		$allInvitations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('findAll'), array(), '', FALSE);
		$invitationRepository->expects($this->once())->method('findAll')->will($this->returnValue($allInvitations));
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('invitations', $allInvitations);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenInvitationToView() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('invitation', $invitation);

		$this->subject->showAction($invitation);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenInvitationToView() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newInvitation', $invitation);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($invitation);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenInvitationToInvitationRepository() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('add'), array(), '', FALSE);
		$invitationRepository->expects($this->once())->method('add')->with($invitation);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->createAction($invitation);
	}

	/**
	 * @test
	 */
	public function createActionAddsMessageToFlashMessageContainer() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('add'), array(), '', FALSE);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);
		$this->subject->expects($this->once())->method('addFlashMessage');

		$this->subject->createAction($invitation);
	}

	/**
	 * @test
	 */
	public function createActionRedirectsToListAction() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('add'), array(), '', FALSE);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->expects($this->once())->method('redirect')->with('list');
		$this->subject->createAction($invitation);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenInvitationToView() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('invitation', $invitation);

		$this->subject->editAction($invitation);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenInvitationInInvitationRepository() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('update'), array(), '', FALSE);
		$invitationRepository->expects($this->once())->method('update')->with($invitation);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->updateAction($invitation);
	}

	/**
	 * @test
	 */
	public function updateActionAddsMessageToFlashMessageContainer() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('update'), array(), '', FALSE);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->expects($this->once())->method('addFlashMessage');
		$this->subject->updateAction($invitation);
	}

	/**
	 * @test
	 */
	public function updateActionRedirectsToListAction() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('update'), array(), '', FALSE);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->expects($this->once())->method('redirect')->with('list');
		$this->subject->updateAction($invitation);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenInvitationFromInvitationRepository() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('remove'), array(), '', FALSE);
		$invitationRepository->expects($this->once())->method('remove')->with($invitation);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->deleteAction($invitation);
	}

	/**
	 * @test
	 */
	public function deleteActionAddsMessageToFlashMessageContainer() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('remove'), array(), '', FALSE);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);
		$this->subject->expects($this->once())->method('addFlashMessage');

		$this->subject->deleteAction($invitation);
	}

	/**
	 * @test
	 */
	public function deleteActionRedirectsToListAction() {
		$invitation = new \Visol\Newscatinvite\Domain\Model\Invitation();

		$invitationRepository = $this->getMock('Visol\\Newscatinvite\\Domain\\Repository\\InvitationRepository', array('remove'), array(), '', FALSE);
		$this->inject($this->subject, 'invitationRepository', $invitationRepository);

		$this->subject->expects($this->once())->method('redirect')->with('list');
		$this->subject->deleteAction($invitation);
	}
}
