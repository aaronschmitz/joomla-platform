<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Oauth
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Joomla Platform class for interacting with an OAuth 2.0 server.
 *
 * @package     Joomla.Platform
 * @subpackage  Oauth
 * @since       1234
 */
class JOauth2client
{
	/**
	 * @var    JRegistry  Options for the OAuth2Client object.
	 * @since  1234
	 */
	protected $options;

	/**
	 * @var    JHttp  The HTTP client object to use in sending HTTP requests.
	 * @since  1234
	 */
	protected $client;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry  $options  OAuth2Client options object.
	 * @param   JHttp      $client   The HTTP client object.
	 *
	 * @since   1234
	 */
	public function __construct(JRegistry $options = null, JHttp $client = null)
	{
		$this->options = isset($options) ? $options : new JRegistry;
		$this->client  = isset($client) ? $client : new JHttp($this->options);
	}

	/**
	 * Get the access token or redict to the authentication URL.
	 *
	 * @return  string  The access token.
	 *
	 * @since   1234
	 */
	public function auth()
	{
		if (array_key_exists($_GET, 'code'))
		{
			$data['grant_type'] = 'authorization_code';
			$data['redirect_uri'] = getOption('redirect');
			$data['client_id'] = getOption('clientid');
			$data['client_secret'] = getOption('clientsecret');
			$data['code'] = $_GET['code'];
			$response = $this->client->post($this->getOption('redirect'), $data);

			if ($response->code == 200)
			{
				$this->setToken($response->body);
				$this->setOption('accesstokentime', time());
				return $this->getToken();
			}
			else
			{
			// Exception
			}
		}

		header('Location: ' . $this->createUrl($scope));
		return false;
	}

	/**
	 * Create the URL for authentication.
	 *
	 * @return  string  The URL.
	 *
	 * @since   1234
	 */
	public function createUrl()
	{
		$url = $this->getOption('authurl');
		if (!$url)
		{
			return false;
		}
		if (!($this->getOption('redirect') && $this->getOption('clientid') && $this->getOption('scope') && $this->getOption('accesstype') && $this->getOption('prompt')))
		{
			return false;
		}
		$url .= '?response_type=code';
		$url .= '&redircet_uri=' . urlencode(getOption('redirect'));
		$url .= '&client_id=' . urlencode(getOption('clientid'));
		$url .= '&scope=' . urlencode(getOption('scope'));
		$url .= 'access_type' . urlencode(getOption('accesstype'));
		$url .= 'approval_prompt' . urlencode(getOption('prompt'));

		return $url;
	}

	/**
	 * Get an option from the JOauth2client instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1234
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the JOauth2client instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  JOauth2client  This object for method chaining.
	 *
	 * @since   1234
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);
		return $this;
	}

	/**
	 * Get the access token from the JOauth2client instance.
	 *
	 * @return  string  The option value.
	 *
	 * @since   1234
	 */
	public function getToken()
	{
		return $this->options->get('accesstoken');
	}

	/**
	 * Set an option for the JOauth2client instance.
	 *
	 * @param   string  $value  The option value to set.
	 *
	 * @return  JOauth2client  This object for method chaining.
	 *
	 * @since   1234
	 */
	public function setToken($value)
	{
		$this->options->set('accesstoken', $value);
		return $this;
	}
}
