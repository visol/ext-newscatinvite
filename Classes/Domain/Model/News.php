<?php

namespace Visol\Newscatinvite\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
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
class News extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * @var ObjectStorage<Invitation>
     * @Extbase\ORM\Lazy
     */
    protected $invitations;

    public function __construct()
    {
        $this->invitations = new ObjectStorage();
        parent::__construct();
    }
}
