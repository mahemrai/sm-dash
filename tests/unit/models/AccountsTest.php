<?php
require 'app/models/Accounts.php';

/**
 * AccountsTest class
 * @package models
 * @author Mahendra Rai
 */

class AccountsTest extends \PHPUnit_Framework_TestCase {
    private $insert_data;
    private $update_data;
    private $accounts;

    public function setUp() {
        $this->accounts = new Accounts();

        $this->insert_data = array(
            'account' => 'Linkedin',
            'api_key' => '',
            'api_secret' => '',
            'oauth_token' => '',
            'oauth_token_secret' => ''
        );

        $this->update_data = array(
            'account' => 'Linkedin',
            'api_key' => 'iopop',
            'api_secret' => '',
            'oauth_token' => '',
            'oauth_token_secret' => ''
        );
    }

    /**
     * Test all accounts information in the database is retrieved.
     */
    public function testGetAllApiAccounts() {
        $data = $this->accounts->getAllApiAccounts();

        $this->assertFalse(empty($data));
        $this->assertEquals(2, sizeof($data));
    }

    /**
     * Test retrieval of existing account details.
     */
    public function testGetApiAccountSuccess() {
        $param = 'Scoopit';

        $data = $this->accounts->getApiAccount($param);

        $this->assertEquals($param, $data['account']);
    }

    /**
     * Test failure of retrieving non-existant account details.
     */
    public function testGetApiAccountFail() {
        $param = 'Linkedin';

        $data = $this->accounts->getApiAccount($param);

        $this->assertEmpty($data);
    }

    /**
     * Test for saving new account details.
     */
    public function testSaveAccountDetailsInsert() {
        $result = $this->accounts->saveAccountDetails($this->insert_data);
        $this->assertTrue($result);
    }

    /**
     * Test for updating existing account details.
     */
    public function testSaveAccountDetailsUpdate() {
        $param = 'Linkedin';

        $result = $this->accounts->saveAccountDetails($this->update_data);
        $this->assertTrue($result);

        $updated = $this->accounts->getApiAccount($param);
        $this->assertEquals($this->update_data['api_key'], $updated->get('api_key'));
    }

    /**
     * Test for deleting existing account details.
     */
    public function testDeleteAccountDetails() {
        $param = 'Linkedin';

        $result = $this->accounts->deleteAccountDetails($param);

        $this->assertTrue($result);
    }
}