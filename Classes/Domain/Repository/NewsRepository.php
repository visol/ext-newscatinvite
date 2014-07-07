<?php
namespace Visol\Newscatinvite\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Georg Ringer <typo3@ringerge.org>
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

class NewsRepository extends \Tx_News_Domain_Repository_NewsRepository {

	/**
	 * Returns a category constraint created by
	 * a given list of categories and a junction string
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $categories
	 * @param  string $conjunction
	 * @param  boolean $includeSubCategories
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
	 */
	protected function createCategoryConstraint(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, $categories, $conjunction, $includeSubCategories = FALSE) {
		$constraint = NULL;
		$categoryConstraints = array();
		$invitationConstraints = array();

		// If "ignore category selection" is used, nothing needs to be done
		if (empty($conjunction)) {
			return $constraint;
		}

		if (!is_array($categories)) {
			$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $categories, TRUE);
		}
		foreach ($categories as $category) {
			if ($includeSubCategories) {
				$subCategories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', \Tx_News_Service_CategoryService::getChildrenCategories($category, 0, '', TRUE), TRUE);
				$subCategoryConstraint = array();
				$subCategoryConstraint[] = $query->contains('categories', $category);
				if (count($subCategories) > 0) {
					foreach ($subCategories as $subCategory) {
						$subCategoryConstraint[] = $query->contains('categories', $subCategory);
					}
				}
				if ($subCategoryConstraint) {
					$categoryConstraints[] = $query->logicalOr($subCategoryConstraint);
				}

			} else {
				$categoryConstraints[] = $query->contains('categories', $category);
				$invitationConstraints[] = $query->logicalAnd(
					$query->equals('invitations.category', $category),
					$query->equals('invitations.status', \Visol\Newscatinvite\Domain\Model\Invitation::STATUS_APPROVED)
				);
			}
		}

		if ($categoryConstraints) {
			switch (strtolower($conjunction)) {
				case 'or':
					$categoryConstraint = $query->logicalOr($categoryConstraints);
					break;
				case 'notor':
					$categoryConstraint = $query->logicalNot($query->logicalOr($categoryConstraints));
					break;
				case 'notand':
					$categoryConstraint = $query->logicalNot($query->logicalAnd($categoryConstraints));
					break;
				case 'and':
				default:
				$categoryConstraint = $query->logicalAnd($categoryConstraints);
			}
		}

		if ($invitationConstraints) {
			$invitationConstraint = $query->logicalOr($invitationConstraints);
		}

		$constraint = $query->logicalOr(
			$categoryConstraint,
			$invitationConstraint
		);

		return $constraint;
	}

}
