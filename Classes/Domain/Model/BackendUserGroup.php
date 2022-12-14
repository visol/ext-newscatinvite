<?php

namespace Visol\Newscatinvite\Domain\Model;

use GeorgRinger\News\Domain\Model\Category;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class BackendUserGroup extends AbstractEntity
{
    /**
     * @var ObjectStorage<Category>
     */
    protected $categoryPerms;

    // below are the "default" properties stolen from the extbase model

    const FILE_OPPERATIONS = 1;
    const DIRECTORY_OPPERATIONS = 4;
    const DIRECTORY_COPY = 8;
    const DIRECTORY_REMOVE_RECURSIVELY = 16;

    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected string $title = '';

    protected string $description = '';

    protected string $modules = '';

    protected string $tablesListening = '';

    protected string $tablesModify = '';

    protected string $pageTypes = '';

    protected string $allowedExcludeFields = '';

    protected string $explicitlyAllowAndDeny = '';

    protected string $allowedLanguages = '';

    protected bool $workspacePermission = false;

    protected string $databaseMounts = '';

    protected int $fileOperationPermissions = 0;

    protected string $tsConfig = '';

    /**
     * Constructs this backend usergroup
     */
    public function __construct()
    {
        $this->categoryPerms = new ObjectStorage();
    }

    /**
     * Setter for title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Setter for description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Setter for modules
     *
     * @param string $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }

    public function getModules(): string
    {
        return $this->modules;
    }

    /**
     * Setter for tables listening
     *
     * @param string $tablesListening
     */
    public function setTablesListening($tablesListening)
    {
        $this->tablesListening = $tablesListening;
    }

    public function getTablesListening(): string
    {
        return $this->tablesListening;
    }

    /**
     * Setter for tables modify
     *
     * @param string $tablesModify
     */
    public function setTablesModify($tablesModify)
    {
        $this->tablesModify = $tablesModify;
    }

    public function getTablesModify(): string
    {
        return $this->tablesModify;
    }

    /**
     * Setter for page types
     *
     * @param string $pageTypes
     */
    public function setPageTypes($pageTypes)
    {
        $this->pageTypes = $pageTypes;
    }

    public function getPageTypes(): string
    {
        return $this->pageTypes;
    }

    /**
     * Setter for allowed exclude fields
     *
     * @param string $allowedExcludeFields
     */
    public function setAllowedExcludeFields($allowedExcludeFields)
    {
        $this->allowedExcludeFields = $allowedExcludeFields;
    }

    public function getAllowedExcludeFields(): string
    {
        return $this->allowedExcludeFields;
    }

    /**
     * Setter for explicitly allow and deny
     *
     * @param string $explicitlyAllowAndDeny
     */
    public function setExplicitlyAllowAndDeny($explicitlyAllowAndDeny)
    {
        $this->explicitlyAllowAndDeny = $explicitlyAllowAndDeny;
    }

    public function getExplicitlyAllowAndDeny(): string
    {
        return $this->explicitlyAllowAndDeny;
    }

    /**
     * Setter for allowed languages
     *
     * @param string $allowedLanguages
     */
    public function setAllowedLanguages($allowedLanguages)
    {
        $this->allowedLanguages = $allowedLanguages;
    }

    public function getAllowedLanguages(): string
    {
        return $this->allowedLanguages;
    }

    /**
     * Setter for workspace permission
     *
     * @param bool $workspacePermission
     */
    public function setWorkspacePermissions($workspacePermission)
    {
        $this->workspacePermission = $workspacePermission;
    }

    /**
     * Getter for workspace permission
     *
     * @return bool
     */
    public function getWorkspacePermission(): bool
    {
        return $this->workspacePermission;
    }

    /**
     * Setter for database mounts
     *
     * @param string $databaseMounts
     */
    public function setDatabaseMounts($databaseMounts)
    {
        $this->databaseMounts = $databaseMounts;
    }

    public function getDatabaseMounts(): string
    {
        return $this->databaseMounts;
    }

    /**
     * Getter for file operation permissions
     *
     * @param int $fileOperationPermissions
     */
    public function setFileOperationPermissions($fileOperationPermissions)
    {
        $this->fileOperationPermissions = $fileOperationPermissions;
    }

    /**
     * Getter for file operation permissions
     *
     * @return int
     */
    public function getFileOperationPermissions(): int
    {
        return $this->fileOperationPermissions;
    }

    /**
     * Check if file operations like upload, copy, move, delete, rename, new and
     * edit files is allowed.
     *
     * @return bool
     */
    public function isFileOperationAllowed(): bool
    {
        return $this->isPermissionSet(self::FILE_OPPERATIONS);
    }

    /**
     * Set the the bit for file operations are allowed.
     *
     * @param bool $value
     */
    public function setFileOperationAllowed($value)
    {
        $this->setPermission(self::FILE_OPPERATIONS, $value);
    }

    /**
     * Check if folder operations like move, delete, rename, and new are allowed.
     *
     * @return bool
     */
    public function isDirectoryOperationAllowed(): bool
    {
        return $this->isPermissionSet(self::DIRECTORY_OPPERATIONS);
    }

    /**
     * Set the the bit for directory operations are allowed.
     *
     * @param bool $value
     */
    public function setDirectoryOperationAllowed($value)
    {
        $this->setPermission(self::DIRECTORY_OPPERATIONS, $value);
    }

    /**
     * Check if it is allowed to copy folders.
     *
     * @return bool
     */
    public function isDirectoryCopyAllowed(): bool
    {
        return $this->isPermissionSet(self::DIRECTORY_COPY);
    }

    /**
     * Set the the bit for copy directories.
     *
     * @param bool $value
     */
    public function setDirectoryCopyAllowed($value)
    {
        $this->setPermission(self::DIRECTORY_COPY, $value);
    }

    /**
     * Check if it is allowed to remove folders recursively.
     *
     * @return bool
     */
    public function isDirectoryRemoveRecursivelyAllowed(): bool
    {
        return $this->isPermissionSet(self::DIRECTORY_REMOVE_RECURSIVELY);
    }

    /**
     * Set the the bit for remove directories recursively.
     *
     * @param bool $value
     */
    public function setDirectoryRemoveRecursivelyAllowed($value)
    {
        $this->setPermission(self::DIRECTORY_REMOVE_RECURSIVELY, $value);
    }

    /**
     * Setter for ts config
     *
     * @param string $tsConfig
     */
    public function setTsConfig($tsConfig)
    {
        $this->tsConfig = $tsConfig;
    }

    public function getTsConfig(): string
    {
        return $this->tsConfig;
    }

    /**
     * Helper method for checking the permissions bitwise.
     *
     * @param int $permission
     * @return bool
     */
    protected function isPermissionSet($permission): bool
    {
        return ($this->fileOperationPermissions & $permission) == $permission;
    }

    /**
     * Helper method for setting permissions bitwise.
     *
     * @param int $permission
     * @param bool $value
     */
    protected function setPermission($permission, $value)
    {
        if ($value) {
            $this->fileOperationPermissions |= $permission;
        } else {
            $this->fileOperationPermissions &= ~$permission;
        }
    }

    /**
     * @return ObjectStorage
     */
    public function getCategoryPerms(): ObjectStorage
    {
        return $this->categoryPerms;
    }
}
