<?php namespace App\libs\Auth\Repositories;
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
use App\libs\Auth\Models\UserRegistrationRequest;
use models\utils\IBaseRepository;
/**
 * Interface IUserRegistrationRequestRepository
 * @package App\libs\Auth\Repositories
 */
interface IUserRegistrationRequestRepository extends IBaseRepository
{
    /**
     * @param string $hash
     * @return UserRegistrationRequest|null
     */
    public function getByHash(string $hash):?UserRegistrationRequest;

    /**
     * @param string $email
     * @return UserRegistrationRequest|null
     */
    public function getByEmail(string $email):?UserRegistrationRequest;
}