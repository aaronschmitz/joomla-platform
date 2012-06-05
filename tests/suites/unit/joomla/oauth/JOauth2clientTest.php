<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Client
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/oauth/oauth2.php';

/**
 * Test class for JOauth2client.
 */
class JOauth2clientTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the JOauth2client object.
	 * @since  1234
	 */
	protected $options;

	/**
	 * @var    JHttp  Mock client object.
	 * @since  1234
	 */
	protected $client;

	/**
	 * @var    JInput  The input object to use in retrieving GET/POST data.
	 * @since  1234
	 */
	protected $input;

	/**
	 * @var    JOauth2client  Object under test.
	 * @since  1234
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->options = new JRegistry;
		$this->client = $this->getMock('JHttp', array('post'));
		$this->input = new JInput;
		$this->object = new JOauth2client($this->options, $this->client, $this->input);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the auth method
	 * @since  1234
	 */
	public function testAuth()
	{
		$this->object->setOption('authurl', 'https://accounts.google.com/o/oauth2/auth');
		$this->object->setOption('clientid', '01234567891011.apps.googleusercontent.com');
		$this->object->setOption('scope', array('https://www.googleapis.com/auth/adsense', 'https://www.googleapis.com/auth/calendar'));
		$this->object->setOption('redirecturi', 'http://localhost/oauth');
		$this->object->setOption('requestparams', array('access_type' => 'offline', 'approval_prompt' => 'auto'));

		$this->object->auth();
		$headers = JResponse::getHeaders();
		$location = false;
		foreach ($headers as $header)
		{
			if ($header['name'] == 'Location')
			{
				$location = true;
				$this->assertEquals($this->object->createUrl(), $header['value']);
			}
		}
		$this->assertEquals(true, $location);

		$this->object->setOption('tokenurl', 'https://accounts.google.com/o/oauth2/token');
		$this->object->setOption('clientsecret', 'jeDs8rKw_jDJW8MMf-ff8ejs');
		$this->input->set('code', '4/wEr_dK8SDkjfpwmc98KejfiwJP-f4wm.kdowmnr82jvmeisjw94mKFIJE48mcEM');
		$this->client->expects($this->once())->method('post')->will($this->returnCallback('httpTokenCallback'));
		$result = $this->object->auth();
		$this->assertEquals('accessvalue', $result['access_token']);
		$this->assertEquals('refreshvalue', $result['refresh_token']);
		$this->assertEquals(3600, $result['expires_in']);
		$this->assertEquals(time(), $result['created'], 10);
	}

	/**
	 * Tests the auth method
	 * @since  1234
	 */
	public function testCreateUrl()
	{
		$this->object->setOption('authurl', 'https://accounts.google.com/o/oauth2/auth');
		$this->object->setOption('clientid', '01234567891011.apps.googleusercontent.com');
		$this->object->setOption('scope', array('https://www.googleapis.com/auth/adsense', 'https://www.googleapis.com/auth/calendar'));
		$this->object->setOption('redirecturi', 'http://localhost/oauth');
		$this->object->setOption('requestparams', array('access_type' => 'offline', 'approval_prompt' => 'auto'));

		$url = $this->object->createUrl();
		$expected = 'https://accounts.google.com/o/oauth2/auth?response_type=code';
		$expected .= '&client_id=01234567891011.apps.googleusercontent.com';
		$expected .= '&redirect_uri=http%3A%2F%2Flocalhost%2Foauth';
		$expected .= '&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fadsense';
		$expected .= '+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fcalendar';
		$expected .= '&access_type=offline&approval_prompt=auto';
		$this->assertEquals($expected, $url);
	}

	/**
	 * Tests the auth method
	 * @since  1234
	 */
	public function testQuery()
	{
		$token['access_token'] = 'accessvalue';
		$token['refresh_token'] = 'refreshvalue';
		$token['created'] = time() - 3600;
		$token['expires_in'] = 3600;
		$this->object->setToken($token);

		$this->client->expects($this->atLeastOnce())->method('post')->will($this->returnCallback('httpQueryCallback'));
		$result = $this->object->query('https://www.googleapis.com/auth/calendar', array('param' => 'value'));
		$this->assertEquals('Lorem ipsum dolor sit amet.', $result->body);
		$this->assertEquals(200, $result->code);
	}

	/**
	 * Tests the setOption method
	 * @since  1234
	 */
	public function testSetOption()
	{
		$this->object->setOption('key', 'value');

		$this->assertThat(
			$this->options->get('key'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the getOption method
	 * @since  1234
	 */
	public function testGetOption()
	{
		$this->options->set('key', 'value');

		$this->assertThat(
			$this->object->getOption('key'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the setToken method
	 * @since  1234
	 */
	public function testSetToken()
	{
		$this->object->setToken('value');

		$this->assertThat(
			$this->options->get('accesstoken'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the getToken method
	 * @since  1234
	 */
	public function testGetToken()
	{
		$this->options->set('accesstoken', 'value');

		$this->assertThat(
			$this->object->getToken(),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the refreshToken method
	 * @since  1234
	 */
	public function testRefreshToken()
	{
		$this->object->setOption('tokenurl', 'https://accounts.google.com/o/oauth2/token');
		$this->object->setOption('clientid', '01234567891011.apps.googleusercontent.com');
		$this->object->setOption('clientsecret', 'jeDs8rKw_jDJW8MMf-ff8ejs');
		$this->object->setOption('redirecturi', 'http://localhost/oauth');

		$this->client->expects($this->once())->method('post')->will($this->returnCallback('httpTokenCallback'));
		$result = $this->object->refreshToken();
		$this->assertEquals('accessvalue', $result['access_token']);
		$this->assertEquals('refreshvalue', $result['refresh_token']);
		$this->assertEquals(3600, $result['expires_in']);
		$this->assertEquals(time(), $result['created'], 10);
	}
}

/**
 * Callback for the use of JHttp to return a token
 * @since  1234
 */
function httpTokenCallback($url, $data)
{
	$response->code = 200;
	$response->body = '{"access_token":"accessvalue","refresh_token":"refreshvalue","expires_in":3600}';
	return $response;
}

/**
 * Callback for the use of JHttp to return a response to an OAuth request
 * @since  1234
 */
function httpQueryCallback($url, $data)
{
	if (isset($data['grant_type']) && $data['grant_type'] == 'refresh_token')
	{
		return httpTokenCallback($url, $data);
	}
	$response->code = 200;
	$response->body = 'Lorem ipsum dolor sit amet.';
	return $response;
}
