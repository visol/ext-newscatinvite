<?php

namespace Visol\Newscatinvite\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class BackendUser extends AbstractEntity
{
    /**
     * @var ObjectStorage<BackendUserGroup>
     */
    protected $usergroupCachedList;

    // below are the "default" properties stolen from the extbase model
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'NotEmpty'])]
    protected string $userName = '';

    protected string $description = '';

    protected bool $isAdministrator = false;

    protected bool $isDisabled = false;

    protected ?\DateTime $startDateAndTime;

    protected ?\DateTime $endDateAndTime;

    protected string $email = '';

    protected string $realName = '';

    protected ?\DateTime $lastLoginDateAndTime;

    /**
     * Constructs this backend usergroup
     */
    public function __construct()
    {
        $this->usergroupCachedList = new ObjectStorage();
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * Sets the user name.
     *
     * @param string $userName the user name to set, must not be empty
     */
    public function setUserName($userName): void
    {
        $this->userName = $userName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Checks whether this user is an administrator.
     *
     * @return bool whether this user is an administrator
     */
    public function getIsAdministrator(): bool
    {
        return $this->isAdministrator;
    }

    /**
     * Sets whether this user should be an administrator.
     *
     * @param bool $isAdministrator whether this user should be an administrator
     */
    public function setIsAdministrator($isAdministrator): void
    {
        $this->isAdministrator = $isAdministrator;
    }

    /**
     * Checks whether this user is disabled.
     *
     * @return bool whether this user is disabled
     */
    public function getIsDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * Sets whether this user is disabled.
     *
     * @param bool $isDisabled whether this user is disabled
     */
    public function setIsDisabled($isDisabled): void
    {
        $this->isDisabled = $isDisabled;
    }

    /**
     * Returns the point in time from which this user is enabled.
     *
     * @return \DateTime|null the start date and time
     */
    public function getStartDateAndTime(): ?\DateTime
    {
        return $this->startDateAndTime;
    }

    /**
     * Sets the point in time from which this user is enabled.
     *
     * @param \DateTime|null $dateAndTime the start date and time
     */
    public function setStartDateAndTime(\DateTime $dateAndTime = null): void
    {
        $this->startDateAndTime = $dateAndTime;
    }

    /**
     * Returns the point in time before which this user is enabled.
     *
     * @return \DateTime|null the end date and time
     */
    public function getEndDateAndTime(): ?\DateTime
    {
        return $this->endDateAndTime;
    }

    /**
     * Sets the point in time before which this user is enabled.
     *
     * @param \DateTime|null $dateAndTime the end date and time
     */
    public function setEndDateAndTime(\DateTime $dateAndTime = null): void
    {
        $this->endDateAndTime = $dateAndTime;
    }

    /**
     * Gets the e-mail address of this user.
     *
     * @return string the e-mail address, might be empty
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the e-mail address of this user.
     *
     * @param string $email the e-mail address, may be empty
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Returns this user's real name.
     *
     * @return string the real name. might be empty
     */
    public function getRealName(): string
    {
        return $this->realName;
    }

    /**
     * Sets this user's real name.
     *
     * @param string $name the user's real name, may be empty.
     */
    public function setRealName($name): void
    {
        $this->realName = $name;
    }

    /**
     * Checks whether this user is currently activated.
     *
     * This function takes the "disabled" flag, the start date/time and the end date/time into account.
     *
     * @return bool whether this user is currently activated
     */
    public function isActivated(): bool
    {
        return !$this->getIsDisabled() && $this->isActivatedViaStartDateAndTime() && $this->isActivatedViaEndDateAndTime();
    }

    /**
     * Checks whether this user is activated as far as the start date and time is concerned.
     *
     * @return bool whether this user is activated as far as the start date and time is concerned
     */
    protected function isActivatedViaStartDateAndTime(): bool
    {
        if (!$this->getStartDateAndTime() instanceof \DateTime) {
            return true;
        }
        $now = new \DateTime('now');
        return $this->getStartDateAndTime() <= $now;
    }

    /**
     * Checks whether this user is activated as far as the end date and time is concerned.
     *
     * @return bool whether this user is activated as far as the end date and time is concerned
     */
    protected function isActivatedViaEndDateAndTime(): bool
    {
        if (!$this->getEndDateAndTime() instanceof \DateTime) {
            return true;
        }
        $now = new \DateTime('now');
        return $now <= $this->getEndDateAndTime();
    }

    /**
     * Gets this user's last login date and time.
     *
     * @return \DateTime|null this user's last login date and time, will be NULL if this user has never logged in before
     */
    public function getLastLoginDateAndTime(): ?\DateTime
    {
        return $this->lastLoginDateAndTime;
    }

    /**
     * Sets this user's last login date and time.
     *
     * @param \DateTime|null $dateAndTime this user's last login date and time
     */
    public function setLastLoginDateAndTime(\DateTime $dateAndTime = null): void
    {
        $this->lastLoginDateAndTime = $dateAndTime;
    }

    public function getUsergroupCachedList(): ObjectStorage
    {
        return $this->usergroupCachedList;
    }
}
