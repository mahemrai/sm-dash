<?php
class Accounts {
    public function getAllApiAccounts() {
        $query = ORM::for_table('sm_accounts')
            ->find_array();

        return $query;
    }

    public function getApiAccount($id) {
        $query = ORM::for_table('sm_accounts')->find_one($id)->as_array();

        return $query;
    }

    public function saveAccountDetails($data) {
        $account = ORM::for_table('sm_accounts')->find_one($id);

        if($account != null) {
            $account->set(array(
                'api_key' => $data['api_key'],
                'api_secret' => $data['api_secret'],
                'oauth_token' => $data['oauth_token'],
                'oauth_token_secret' => $data['oauth_token_secret']
            ));
        }
        else{
            $account = ORM::for_table('sm_accounts')->create();

            $account->api_key = $data['api_key'];
            $account->api_secret = $data['api_secret'];
            $account->oauth_token = $data['oauth_token'];
            $account->oauth_token_secret = $data['oauth_token_secret'];
        }

        $account->save();
    }
}