<?php
App::uses('AppController', 'Controller');

class ArtistsController extends AppController {
    public $helpers = array('Html','Form');

    function buscar($idArtist){
        $response_auth = $this->AuthSpotify($this->Artist);
            
        if($response_auth->success){
            $response_search = $this->Artist
                    ->find('all', array('conditions' => array('idArtist' => $idArtist)));
            echo json_encode($response_search);
        }else{
            echo json_encode($response_auth);
        }
        die();
    }
}
?>