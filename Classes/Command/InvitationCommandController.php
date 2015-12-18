<?php
namespace Visol\Newscatinvite\Command;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class InvitationCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \Visol\Newscatinvite\Domain\Repository\InvitationRepository
	 * @inject
	 */
	protected $invitationRepository;

	/**
	 * @var \Visol\Newscatinvite\Domain\Repository\BackendUserGroupRepository
	 * @inject
	 */
	protected $backendUserGroupRepository;

	/**
	 * @var \Visol\Newscatinvite\Domain\Repository\BackendUserRepository
	 * @inject
	 */
	protected $backendUserRepository;

	/**
	 * TypoScript setup for module.tx_newscatinvite
	 *
	 * @var array
	 */
	protected $extensionConfiguration;

	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Send pending invitations
	 *
	 * @param int $itemsPerRun
	 */
	public function sendInvitationsCommand($itemsPerRun = 20) {
		$this->initializeAction();

		$notSentInvitations = $this->invitationRepository->findPendingUnsentInvitations();
		foreach ($notSentInvitations as $invitation) {
			/** @var \Visol\Newscatinvite\Domain\Model\Invitation $invitation */
			$recipientArray = array();
			if ($invitation->getCategory() instanceof \Tx_News_Domain_Model_Category) {
				$userGroupsWithCurrentCategory = $this->backendUserGroupRepository->findByCategoryPermissions($invitation->getCategory());
				if ($userGroupsWithCurrentCategory->count()) {
					$backendUserGroupsArray = array();
					foreach ($userGroupsWithCurrentCategory as $usergroup) {
						/** @var \Visol\Newscatinvite\Domain\Model\BackendUserGroup $usergroup */
						$backendUserGroupsArray[] = $usergroup->getUid();
					}
					$backendUsers = $this->backendUserRepository->findByUsergroups($backendUserGroupsArray);
					foreach ($backendUsers as $backendUser) {
						if (!empty($backendUser['email'])) {
							$recipientArray[] = $backendUser['email'];
						}
					}
				}
			}
			if (!empty($recipientArray)) {

				$subject = 'Einladung zur Aufnahme einer News-Meldung';

				/** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
				$standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
				$standaloneView->setFormat('html');
				$templateRootPath = GeneralUtility::getFileAbsFileName($this->extensionConfiguration['view']['templateRootPath']);
				$templatePathAndFilename = $templateRootPath . 'Email/InvitationNotification.html';
				$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
				$standaloneView->assignMultiple(array(
					'invitation' => $invitation,
					'settings' => $this->extensionConfiguration['settings']
				));
				$content = $standaloneView->render();
				$sender = array('typo3@unilu.ch' => 'TYPO3 Universität Luzern');
				$replyTo = '';
				if ($invitation->getCreator()->getEmail() !== '') {
					$replyTo = $invitation->getCreator()->getEmail();
				}
				$emailIsSent = $this->sendEmail($recipientArray, $sender, $subject, $content, $replyTo);
				if ($emailIsSent) {
					$invitation->setSent(1);
					$this->invitationRepository->update($invitation);
				}
			}

		}

		$this->persistenceManager->persistAll();

	}

	/**
	 * @param array $recipient
	 * @param array $sender
	 * @param $subject
	 * @param $content
	 * @param string $replyTo
	 * @param string $returnPath
	 * @return bool
	 */
	protected function sendEmail(array $recipient, array $sender, $subject, $content, $replyTo = '', $returnPath = '') {
		/** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
		$message = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
		$message->setTo($recipient);
		$message->setFrom($sender);
		if (!empty($replyTo) && GeneralUtility::validEmail($replyTo)) {
			$message->addReplyTo($replyTo);
		}
		if (!empty($returnPath) && GeneralUtility::validEmail($returnPath)) {
			$message->setReturnPath($returnPath);
		}
		$message->setSubject($subject);
		$message->setBody($content, 'text/html');
		$message->send();
		return $message->isSent();
	}

	/**
	 * constructor
	 */
	public function initializeAction() {
		$this->extensionConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, 'newscatinvite', 'newscatinvite');
	}

}
?>