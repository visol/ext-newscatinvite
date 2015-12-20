<?php
namespace Visol\Newscatinvite\Domain\Model;

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
class Invitation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	const STATUS_APPROVED = 1;
	const STATUS_PENDING = 0;
	const STATUS_REJECTED = -1;


	/**
	 * tstamp
	 *
	 * @var \DateTime
	 */
	protected $tstamp;

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
	 * @var \GeorgRinger\News\Domain\Model\Category
	 */
	protected $category;

	/**
	 * news
	 *
	 * @var \GeorgRinger\News\Domain\Model\News
	 */
	protected $news;

	/**
	 * approvingBeuser
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\BackendUser
	 */
	protected $approvingBeuser;

	/**
	 * creator
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\BackendUser
	 */
	protected $creator;

	/**
	 * __construct
	 */
	public function __construct() {
	}

	/**
	 * Returns the tstamp
	 *
	 * @return \DateTime $tstamp
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Sets the tstamp
	 *
	 * @param \DateTime $tstamp
	 * @return void
	 */
	public function setTstamp(\DateTime $tstamp) {
		$this->tstamp = $tstamp;
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
	 * Returns the category
	 *
	 * @return \GeorgRinger\News\Domain\Model\Category $category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Sets the category
	 *
	 * @param \GeorgRinger\News\Domain\Model\Category $category
	 * @return void
	 */
	public function setCategory(\GeorgRinger\News\Domain\Model\Category $category) {
		$this->category = $category;
	}

	/**
	 * Returns the news
	 *
	 * @return \GeorgRinger\News\Domain\Model\News $news
	 */
	public function getNews() {
		return $this->news;
	}

	/**
	 * Sets the news
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @return void
	 */
	public function setNews(\GeorgRinger\News\Domain\Model\News $news) {
		$this->news = $news;
	}

	/**
	 * Returns the approvingBeuser
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\BackendUser $approvingBeuser
	 */
	public function getApprovingBeuser() {
		return $this->approvingBeuser;
	}

	/**
	 * Sets the approvingBeuser
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\BackendUser $approvingBeuser
	 * @return void
	 */
	public function setApprovingBeuser(\TYPO3\CMS\Extbase\Domain\Model\BackendUser $approvingBeuser) {
		$this->approvingBeuser = $approvingBeuser;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Domain\Model\BackendUser
	 */
	public function getCreator() {
		return $this->creator;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\BackendUser $creator
	 */
	public function setCreator($creator) {
		$this->creator = $creator;
	}

}