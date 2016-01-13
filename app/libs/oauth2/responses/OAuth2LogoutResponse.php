<?php
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

namespace oauth2\responses;

use oauth2\OAuth2Protocol;

/**
 * Class OAuth2LogoutResponse
 * @package oauth2\responses
 */
final class OAuth2LogoutResponse extends OAuth2IndirectResponse
{
    /**
     * @param string $return_to
     * @param null $state
     */
    public function __construct($return_to, $state = null)
    {

        parent::__construct();

        $this->setReturnTo($return_to);

        if(!is_null($state) && !empty($state))
            $this[OAuth2Protocol::OAuth2Protocol_State] = $state;
    }
}