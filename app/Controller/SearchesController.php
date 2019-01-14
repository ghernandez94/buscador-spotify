<?php
App::uses('AppController', 'Controller');

class SearchesController extends AppController {
    public $helpers = array('Html','Form');

    function index() {
    }

    function buscarTodo(){
        $textoBuscado = $this->params['url']['textoBuscado'];
        $limit = 5;
        $offset = 0;

        $response_auth = $this->AuthSpotify($this->Search);
            
        if($response_auth->success){
            $response_search = $this->Search
                    ->find('all', array('conditions' => array('q' => $textoBuscado,
                                                            'type' => 'track,artist,album',
                                                            'limit' => $limit,
                                                            'offset' => $offset)));
            echo json_encode($response_search);
        }else{
            echo json_encode($response_auth);
        }
        die();
    }

    function buscar(){
        $textoBuscado = $this->params['url']['textoBuscado'];
        $tipo = $this->params['url']['tipo'];
        $limit = 5;
        $offset = isset($this->params['url']['offset']) ? $this->params['url']['offset'] : 0 ;

        $response_auth = $this->AuthSpotify($this->Search);
            
        if($response_auth->success){
            $response_search = $this->Search
                    ->find('all', array('conditions' => array('q' => $textoBuscado,
                                                            'type' => $tipo,
                                                            'limit' => $limit,
                                                            'offset' => $offset)));
            echo json_encode($response_search);
        }else{
            echo json_encode($response_auth);
        }
        die();
    }
}
?>