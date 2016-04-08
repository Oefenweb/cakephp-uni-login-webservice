<?php
App::uses('SoapSource', 'UniLoginWebservice.Model/Datasource');

/**
 * Test Soap Source class.
 *
 */
class TestSoapSource extends SoapSource {


/**
 * Test double of `parent::_parseConfig`.
 *
 * @return bool|array
 */
	// @codingStandardsIgnoreStart
	public function _parseConfig() {
	// @codingStandardsIgnoreEnd
		return parent::_parseConfig();
	}

}

/**
 * Soap Source Test class.
 *
 */
class SoapSourceTest extends CakeTestCase {

/**
 * testParseConfigNoConfig method.
 *
 * @return void
 */
	public function testParseConfigNoConfig() {
		$expected = ['trace' => true];

		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestSoapSource')->disableOriginalConstructor()->getMock();

		// Now call _parseConfig
		$reflectedClass = new ReflectionClass('TestSoapSource');
		$parseConfig = $reflectedClass->getMethod('_parseConfig');
		$result = $parseConfig->invoke($Source);

		$this->assertEquals($expected, $result);
	}

/**
 * testParseConfigWithConfig method.
 *
 * @return void
 */
	public function testParseConfigWithConfig() {
		$expected = [
			'trace' => true,
			'location' => 'http://www.example.org/location',
			'uri' => 'http://www.example.org/uri',
			'login' => 'username',
			'password' => 'welcome123',
			'authentication' => 'simple'
		];

		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('TestSoapSource')->disableOriginalConstructor()->getMock();
		$Source->config = $expected;

		// Now call _parseConfig
		$reflectedClass = new ReflectionClass('TestSoapSource');
		$parseConfig = $reflectedClass->getMethod('_parseConfig');
		$result = $parseConfig->invoke($Source);

		$this->assertEquals($expected, $result);
	}

/**
 * testConstructConnectFailed method.
 *
 * @return void
 */
	public function testConstructConnectFailed() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();

		// Set expectations for constructor calls
		$Source->expects($this->once())->method('connect')->will($this->returnValue(false));

		// Now call the constructor
		$reflectedClass = new ReflectionClass('SoapSource');
		$constructor = $reflectedClass->getConstructor();
		$constructor->invoke($Source);

		$this->assertFalse($Source->connected);
	}

/**
 * testConstructConnectSucceeded method.
 *
 * @return void
 */
	public function testConstructConnectSucceeded() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();

		// Set expectations for constructor calls
		$Source->expects($this->once())->method('connect')->will($this->returnValue(true));

		// Now call the constructor
		$reflectedClass = new ReflectionClass('SoapSource');
		$constructor = $reflectedClass->getConstructor();
		$constructor->invoke($Source);

		$this->assertTrue($Source->connected);
	}

/**
 * testConnectNoConfig method.
 *
 * @return void
 */
	public function testConnectNoConfig() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();

		// Now call connect
		$reflectedClass = new ReflectionClass('SoapSource');
		$connect = $reflectedClass->getMethod('connect');
		$result = $connect->invoke($Source);

		$this->assertFalse($result);
	}

/**
 * testClose method.
 *
 * @return void
 */
	public function testClose() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();

		// Now call close
		$reflectedClass = new ReflectionClass('SoapSource');
		$close = $reflectedClass->getMethod('close');
		$result = $close->invoke($Source);

		$this->assertTrue($result);
		$this->assertNull($Source->client);
		$this->assertFalse($Source->connected);
	}

/**
 * testListSources method.
 *
 * @return void
 */
	public function testListSources() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();
		$Source->client = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();

		$Source->client->expects($this->once())->method('__getFunctions')->will($this->returnValue([]));
		$Source->expects($this->never())->method('connect');

		// Now call listSources
		$reflectedClass = new ReflectionClass('SoapSource');
		$listSources = $reflectedClass->getMethod('listSources');
		$result = $listSources->invoke($Source);

		$this->assertEquals([], $result);
	}

/**
 * testQueryNotConnected method.
 *
 * @return void
 */
	public function testQueryNotConnected() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();

		// Now call query
		$reflectedClass = new ReflectionClass('SoapSource');
		$query = $reflectedClass->getMethod('query');
		$result = $query->invoke($Source, 'test');

		$this->assertFalse($result);
	}

/**
 * testQueryConnected method.
 *
 * @return void
 */
	public function testQueryConnected() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();
		$Source->client = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();
		$Source->connected = true;

		$expected = [345];
		$method = 'test';
		$params = 7;
		$Source->client->expects($this->once())->method('__soapCall')->with($method, [$params])
			->will($this->returnValue($expected));
		$Source->expects($this->never())->method('connect');

		// Now call query
		$reflectedClass = new ReflectionClass('SoapSource');
		$query = $reflectedClass->getMethod('query');
		$result = $query->invoke($Source, $method, $params);

		$this->assertEquals($expected, $result);
	}

/**
 * testQuerySoapFault method.
 *
 * @return void
 */
	public function testQuerySoapFault() {
		// Get mock, without the constructor being called
		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();
		$Source->client = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();
		$Source->connected = true;

		$method = 'test';
		$params = 7;
		$Source->client->expects($this->once())->method('__soapCall')->with($method, [$params])
			->will($this->throwException(new SoapFault('1', 'Error message!')));
		$Source->expects($this->never())->method('connect');

		// Now call query
		$reflectedClass = new ReflectionClass('SoapSource');
		$query = $reflectedClass->getMethod('query');
		$result = $query->invoke($Source, $method, $params);

		$this->assertFalse($result);
	}

/**
 * testGetResponse method.
 *
 * @return void
 */
	public function testGetResponse() {
		$expected = 'response';

		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();
		$Source->client = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();

		$Source->client->expects($this->once())->method('__getLastResponse')->will($this->returnValue($expected));

		// Now call getResponse
		$reflectedClass = new ReflectionClass('SoapSource');
		$getResponse = $reflectedClass->getMethod('getResponse');
		$result = $getResponse->invoke($Source);

		$this->assertEquals($expected, $result);
	}

/**
 * testGetRequest method.
 *
 * @return void
 */
	public function testGetRequest() {
		$expected = 'request';

		$Source = $this->getMockBuilder('SoapSource')->disableOriginalConstructor()->getMock();
		$Source->client = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();

		$Source->client->expects($this->once())->method('__getLastRequest')->will($this->returnValue($expected));

		// Now call getRequest
		$reflectedClass = new ReflectionClass('SoapSource');
		$getRequest = $reflectedClass->getMethod('getRequest');
		$result = $getRequest->invoke($Source);

		$this->assertEquals($expected, $result);
	}

}
