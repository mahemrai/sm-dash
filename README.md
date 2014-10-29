# Sm-dash

Web app for viewing feeds from social media accounts. Currently supports Twitter and Scoop.it.

Supported functionalities:

1. Post, favourite, delete and retweet tweets.
2. View scoops and subscribed topics.

## Dependencies

1. [Slim PHP framework]
2. [Twig]
3. [Slim Views]
4. [Twitter Api PHP]
5. [LESS]
6. [Zend Oauth]
7. [Slim Config Yaml]

[Slim PHP framework]:http://www.slimframework.com/
[Twig]:http://twig.sensiolabs.org/
[Twitter Api PHP]:https://github.com/J7mbo/twitter-api-php
[Slim Views]:https://github.com/codeguy/Slim-Views
[LESS]:https://github.com/oyejorge/less.php
[Zend Oauth]:https://github.com/zendframework/ZendOAuth
[Slim Config Yaml]:https://github.com/techsterx/slim-config-yaml

## Setting up project

### Clone project

Clone the project by running `git clone https://github.com/mahemrai/sm-dash.git`

### Run composer

In the project root run `sudo composer install` to install project dependencies.

### Create API accounts

Register and create API accounts for Twitter REST API and Scoop.it REST API.

### YAML config

Copy existing YAML file as app.yml and enter required api details listed in the config file.

```
application:
    twitter:
        username: YOUR-USERNAME
        api_key: YOUR-API-KEY
        api_secret: YOUR-API-SECRET
        oauth_token: YOUR-OAUTH-TOKEN
        oauth_token_secret: YOUR-OAUTH-TOKEN-SECRET
    scoopit:
        default_topic: YOUR-DEFAULT-TOPIC
        api_key: YOUR-API-KEY
        api_secret: YOUR-API-SECRET
        callback_url: YOUR-CALLBACK-URL
```
