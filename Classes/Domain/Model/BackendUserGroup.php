<?php

namespace Visol\Newscatinvite\Domain\Model;

use GeorgRinger\News\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
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
/**
 * This model represents a backend usergroup.
 *
 * @api
 */
class BackendUserGroup extends \TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup
{

    /**
     * @var ObjectStorage<Category>
     */
    protected $categoryPerms;

    /**
     * Constructs this backend usergroup
     */
    public function __construct()
    {
        $this->categoryPerms = new ObjectStorage();
        parent::__construct();
    }

    /**
     * @return ObjectStorage
     */
    public function getCategoryPerms()
    {
        return $this->categoryPerms;
    }
}
