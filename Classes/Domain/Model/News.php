<?php

namespace Visol\Newscatinvite\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class News extends \GeorgRinger\News\Domain\Model\News
{
    /**
     * @var ObjectStorage<Invitation>
     */
    #[Extbase\ORM\Lazy]
    protected $invitations;

    public function __construct()
    {
        $this->invitations = new ObjectStorage();
        parent::__construct();
    }
}
