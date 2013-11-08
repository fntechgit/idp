<?php
/**
 * Created by JetBrains PhpStorm.
 * User: smarcet
 * Date: 10/15/13
 * Time: 4:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace openid\services;

use openid\model\IOpenIdUser;

interface IAuthService
{
    const AuthorizationResponse_None = "None";
    const AuthorizationResponse_AllowOnce = "AllowOnce";
    const AuthorizationResponse_AllowForever = "AllowForever";
    const AuthorizationResponse_DenyForever = "DenyForever";
    const AuthorizationResponse_DenyOnce = "DenyOnce";

    /**
     * @return bool
     */
    public function isUserLogged();

    /**
     * @return IOpenIdUser
     */
    public function getCurrentUser();

    /**
     * @param $username
     * @param $password
     * @param $remember_me
     * @return mixed
     */
    public function login($username, $password, $remember_me);

    public function getUserByUsername($username);

    /**
     * @return AuthorizationResponse_*
     */
    public function getUserAuthorizationResponse();

    public function setUserAuthorizationResponse($auth_response);

    public function logout();

    public function getUserByOpenId($openid);
}