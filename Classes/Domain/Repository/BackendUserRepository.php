<?php
namespace Visol\Newscatinvite\Domain\Repository;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Repository for \TYPO3\CMS\Extbase\Domain\Model\BackendUser.
 *
 * @api
 */
class BackendUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\BackendUserRepository {

	/**
	 * @param array $usergroups
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUsergroups($usergroups) {
		$usergroupConstraints = array();
		foreach ($usergroups as $usergroup) {
			$usergroupConstraints[] = 'AND FIND_IN_SET(' . $usergroup . ', usergroup_cached_list) ';
		}
		$statement = 'SELECT * FROM be_users WHERE 1=1 ' . implode($usergroupConstraints) . BackendUtility::BEenableFields('be_users') . BackendUtility::deleteClause('be_users');
		$query = $this->createQuery();
		$query->statement($statement);

		return $query->execute(TRUE);
	}

}
