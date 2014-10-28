<?php
/**
 * Accounts class
 * @package models
 * @author Mahendra Rai
 */
class Accounts {
    private $config;

    public function __construct($config_array) {
        $this->config = $config_array;
    }

    /**
     * Check if the api config for each account exists in YAML config file.
     * @param string $account
     * @return boolean
     */
    public function apiConfigExists($account) {
        if (strcasecmp($this->config[$account]['api_key'], 'YOUR-API-KEY') == 0) {
            return false;
        } elseif (strcasecmp($this->config[$account]['api_secret'], 'YOUR-API-SECRET') == 0) {
            return false;
        }

        return true;
    }
}