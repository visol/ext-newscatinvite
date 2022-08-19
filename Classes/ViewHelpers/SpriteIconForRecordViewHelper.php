<?php

namespace Visol\Newscatinvite\ViewHelpers;

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
use TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Views sprite icon for a record (object)
 * Credits: typo3/sysext/beuser/Classes/ViewHelpers/SpriteIconForRecordViewHelper.php
 *
 * @author Felix Kopp <felix-source@phorax.com>
 */
class SpriteIconForRecordViewHelper extends AbstractBackendViewHelper
{

    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Displays spriteIcon for database table and object
     *
     *
     * @return string
     * @see \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord($table, $row)
     */
    public function render()
    {
        $table = $this->arguments['table'];
        $object = $this->arguments['object'];
        if (!is_object($object) || !method_exists($object, 'getUid')) {
            return '';
        }
        $row = [
            'uid' => $object->getUid(),
            'startTime' => false,
            'endTime' => false
        ];
        if (method_exists($object, 'getHidden')) {
            $row['hidden'] = $object->getHidden();
        }
        /** @var IconFactory $iconFactory */
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        return $iconFactory->getIconForRecord($table, $row, Icon::SIZE_SMALL)->render();
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('table', 'string', '', true);
        $this->registerArgument('object', 'object', '', true);
    }

}
