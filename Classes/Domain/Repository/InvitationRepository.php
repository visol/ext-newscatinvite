<?php
namespace Visol\Newscatinvite\Domain\Repository;


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
 * The repository for Invitations
 */
class InvitationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	public function initializeObject() {
		/** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		$querySettings->setRespectSysLanguage(FALSE);
		$querySettings->setIgnoreEnableFields(TRUE);
		$this->setDefaultQuerySettings($querySettings);
	}

	public function findByStatusAndCategories($status, Array $categories) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('status', $status),
				$query->in('category', $categories),
				$query->greaterThan('category.uid', 0),
				$query->greaterThan('news.uid', 0)
			)
		);


		return $query->execute();
	}

	public function findByCategories(Array $categories) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->in('category', $categories),
				$query->greaterThan('category.uid', 0),
				$query->greaterThan('news.uid', 0),
				$query->logicalOr(
					$query->equals('status', \Visol\Newscatinvite\Domain\Model\Invitation::STATUS_APPROVED),
					$query->equals('status', \Visol\Newscatinvite\Domain\Model\Invitation::STATUS_REJECTED)
				)
			)
		);

		return $query->execute();
	}

}