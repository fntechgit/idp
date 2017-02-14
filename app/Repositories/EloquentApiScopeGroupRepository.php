<?php namespace Repositories;
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
use OAuth2\Repositories\IApiScopeGroupRepository;
use Models\OAuth2\ApiScopeGroup;
use Utils\Services\ILogService;
/**
 * Class EloquentApiScopeGroupRepository
 * @package repositories
 */
final class EloquentApiScopeGroupRepository extends AbstractEloquentEntityRepository implements IApiScopeGroupRepository
{
    /**
     * @var ILogService
     */
    private $log_service;

    /**
     * @param ApiScopeGroup $group
     * @param ILogService $log_service
     */
    public function __construct(ApiScopeGroup $group, ILogService $log_service)
    {
        $this->entity      = $group;
        $this->log_service = $log_service;
    }

    /**
     * @param string $name
     * @return ApiScopeGroup
     */
    public function getByName($name)
    {
       return $this->entity->where('name', '=', $name)->first();
    }
}