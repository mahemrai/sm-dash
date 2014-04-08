<?php
/**
 * Accounts class
 * @package models
 * @author Mahendra Rai
 */
class Accounts {
    /**
     * Description
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

    public function getApiAccount($account) {
        $query = ORM::for_table('sm_accounts')
            ->where('account', $account)
            ->find_one()
            ->as_array();

        return $query;
    }

    public function saveAccountDetails($data) {
        $account = ORM::for_table('sm_accounts')
            ->where('account', $data['name'])
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

            $account->account = $data['name'];
            $account->api_key = $data['api_key'];
            $account->api_secret = $data['api_secret'];
            $account->oauth_token = $data['oauth_token'];
            $account->oauth_token_secret = $data['oauth_token_secret'];
        }

        return $account->save();
    }
}