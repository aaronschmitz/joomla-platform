<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Client
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/google/google.php';

/**
 * Test class for JGoogle.
 */
class JGoogleTest extends TestCase
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
	 * @var    JGoogle  Object under test.
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
		$this->object = new JGoogle($this->options, $this->client);
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
	 * Tests the magic __get method - data
	 * @since  1234
	 */
	public function test__GetData()
	{
		$this->assertThat(
			$this->object->data('Picasa'),
			$this->isInstanceOf('JGoogleDataPicasa')
		);
	}

	/**
	 * Tests the magic __get method - embed
	 * @since  1234
	 */
	public function test__GetEmbed()
	{
		$this->assertThat(
			$this->object->embed('Maps'),
			$this->isInstanceOf('JGoogleEmbedMaps')
		);
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
