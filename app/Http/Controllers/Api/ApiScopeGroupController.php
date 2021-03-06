<?php namespace App\Http\Controllers\Api;
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
use App\Http\Controllers\APICRUDController;
use App\ModelSerializers\SerializerRegistry;
use Exception;
use Illuminate\Support\Facades\Log;
use models\exceptions\EntityNotFoundException;
use models\exceptions\ValidationException;
use OAuth2\Repositories\IApiScopeGroupRepository;
use OAuth2\Services\IApiScopeGroupService;
use Utils\Services\ILogService;
/**
 * Class ApiScopeGroupController
 * @package App\Http\Controllers
 */
final class ApiScopeGroupController extends APICRUDController
{

    /**
     * ApiScopeGroupController constructor.
     * @param IApiScopeGroupService $service
     * @param IApiScopeGroupRepository $repository
     * @param ILogService $log_service
     */
    public function __construct
    (
        IApiScopeGroupService $service,
        IApiScopeGroupRepository $repository,
        ILogService $log_service
    )
    {
        parent::__construct($repository, $service, $log_service);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function activate($id){
        try
        {
            $entity = $this->service->update($id, ['active' => true]);
            return $this->updated(SerializerRegistry::getInstance()->getSerializer($entity)->serialize());
        }
        catch (ValidationException $ex1)
        {
            Log::warning($ex1);
            return $this->error412(array( $ex1->getMessage()));
        }
        catch (EntityNotFoundException $ex2)
        {
            Log::warning($ex2);
            return $this->error404(array('message' => $ex2->getMessage()));
        }
        catch (Exception $ex) {
            Log::error($ex);
            return $this->error500($ex);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function deactivate($id){
        try
        {
            $entity = $this->service->update($id, ['active' => false]);
            return $this->updated(SerializerRegistry::getInstance()->getSerializer($entity)->serialize());
        }
        catch (ValidationException $ex1)
        {
            Log::warning($ex1);
            return $this->error412(array( $ex1->getMessage()));
        }
        catch (EntityNotFoundException $ex2)
        {
            Log::warning($ex2);
            return $this->error404(array('message' => $ex2->getMessage()));
        }
        catch (Exception $ex) {
            Log::error($ex);
            return $this->error500($ex);
        }
    }

    /**
     * @return array
     */
    protected function getUpdatePayloadValidationRules(): array
    {
        return [
            'name'    => 'required|text|max:512',
            'active'  => 'required|boolean',
            'scopes'  => 'required',
            'users'   => 'required|user_ids',
        ];
    }

    /**
     * @return array
     */
    protected function getCreatePayloadValidationRules(): array
    {
       return [
           'name'   => 'required|text|max:512',
           'active' => 'required|boolean',
           'scopes' => 'required',
           'users'  => 'required|user_ids',
       ];
    }
}