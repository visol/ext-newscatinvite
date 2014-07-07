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
	 * @var \Tx_News_Domain_Model_Category
	 */
	protected $category;

	/**
	 * news
	 *
	 * @var \Tx_News_Domain_Model_News
	 */
	protected $news;

	/**
	 * approvingBeuser
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\BackendUser
	 */
	protected $approvingBeuser;

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
		// TODO: Datum aus DB ausgeben
		return new \DateTime();
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
	 * @return \Tx_News_Domain_Model_Category $category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Sets the category
	 *
	 * @param \Tx_News_Domain_Model_Category $category
	 * @return void
	 */
	public function setCategory(\Tx_News_Domain_Model_Category $category) {
		$this->category = $category;
	}

	/**
	 * Returns the news
	 *
	 * @return \Tx_News_Domain_Model_News $news
	 */
	public function getNews() {
		return $this->news;
	}

	/**
	 * Sets the news
	 *
	 * @param \Tx_News_Domain_Model_News $news
	 * @return void
	 */
	public function setNews(\Tx_News_Domain_Model_News $news) {
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

}