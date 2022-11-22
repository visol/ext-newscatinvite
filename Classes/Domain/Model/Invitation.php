<?php

namespace Visol\Newscatinvite\Domain\Model;

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

    /**
     * @var NewsRepository
     */
    protected $newsRepository;

    /**
     * @var NewsService
     */
    protected $newsService;

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
     * @Extbase\ORM\Transient
     */
    protected $rawNews;

    /**
     * @var BackendUser
     */
    protected $approvingBeuser;

    /**
     * @var BackendUser|null
     */
    protected ?BackendUser $creator = null;

    /**
     * @return \DateTime $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @param \DateTime $tstamp
     *
     * @return void
     */
    public function setTstamp(\DateTime $tstamp)
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
     * @return void
     */
    public function setStatus($status)
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
     *
     * @return void
     */
    public function setSent($sent)
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

    /**
     * @param Category $category
     *
     * @return void
     */
    public function setCategory(Category $category)
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

    /**
     * @param News $news
     *
     * @return void
     */
    public function setNews(News $news)
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
        $rawNewsRecord = $this->newsService->getRawNewsRecordWithCategories((int)$this->news);

        return $rawNewsRecord;
    }

    /**
     * @return BackendUser $approvingBeuser
     */
    public function getApprovingBeuser()
    {
        return $this->approvingBeuser;
    }

    /**
     * @param BackendUser $approvingBeuser
     *
     * @return void
     */
    public function setApprovingBeuser(BackendUser $approvingBeuser)
    {
        $this->approvingBeuser = $approvingBeuser;
    }

    /**
     * @return BackendUser|null
     */
    public function getCreator(): ?BackendUser
    {
        return $this->creator;
    }

    /**
     * @param ?BackendUser $creator
     */
    public function setCreator(?BackendUser $creator): void
    {
        $this->creator = $creator;
    }

    public function injectNewsRepository(NewsRepository $newsRepository): void
    {
        $this->newsRepository = $newsRepository;
    }

    public function injectNewsService(NewsService $newsService): void
    {
        $this->newsService = $newsService;
    }
}
