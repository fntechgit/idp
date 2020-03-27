<?php namespace OAuth2\Repositories;
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
use Models\OAuth2\AsymmetricKey;
use models\utils\IBaseRepository;
/**
 * Interface IAsymmetricKeyRepository
 * @package OAuth2\Repositories
 */
interface IAsymmetricKeyRepository extends IBaseRepository
{
    /**
     * @param string $pem
     * @return AsymmetricKey|null
     */
    public function getByPEM(string $pem):?AsymmetricKey;

    /**
     * @param string $type
     * @param string $usage
     * @params string $alg
     * @param \DateTime $valid_from
     * @param \DateTime $valid_to
     * @param int|null $owner_id
     * @return AsymmetricKey[]
     */
    public function getByValidityRange($type, $usage, $alg, \DateTime $valid_from, \DateTime $valid_to, $owner_id = null):array;

    /**
     * @return AsymmetricKey[]
     */
    public function getActives():array;

    /**
     * @param string $type
     * @param string $usage
     * @param string $alg
     * @param int|null $owner_id
     * @return AsymmetricKey|null
     */
    public function getActiveByCriteria(string $type, string $usage, string $alg, int $owner_id = null):?AsymmetricKey;

}