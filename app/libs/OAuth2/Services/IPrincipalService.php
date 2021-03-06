<?php namespace OAuth2\Services;

/**
 * Copyright 2015 OpenStack Foundation
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

use OAuth2\Models\IPrincipal;

/**
 * Interface IPrincipalService
 * @package OAuth2\Services
 */
interface IPrincipalService
{
    /**
     * @return IPrincipal
     */
    public function get();

    /**
     * @param IPrincipal $principal
     * @return void
     */
    public function save(IPrincipal $principal);

    /**
     * @param $user_id
     * @param $auth_time
     * @return mixed
     */
    public function register($user_id, $auth_time);

    /**
     * @return $this
     */
    public function clear();

    const OP_BROWSER_STATE_COOKIE_NAME = 'op_bs';
}