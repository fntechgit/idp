<?php namespace App\Services;
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
use Utils\Db\ITransactionService;
/**
 * Class AbstractService
 * @package App\Services
 */
abstract class AbstractService
{
    /**
     * @var ITransactionService
     */
    protected $tx_service;

    /**
     * AbstractService constructor.
     * @param ITransactionService $tx_service
     */
    public function __construct(ITransactionService $tx_service)
    {
        $this->tx_service = $tx_service;
    }
}