<?php

class OAuth2Authentication {

    /**
     * Authentication
     *
     * @param HttpSocket $http
     * @param array $authInfo
     * @return void
     */
        public static function authentication(HttpSocket $http, &$authInfo) {
           $http->request['header']['Authorization'] = 'Bearer '.$authInfo['token'];
        }
    }
?>