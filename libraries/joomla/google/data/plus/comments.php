<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Google
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Google+ data class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Google
 * @since       1234
 */
class JGoogleDataPlusComments extends JGoogleData
{
	/**
	 * Constructor.
	 *
	 * @param   JRegistry         $options  Google options object
	 * @param   JGoogleAuth       $auth     Google data http client object
	 *
	 * @since   1234
	 */
	public function __construct(JRegistry $options = null, JGoogleAuth $auth = null)
	{
		$options = isset($options) ? $options : new JRegistry;
		if (!$options->get('scope'))
		{
			$options->set('scope', 'https://www.googleapis.com/auth/plus.me');
		}
		if (isset($auth) && !$auth->getOption('scope'))
		{
			$auth->setOption('scope', 'https://www.googleapis.com/auth/plus.me');
		}

		parent::__construct($options, $auth);
	}
}
