<?php namespace App\ModelSerializers\OAuth2;
/**
 * Copyright 2019 OpenStack Foundation
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
use App\ModelSerializers\BaseSerializer;
/**
 * Class AsymmetricKeySerializer
 * @package App\ModelSerializers\OAuth2
 */
class AsymmetricKeySerializer extends BaseSerializer
{

    protected static $array_mappings = [
        'Kid'       => 'kid:json_string',
        'PEM'       => 'pem:json_string',
        'SHA_256_Thumbprint' => 'sha_256:json_string',
        'Active'    => 'active:json_boolean',
        'Expired'   => 'expired:json_boolean',
        'ValidFrom' => 'valid_from:datetime_epoch',
        'ValidTo'   => 'valid_to:datetime_epoch',
        'Usage'     => 'usage:json_string',
        'Type'      => 'type:json_string',
        'AlgName'   => 'alg:json_string',
        'LastUse'   => 'last_used:datetime_epoch',
    ];
}