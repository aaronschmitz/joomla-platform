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
 * @since       11.3
 */
class JOauth
{
	/**
	 * @var    JRegistry  Options for the OAuth2Client object.
	 * @since  11.3
	 */
	protected $options;

	/**
	 * @var    JOAuthHttp  The HTTP client object to use in sending HTTP requests.
	 * @since  11.3
	 */
	protected $client;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry    $options  OAuth2Client options object.
	 * @param   JHttp  $client   The HTTP client object.
	 *
	 * @since   11.3
	 */
	public function __construct(JRegistry $options = null, JHttp $client = null)
	{
		$this->options = isset($options) ? $options : new JRegistry;
		$this->client  = isset($client) ? $client : new JHttp($this->options);
	}

    protected function __getSession($token = NULL)
    {
    
    protected function decodeToken($token)
    {
        
    }
    
    protected function checkSession($name)
    {
        $session = &JSession::getInstance();
        if ($session->has($name))
        {
            $data = 
        }
    }
    
    protected function sign($data, $key)
    {
        $str = '';
        foreach ($data as $key => $value)
        {
            $str .= $key . '=' . $value;
        }
        return md5($str . $key);
    }
    
    protected function request($method = 'GET', $url, $data = array())
    {
        if ($method=='GET')
        {
            $this->client->get($url . '?' . http_build_query($data, NULL, '&'));
        }
        else if ($method=='POST')
        {
            $this->client->post($url, $data);
        }
    }

	/**
	 * Get an option from the JOauth instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   11.3
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the JOauth instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  JGitHub  This object for method chaining.
	 *
	 * @since   11.3
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
