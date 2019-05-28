<?php namespace Utils\Services;
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
use App\Services\IBaseService;
use Models\BannedIP;
use models\exceptions\EntityNotFoundException;
use models\exceptions\ValidationException;
/**
 * Interface IBannedIPService
 * @package Utils\Services
 */
interface IBannedIPService extends IBaseService {

    /**
     * @param int $initial_hits
     * @param string $exception_type
     * @return BannedIP
     * @throws EntityNotFoundException
     * @throws ValidationException
     */
    public function add(int $initial_hits, string $exception_type):BannedIP;

    /**
     * @param $ip
     * @throws EntityNotFoundException
     */
    public function deleteByIP(string $ip):void;

} 