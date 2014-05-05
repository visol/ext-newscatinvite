<?php

namespace Visol\Newscatinvite\Tests\Unit\Domain\Model;

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
 * Test case for class \Visol\Newscatinvite\Domain\Model\Invitation.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Jonas Renggli <jonas.renggli@visol.ch>
 */
class InvitationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Visol\Newscatinvite\Domain\Model\Invitation
	 */
	protected $subject;

	public function setUp() {
		$this->subject = new \Visol\Newscatinvite\Domain\Model\Invitation();
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getStatusReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getStatus()
		);
	}

	/**
	 * @test
	 */
	public function setStatusForIntegerSetsStatus() {
		$this->subject->setStatus(12);

		$this->assertAttributeEquals(
			12,
			'status',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSentReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getSent()
		);
	}

	/**
	 * @test
	 */
	public function setSentForBooleanSetsSent() {
		$this->subject->setSent(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'sent',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCategoryReturnsInitialValueFor() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getCategory()
		);
	}

	/**
	 * @test
	 */
	public function setCategoryForObjectStorageContainingSetsCategory() {
		$category = new ();
		$objectStorageHoldingExactlyOneCategory = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCategory->attach($category);
		$this->subject->setCategory($objectStorageHoldingExactlyOneCategory);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneCategory,
			'category',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addCategoryToObjectStorageHoldingCategory() {
		$category = new ();
		$categoryObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$categoryObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($category));
		$this->inject($this->subject, 'category', $categoryObjectStorageMock);

		$this->subject->addCategory($category);
	}

	/**
	 * @test
	 */
	public function removeCategoryFromObjectStorageHoldingCategory() {
		$category = new ();
		$categoryObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$categoryObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($category));
		$this->inject($this->subject, 'category', $categoryObjectStorageMock);

		$this->subject->removeCategory($category);

	}

	/**
	 * @test
	 */
	public function getNewsReturnsInitialValueFor() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getNews()
		);
	}

	/**
	 * @test
	 */
	public function setNewsForObjectStorageContainingSetsNews() {
		$news = new ();
		$objectStorageHoldingExactlyOneNews = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneNews->attach($news);
		$this->subject->setNews($objectStorageHoldingExactlyOneNews);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneNews,
			'news',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addNewsToObjectStorageHoldingNews() {
		$news = new ();
		$newsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$newsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($news));
		$this->inject($this->subject, 'news', $newsObjectStorageMock);

		$this->subject->addNews($news);
	}

	/**
	 * @test
	 */
	public function removeNewsFromObjectStorageHoldingNews() {
		$news = new ();
		$newsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$newsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($news));
		$this->inject($this->subject, 'news', $newsObjectStorageMock);

		$this->subject->removeNews($news);

	}

	/**
	 * @test
	 */
	public function getApprovingBeuserReturnsInitialValueFor() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getApprovingBeuser()
		);
	}

	/**
	 * @test
	 */
	public function setApprovingBeuserForObjectStorageContainingSetsApprovingBeuser() {
		$approvingBeuser = new ();
		$objectStorageHoldingExactlyOneApprovingBeuser = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneApprovingBeuser->attach($approvingBeuser);
		$this->subject->setApprovingBeuser($objectStorageHoldingExactlyOneApprovingBeuser);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneApprovingBeuser,
			'approvingBeuser',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addApprovingBeuserToObjectStorageHoldingApprovingBeuser() {
		$approvingBeuser = new ();
		$approvingBeuserObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$approvingBeuserObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($approvingBeuser));
		$this->inject($this->subject, 'approvingBeuser', $approvingBeuserObjectStorageMock);

		$this->subject->addApprovingBeuser($approvingBeuser);
	}

	/**
	 * @test
	 */
	public function removeApprovingBeuserFromObjectStorageHoldingApprovingBeuser() {
		$approvingBeuser = new ();
		$approvingBeuserObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$approvingBeuserObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($approvingBeuser));
		$this->inject($this->subject, 'approvingBeuser', $approvingBeuserObjectStorageMock);

		$this->subject->removeApprovingBeuser($approvingBeuser);

	}
}
