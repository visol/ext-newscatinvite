<?php
namespace Visol\Newscatinvite\Domain\Model;


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
 * Invitation
 */
class Invitation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * status
	 *
	 * @var integer
	 */
	protected $status = '0';

	/**
	 * sent
	 *
	 * @var boolean
	 */
	protected $sent = 'FALSE';

	/**
	 * category
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<>
	 * @cascade remove
	 */
	protected $category;

	/**
	 * news
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<>
	 * @cascade remove
	 */
	protected $news;

	/**
	 * __construct
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties
	 * Do not modify this method!
	 * It will be rewritten on each save in the extension builder
	 * You may modify the constructor of this class instead
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->category = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->news = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the status
	 *
	 * @return integer $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Sets the status
	 *
	 * @param integer $status
	 * @return void
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * Returns the sent
	 *
	 * @return boolean $sent
	 */
	public function getSent() {
		return $this->sent;
	}

	/**
	 * Sets the sent
	 *
	 * @param boolean $sent
	 * @return void
	 */
	public function setSent($sent) {
		$this->sent = $sent;
	}

	/**
	 * Returns the boolean state of sent
	 *
	 * @return boolean
	 */
	public function isSent() {
		return $this->sent;
	}

	/**
	 * Adds a
	 *
	 * @param  $category
	 * @return void
	 */
	public function addCategory($category) {
		$this->category->attach($category);
	}

	/**
	 * Removes a
	 *
	 * @param $categoryToRemove The  to be removed
	 * @return void
	 */
	public function removeCategory($categoryToRemove) {
		$this->category->detach($categoryToRemove);
	}

	/**
	 * Returns the category
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<> $category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Sets the category
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<> $category
	 * @return void
	 */
	public function setCategory(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $category) {
		$this->category = $category;
	}

	/**
	 * Adds a
	 *
	 * @param  $news
	 * @return void
	 */
	public function addNews($news) {
		$this->news->attach($news);
	}

	/**
	 * Removes a
	 *
	 * @param $newsToRemove The  to be removed
	 * @return void
	 */
	public function removeNews($newsToRemove) {
		$this->news->detach($newsToRemove);
	}

	/**
	 * Returns the news
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<> $news
	 */
	public function getNews() {
		return $this->news;
	}

	/**
	 * Sets the news
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<> $news
	 * @return void
	 */
	public function setNews(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $news) {
		$this->news = $news;
	}

}