<?php
/**
 * Accounts class
 * @package models
 * @author Mahendra Rai
 */
class Accounts {
    /**
     * Fetch all api accounts.
     * @return array
     */
    public function getAllApiAccounts() {
        $accounts = ORM::for_table('sm_accounts')
            ->find_array();

        foreach($accounts as $account) {
            $item = array('id' => $account['id']);
            $data[$account['account']] = $item;
        }

        return $data;
    }

    /**
     * Fetch api detail for the selected account.
     * @param string $account
     * @return array
     */
    public function getApiAccount($account) {
        $query = ORM::for_table('sm_accounts')
            ->where('account', $account)
            ->find_one();

        return $query;
    }

    /**
     * Save api details for the existing account or create a new account if
     * it doesn't exist.
     * @param array $data
     * @return boolean
     */
    public function saveAccountDetails($data) {
        $account = ORM::for_table('sm_accounts')
            ->where('account', $data['account'])
            ->find_one();

        if($account) {
            $account->set(array(
                'api_key' => $data['api_key'],
                'api_secret' => $data['api_secret'],
                'oauth_token' => $data['oauth_token'],
                'oauth_token_secret' => $data['oauth_token_secret']
            ));
        }
        else{
            $account = ORM::for_table('sm_accounts')->create();

            $account->account = $data['account'];
            $account->api_key = $data['api_key'];
            $account->api_secret = $data['api_secret'];
            $account->oauth_token = $data['oauth_token'];
            $account->oauth_token_secret = $data['oauth_token_secret'];
        }

        return $account->save();
    }

    /**
     * Delete api details for the existing account.
     * @param string $account
     * @return boolean
     */
    public function deleteAccountDetails($account) {
        $query = ORM::for_table('sm_accounts')
            ->where('account', $account)
            ->find_one();

        return $query->delete();
    }
}