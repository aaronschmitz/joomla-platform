<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Client
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM.'/joomla/oauth/oauth.php';

/**
 * Test class for JOauth2client.
 */
class JOauth2clientTest extends PHPUnit_Framework_TestCase
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
}
