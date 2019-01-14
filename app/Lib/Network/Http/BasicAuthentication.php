<?php

class BasicAuthentication {

    /**
     * Authentication
     *
     * @param HttpSocket $http
     * @param array $authInfo
     * @return void
     */
        public static function authentication(HttpSocket $http, &$authInfo) {
            $authorization = $authInfo['client_key'];
            $http->request['header']['Authorization'] = 'Basic '.$authorization;
        }
    }
?>