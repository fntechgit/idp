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
use OAuth2\Models\IClient;
use Utils\Db\IBaseRepository;
/**
 * Interface IClientRepository
 * @package OAuth2\Repositories
 */
interface IClientRepository extends IBaseRepository
{
    /**
     * @param string $app_name
     * @return IClient
     */
    public function getByApplicationName($app_name);

    /**
     * @param string $client_id
     * @return IClient
     */
    public function getClientById($client_id);

    /**
     * @param int $id
     * @return IClient
     */
    public function getClientByIdentifier($id);

    /**
     * @param string $origin
     * @return IClient
     */
    public function getByOrigin($origin);
}