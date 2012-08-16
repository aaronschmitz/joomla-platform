<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Google
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
jimport('joomla.oauth.v2client');

/**
 * Google OAuth authentication class
 *
 * @package     Joomla.Platform
 * @subpackage  Google
 * @since       12.2
 */
class JGoogleAuthOauth2 extends JGoogleAuth
{
	/**
	 * @var    JOauthV2client  OAuth client for the Google authentication object.
	 * @since  12.2
	 */
	protected $client;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry       $options  JGoogleAuth options object.
	 * @param   JOauthV2client  $client   OAuth client for Google authentication.
	 *
	 * @since   12.2
	 */
	public function __construct(JRegistry $options = null, JOauthV2client $client = null)
	{
		$this->options = isset($options) ? $options : new JRegistry;
		$this->client = isset($client) ? $client : new JOauthV2client($this->options);
	}

	/**
	 * Method to authenticate to Google
	 *
	 * @return  bool  True on success.
	 *
	 * @since   12.2
	 */
	public function auth()
	{
		$this->googlize();
		return $this->client->auth();
	}

	/**
	 * Verify if the client has been authenticated
	 *
	 * @return  bool  Is authenticated
	 *
	 * @since   12.2
	 */
	public function isAuth()
	{
		return $this->client->isAuth();
	}

	/**
	 * Method to retrieve data from Google
	 *
	 * @param   string  $url      The URL for the request.
	 * @param   mixed   $data     The data to include in the request.
	 * @param   array   $headers  The headers to send with the request.
	 * @param   string  $method   The type of http request to send.
	 *
	 * @return  mixed  Data from Google.
	 *
	 * @since   12.2
	 */
	public function query($url, $data = null, $headers = null, $method = 'get')
	{
		$this->googlize();
		return $this->client->query($url, $data, $headers, $method);
	}

	/**
	 * Method to fill in Google-specific OAuth settings
	 *
	 * @return  JOauthV2client  Google-configured Oauth2 client.
	 *
	 * @since   12.2
	 */
	protected function googlize()
	{
		if (!$this->client->getOption('authurl'))
		{
			$this->client->setOption('authurl', 'https://accounts.google.com/o/oauth2/auth');
		}
		if (!$this->client->getOption('tokenurl'))
		{
			$this->client->setOption('tokenurl', 'https://accounts.google.com/o/oauth2/token');
		}
		if (!$this->client->getOption('requestparams'))
		{
			$this->client->setOption('requestparams', Array());
		}

		$params = $this->client->getOption('requestparams');
		if (!array_key_exists('access_type', $params))
		{
			$params['access_type'] = 'offline';
		}
		if ($params['access_type'] == 'offline' && $this->client->getOption('userefresh') === null)
		{
			$this->client->setOption('userefresh', true);
		}
		if (!array_key_exists('approval_prompt', $params))
		{
			$params['approval_prompt'] = 'auto';
		}

		$this->client->setOption('requestparams', $params);

		return $this->client;
	}
}
