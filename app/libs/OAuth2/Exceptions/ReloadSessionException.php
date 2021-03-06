<?php namespace App\libs\OAuth2\Exceptions;
/**
 * Copyright 2018 OpenStack Foundation
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
use OAuth2\Exceptions\OAuth2BaseException;
use OAuth2\OAuth2Protocol;
/**
 * Class ReloadSessionException
 * @package App\libs\OAuth2\Exceptions
 */
final class ReloadSessionException extends OAuth2BaseException
{

    /**
     * @return string
     */
    public function getError()
    {
        return OAuth2Protocol::OAuth2Protocol_Error_Session_Cant_Reload;
    }
}