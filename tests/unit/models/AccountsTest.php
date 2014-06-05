<?php
/**
 * AccountsTest class
 * @package models
 * @author Mahendra Rai
 */

class AccountsTest extends \PHPUnit_Framework_TestCase {
    private $fixture;

    public function setUp() {
        $this->fixture = array(
            array(
                'id' => 1,
                'account' => 'Twitter',
                'api_key' => '',
                'api_secret' => '',
                'oauth_token' => '',
                'oauth_token_secret' => ''
            ),
            array(
                'id' => 2,
                'account' => 'Scoopit',
                'api_key' => '',
                'api_secret' => '',
                'oauth_token' => '',
                'oauth_token_secret' => ''
            )
        );
    }

    public function testGetAllApiAccounts() {
        $mock = $this->getMock('Accounts', array('getAllApiAccounts'));

        $mock->expects($this->any())
             ->method('getAllApiAccounts')
             ->will($this->returnValue($this->fixture));

        $this->assertTrue(!empty($this->fixture));
        $this->assertFalse(empty($this->fixture));
        $this->assertSame($this->fixture, $mock->getAllApiAccounts());
    }

    public function testGetApiAccount() {
        $mock = $this->getMock('Accounts', array('getApiAccount'));

        $mock->expects($this->any())
             ->method('getApiAccount')
             ->will($this->returnValue($this->fixture[1]));
    }
}