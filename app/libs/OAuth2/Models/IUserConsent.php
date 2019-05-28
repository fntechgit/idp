<?php namespace OAuth2\Models;
/**
 * Copyright 2016 OpenStack Foundation
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
use Auth\User;
use Models\OAuth2\Client;
/**
 * Interface IUserConsent
 * @package OAuth2\Models
 */
interface IUserConsent {
    /**
     * @return string
     */
    public function getScope():string;

    /**
     * @return Client
     */
    public function getClient():Client;

    /**
     * @return User
     */
    public function getUser():User;

    /**
     * @param string $scope
     */
    public function setScope(string $scope): void;

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void;

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void;
} 