<?php

namespace Visol\Newscatinvite\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Domain\Model\BackendUser;
use Visol\Newscatinvite\Domain\Repository\NewsRepository;
use Visol\Newscatinvite\Service\NewsService;
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
     * @var Category
     */
    protected $category;

    /**
     * news
     *
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
     * approvingBeuser
     *
     * @var BackendUser
     */
    protected $approvingBeuser;

    /**
     * creator
     *
     * @var BackendUser
     */
    protected $creator;

    /**
     * __construct
     */
    public function __construct()
    {
    }

    /**
     * Returns the tstamp
     *
     * @return \DateTime $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the tstamp
     *
     * @param \DateTime $tstamp
     *
     * @return void
     */
    public function setTstamp(\DateTime $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Returns the status
     *
     * @return integer $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status
     *
     * @param integer $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns the sent
     *
     * @return boolean $sent
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Sets the sent
     *
     * @param boolean $sent
     *
     * @return void
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    /**
     * Returns the boolean state of sent
     *
     * @return boolean
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * Returns the category
     *
     * @return Category $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param Category $category
     *
     * @return void
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the news
     *
     * @return News $news
     */
    public function getNews()
    {
        $newsRecord = $this->newsRepository->findByUid($this->news, false);

        return $newsRecord;
    }

    /**
     * Sets the news
     *
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
        $rawNewsRecord = $this->newsService->getRawNewsRecordWithCategories($this->news);

        return $rawNewsRecord;
    }

    /**
     * Returns the approvingBeuser
     *
     * @return BackendUser $approvingBeuser
     */
    public function getApprovingBeuser()
    {
        return $this->approvingBeuser;
    }

    /**
     * Sets the approvingBeuser
     *
     * @param BackendUser $approvingBeuser
     *
     * @return void
     */
    public function setApprovingBeuser(BackendUser $approvingBeuser)
    {
        $this->approvingBeuser = $approvingBeuser;
    }

    /**
     * @return BackendUser
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param BackendUser $creator
     */
    public function setCreator($creator)
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
