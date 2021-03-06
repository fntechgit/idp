<?php namespace Services\SecurityPolicies;
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
use App\Http\Utils\IUserIPHelperProvider;
use App\libs\Auth\Repositories\IBannedIPRepository;
use Illuminate\Support\Facades\Log;
use Exception;
use OAuth2\Exceptions\ReplayAttackAuthCodeException;
use Utils\Db\ITransactionService;
use Utils\Services\ICacheService;
use Utils\Services\ILockManagerService;
use Utils\Services\IServerConfigurationService;
/**
 * Class AuthorizationCodeRedeemPolicy
 * @package Services\SecurityPolicies
 */
final class AuthorizationCodeRedeemPolicy extends AbstractBlacklistSecurityPolicy
{


    /**
     * AuthorizationCodeRedeemPolicy constructor.
     * @param IBannedIPRepository $banned_ip_repository
     * @param IServerConfigurationService $server_configuration_service
     * @param ILockManagerService $lock_manager_service
     * @param ICacheService $cache_service
     * @param IUserIPHelperProvider $ip_helper
     * @param ITransactionService $tx_service
     */
    public function __construct(
        IBannedIPRepository  $banned_ip_repository,
        IServerConfigurationService $server_configuration_service,
        ILockManagerService $lock_manager_service,
        ICacheService $cache_service,
        IUserIPHelperProvider $ip_helper,
        ITransactionService $tx_service
    ) {
        parent::__construct
        (
            $banned_ip_repository,
            $server_configuration_service,
            $lock_manager_service,
            $cache_service,
            $ip_helper,
            $tx_service
        );
    }

    /**
     * Check if current security policy is meet or not
     * @return boolean
     */
    public function check()
    {
        return true;
    }

    /**
     * Apply security policy on a exception
     * @param Exception $ex
     * @return $this
     */
    public function apply(Exception $ex)
    {
        try {

            if ($ex instanceof ReplayAttackAuthCodeException) {
                $auth_code = $ex->getToken();
                Log::error(sprintf("AuthorizationCodeRedeemPolicy : auth code %s - message %s", $auth_code, $ex->getMessage()));
                $this->counter_measure->trigger
                (
                    [
                        'auth_code' => $auth_code
                    ]
                );
            }
        } catch (Exception $ex) {
            Log::error($ex);
        }
        return $this;
    }

}