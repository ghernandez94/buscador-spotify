<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('HttpSocket', 'Network/Http');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $token;
   
    public function AuthSpotify($model, $forzado = false){
        $result = new \stdClass();
        
        try{
            if($forzado || !isset($_COOKIE['spToken'])){
                $this->Http = new HttpSocket();
    
                $authInfo = array();
                $authInfo['client_key'] = Configure::read('client.key');
    
                $this->Http->configAuth('Basic', $authInfo);
                
                $queryData['conditions']['grant_type'] = 'client_credentials';
                $response = $this->Http->post(
                    'https://accounts.spotify.com/api/token',
                    $queryData['conditions']
                );
    
                $response = json_decode($response, true);

                if(!isset($response['access_token'])){
                    
                    $result->success = false;
                    //$result->error = $response;
                    $result->error = isset($response['error']) ? $response['error'] : 'unknown error';
                    $result->error_description = isset($response['error_description']) ? $response['error_description'] : '';

                    return $result;
                }else{
                    $token = $response['access_token'];
                    setcookie('spToken', $token, time()+3600);
                }
                
            }else{
                $token = $_COOKIE["spToken"];
            }
            
            $model->token = $token;

            $result->success = true;
            $result->token = $token;
            return $result;

        }catch(Exception $ex){
            $result->success = false;
            $result->error = $ex->getMessage();
            $result->error_description = $ex->getTraceAsString();

            return $result;
        }

        
    }
}
