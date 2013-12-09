<?php

namespace oauth2\models;

use Zend\Math\Rand;

class AuthorizationCode extends Token {

    private $redirect_uri;


    public function __construct(){
        parent::__construct(Token::DefaultByteLength);
    }

    public static function create($client_id, $scope, $redirect_uri, $lifetime = 600){
        $instance = new self();
        $instance->value        = Rand::getString($instance->len,null,true);
        $instance->scope        = $scope;
        $instance->redirect_uri = $redirect_uri;
        $instance->client_id    = $client_id;
        $instance->lifetime     = $lifetime;
        return $instance;
    }

    public static function load($value, $client_id, $scope, $redirect_uri, $lifetime = 600){
        $instance = new self();
        $instance->value        = $value;
        $instance->scope        = $scope;
        $instance->redirect_uri = $redirect_uri;
        $instance->client_id    = $client_id;
        $instance->lifetime     = $lifetime;
        return $instance;
    }


    public function getRedirectUri(){
        return $this->redirect_uri;
    }

    public function toJSON()
    {
        $o = array(
            'value'        => $this->value,
            'redirect_uri' => $this->redirect_uri,
            'client_id'    => $this->client_id,
            'scope'        => $this->scope,
        );

        return json_encode($o);
    }

    public function fromJSON($json)
    {
        $o = json_decode($json);

        $this->value     = $o->value;
        $this->scope     = $o->scope;
        $this->client_id = $o->client_id;
        $this->scope     = $o->redirect_uri;
    }
}