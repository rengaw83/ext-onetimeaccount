<?php
/***************************************************************
* Copyright notice
*
* (c) 2007 Oliver Klee (typo3-coding@oliverklee.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class 'tx_onetimeaccount_configcheck' for the 'ontimeaccount' extension.
 *
 * This class checks this extension's configuration for basic sanity.
 *
 * @author	Oliver Klee <typo3-coding@oliverklee.de>
 */

require_once(t3lib_extMgm::extPath('oelib').'class.tx_oelib_configcheck.php');

class tx_onetimeaccount_configcheck extends tx_oelib_configcheck {
	/**
	 * Checks the configuration for tx_onetimeaccount_pi1.
	 *
	 * @access	private
	 */
	function check_tx_onetimeaccount_pi1() {
		$this->checkCssStyledContent();
		$this->checkStaticIncluded();
		$this->checkTemplateFile(true);
		$this->checkCssFile(true);
		$this->checkSalutationMode();

		$this->checkFeUserFieldsToDisplay();
		$this->checkSystemFolderForNewFeUserRecords();
		$this->checkGroupForNewFeUsers();

		return;
	}

	/**
	 * Checks the setting of the configuration value feUserFieldsToDisplay.
	 *
	 * @access	private
	 */
	function checkFeUserFieldsToDisplay() {
		$providedFields = array(
			'company',
			'gender',
			'name',
			'first_name',
			'last_name',
			'address',
			'zip',
			'city',
			'country',
			'static_info_country',
			'email',
			'telephone',
			'fax',
			'date_of_birth',
			'status'
		);
		$fieldsFromFeUsers = $this->getDbColumnNames('fe_users');

		// Make sure that only fields are allowed that are actually available.
		// (Some fields don't come with the vanilla TYPO3 installation and are
		// provided by the sr_feusers_register extension.)
		$availableFields = array_intersect($providedFields, $fieldsFromFeUsers);

		$this->checkIfMultiInSetNotEmpty(
			'feUserFieldsToDisplay',
			true,
			's_general',
			'This value specifies which form fields will be displayed. '
				.'Incorrect values will cause those fields to not get displayed.',
			$availableFields
		);

		return;
	}

	/**
	 * Checks the setting of the configuration value
	 * systemFolderForNewFeUserRecords.
	 *
	 * @access	private
	 */
	function checkSystemFolderForNewFeUserRecords() {
		$this->checkIfSingleSysFolderNotEmpty(
			'systemFolderForNewFeUserRecords',
			true,
			's_general',
			'This value specifies the system folder in which new FE user'
				.'records will be stored.'
				.'If this value is not set correctly, the records will be '
				.'stored in the wrong page.'
		);

		return;
	}

	/**
	 * Checks the setting of the configuration value groupForNewFeUsers.
	 *
	 * @access	private
	 */
	function checkGroupForNewFeUsers() {
		$this->checkIfPositiveIntegerOrEmpty(
			'groupForNewFeUsers',
			true,
			's_general',
			'This value specifies the FE user group to which new FE user '
				.'records will be assigned. '
				.'If this value is not set correctly, the users will not be '
				.'placed in that group.'
		);

		return;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/onetimeaccount/class.tx_onetimeaccount_configcheck.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/onetimeaccount/class.tx_onetimeaccount_configcheck.php']);
}

?>
