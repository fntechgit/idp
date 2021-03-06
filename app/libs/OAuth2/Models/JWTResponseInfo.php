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
use jwa\cryptographic_algorithms\content_encryption\ContentEncryptionAlgorithm;
use jwa\cryptographic_algorithms\EncryptionAlgorithm;
use jwa\cryptographic_algorithms\IntegrityCheckingAlgorithm;
/**
 * Class JWTResponseInfo
 * @package OAuth2\Models
 */
final class JWTResponseInfo
{
    /**
     * @var ContentEncryptionAlgorithm
     */
    protected $enc;

    /**
     * @var EncryptionAlgorithm
     */
    protected $alg;

    /**
     * @var IntegrityCheckingAlgorithm
     */
    protected $sig_alg;

    /**
     * @param IntegrityCheckingAlgorithm $sig_alg
     * @param EncryptionAlgorithm $alg
     * @param ContentEncryptionAlgorithm $enc
     */
    public function __construct
    (
        IntegrityCheckingAlgorithm $sig_alg = null,
        EncryptionAlgorithm $alg = null,
        ContentEncryptionAlgorithm $enc = null
    )
    {

        $this->sig_alg = $sig_alg;
        $this->alg     = $alg;
        $this->enc     = $enc;
    }

    /**
     * @return IntegrityCheckingAlgorithm
     */
    public function getSigningAlgorithm()
    {
        return $this->sig_alg;
    }

    /**
     * @return EncryptionAlgorithm
     */
    public function getEncryptionKeyAlgorithm()
    {
        return $this->alg;
    }

    /**
     * @return ContentEncryptionAlgorithm
     */
    public function getEncryptionContentAlgorithm()
    {
        return $this->enc;
    }
}