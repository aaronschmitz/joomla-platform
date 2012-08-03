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
class JGoogleDataPlus extends JGoogleData
{
	/**
	* @var    JGoogleDataPlusPeople  Google+ API object for people.
	* @since  12.3
	*/
	protected $people;

	/**
	* @var    JGoogleDataPlusActivities  Google+ API object for people.
	* @since  12.3
	*/
	protected $activities;

	/**
	* @var    JGoogleDataPlusComments  Google+ API object for people.
	* @since  12.3
	*/
	protected $comments;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry    $options  Google options object
	 * @param   JGoogleAuth  $auth     Google data http client object
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

		// Setup the default API url if not already set.
		$options->def('api.url', 'https://www.googleapis.com/plus/v1/');

		parent::__construct($options, $auth);
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  JGoogleDataPlus  Google+ API object (people, activities, comments).
	 *
	 * @since   12.3
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'people':
				if ($this->people == null)
				{
					$this->people = new JGoogleDataPlusPeople($this->options, $this->client);
				}
				return $this->people;

			case 'activities':
				if ($this->activities == null)
				{
					$this->activities = new JGoogleDataPlusActivities($this->options, $this->client);
				}
				return $this->activities;

			case 'comments':
				if ($this->comments == null)
				{
					$this->comments = new JGoogleDataPlusComments($this->options, $this->client);
				}
				return $this->comments;
		}
	}
}
