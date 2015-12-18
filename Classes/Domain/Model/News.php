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
class News extends \Tx_News_Domain_Model_News {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Visol\Newscatinvite\Domain\Model\Invitation>
	 * @lazy
	 */
	protected $invitations;

	public function __construct() {
		$this->invitations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		parent::__construct();
	}

}