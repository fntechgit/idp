<?php
/**
 * Created by JetBrains PhpStorm.
 * User: smarcet
 * Date: 10/14/13
 * Time: 5:44 PM
 * To change this template use File | Settings | File Templates.
 */

namespace openid\handlers;


use openid\OpenIdMessage;

class OpenIdCheckAuthenticationRequestHandler extends OpenIdMessageHandler{

    protected function InternalHandle(OpenIdMessage $message){

    }

    protected  function CanHandle(OpenIdMessage $message)
    {
        // TODO: Implement CanHandle() method.
    }
}