<?php

namespace openid\model;


interface ITrustedSite
{
    public function getRealm();

    public function getData();

    public function getUser();

    public function getAuthorizationPolicy();

    public function getUITrustedData();
}