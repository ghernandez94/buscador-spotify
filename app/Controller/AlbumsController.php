<?php
App::uses('AppController', 'Controller');

class AlbumsController extends AppController {
    public $helpers = array('Html','Form');

    function buscar($idAlbum){
        $response_auth = $this->AuthSpotify($this->Album);
            
        if($response_auth->success){
            $response_search = $this->Album
                    ->find('all', array('conditions' => array('idAlbum' => $idAlbum)));
            echo json_encode($response_search);
        }else{
            echo json_encode($response_auth);
        }
        die();
    }
}
?>