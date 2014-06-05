<?php
require 'app/models/Accounts.php';

/**
 * AccountsTest class
 * @package models
 * @author Mahendra Rai
 */

class AccountsTest extends \PHPUnit_Framework_TestCase {
    private $fixture;

    public function setUp() {
        $this->fixture = array(
            'Twitter' => array(
                'id' => 1,
                'account' => 'Twitter',
                'api_key' => '',
                'api_secret' => '',
                'oauth_token' => '',
                'oauth_token_secret' => ''
            ),
            'Scoopit' => array(
                'id' => 2,
                'account' => 'Scoopit',
                'api_key' => '',
                'api_secret' => '',
                'oauth_token' => '',
                'oauth_token_secret' => ''
            )
        );
    }

    public static function setUpBeforeClass() {
        $pdo = new PDO("sqlite:test.db");
        Phactory::setConnection($pdo);

        $pdo->exec("
            CREATE TABLE `sm_accounts` (
                id INTEGER PRIMARY KEY,
                account VARCHAR(50),
                api_key VARCHAR(500),
                api_secret VARCHAR(500),
                oauth_token VARCHAR(500),
                oauth_token_secret VARCHAR(500))
        ");
    }

    public function testGetAllApiAccounts() {
        $mock = $this->getMock('Accounts', array('getAllApiAccounts'));

        $mock->expects($this->any())
             ->method('getAllApiAccounts')
             ->will($this->returnValue($this->fixture));

        $this->assertTrue(!empty($this->fixture));
        $this->assertFalse(empty($this->fixture));
        $this->assertSame($this->fixture, $mock->getAllApiAccounts());

        $account = new Accounts();
        $data = $account->getAllApiAccounts();

        $this->assertEquals(3, sizeof($data));
    }

    public function testGetApiAccount() {
        $param = 'Scoopit';

        $mock = $this->getMock('Accounts', array('getApiAccount'));

        $mock->expects($this->any())
             ->method('getApiAccount')
             ->with($param)
             ->will($this->returnValue($this->fixture[$param]));

        $this->assertSame($this->fixture[$param], $mock->getApiAccount($param));
        
        $account = new Accounts();
        $data = $account->getApiAccount($param);

        $this->assertEquals(3, $data['id']);
    }

    public function testSaveAccountDetails() {

    }
}