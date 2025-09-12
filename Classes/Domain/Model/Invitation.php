<?php

namespace Visol\Newscatinvite\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\News;
use Visol\Newscatinvite\Domain\Repository\NewsRepository;
use Visol\Newscatinvite\Service\NewsService;

class Invitation extends AbstractEntity
{
    const STATUS_APPROVED = 1;
    const STATUS_PENDING = 0;
    const STATUS_REJECTED = -1;

    protected ?NewsRepository $newsRepository = null;

    protected ?NewsService $newsService = null;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var integer
     */
    protected $status = 0;

    /**
     * @var boolean
     */
    protected $sent = false;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var int
     */
    protected $news;

    /**
     * the raw news record as an array
     *
     * @var array
     */
    #[Extbase\ORM\Transient]
    protected $rawNews;

    /**
     * @var BackendUser
     */
    protected $approvingBeuser;

    protected ?BackendUser $creator = null;

    public function initializeObject(): void
    {
        $this->newsRepository = GeneralUtility::makeInstance(NewsRepository::class);
        $this->newsService = GeneralUtility::makeInstance(NewsService::class);
    }

    /**
     * @return \DateTime $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    public function setTstamp(\DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * @return integer $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return boolean $sent
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @param boolean $sent
     */
    public function setSent($sent): void
    {
        $this->sent = $sent;
    }

    /**
     * @return boolean
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * @return Category $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return News $news
     */
    public function getNews()
    {
        return $this->newsRepository->findByUid($this->news, false);
    }

    public function setNews(News $news): void
    {
        $this->news = $news;
    }

    /**
     * Gets the raw news
     * Only used for notification e-mails
     *
     * @return array
     */
    public function getRawNews()
    {
        return $this->newsService->getRawNewsRecordWithCategories($this->news);
    }

    /**
     * @return BackendUser $approvingBeuser
     */
    public function getApprovingBeuser()
    {
        return $this->approvingBeuser;
    }

    public function setApprovingBeuser(BackendUser $approvingBeuser): void
    {
        $this->approvingBeuser = $approvingBeuser;
    }

    public function getCreator(): ?BackendUser
    {
        return $this->creator;
    }

    public function setCreator(?BackendUser $creator): void
    {
        $this->creator = $creator;
    }

}
