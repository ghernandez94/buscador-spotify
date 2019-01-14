<?php
App::uses('HttpSocket', 'Network/Http');

class ArtistSource extends DataSource {
    public $description = '';

    public $config = array(
        'apiKey' => ''
    );

    public function __construct($config) {
        parent::__construct($config);
    }

    public function listSources($data = null) {
        return null;
    }

    public function calculate(Model $model, $func, $params = array()) {
        return 'COUNT';
    }

    public function read(Model $model, $queryData = array(), $recursive = null) {
        
        if ($queryData['fields'] === 'COUNT') {
            return array(array(array('count' => 1)));
        }

        $result = new \stdClass();

        try{
        
            $this->Http = new HttpSocket();
            $this->Http->configAuth('OAuth2', array('token' => $model->token));
    
            $queryData['conditions']['market'] = 'CL';
            $queryData['conditions']['include_groups'] = 'album,single';

            $idArtist = $queryData['conditions']['idArtist'];
            unset($queryData['conditions']['idArtist']);

            $json = $this->Http->get(
                'https://api.spotify.com/v1/artists/'.$idArtist.'/albums',
                $queryData['conditions']
            );
            $res = json_decode($json, true);
            if (is_null($res)) {
                $error = json_last_error();
                throw new CakeException($error);
            }

            if(!isset($res['items']) ){
                $result->success = false;
                $result->error = $res;
                return $result;
            }

            $result->success = true;
            $result->data = $res;

            return $result;
        }catch(Exception $ex){
            $result->success = false;
            $result->error = $ex->getMessage();
            $result->error_description = 'Error on line '.$ex->getLine().' in '.$ex->getFile();
            return $result;
        }
    }
}
?>