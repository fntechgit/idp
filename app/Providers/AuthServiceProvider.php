<?php namespace App\Providers;
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
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Auth\CustomAuthProvider as AuthProvider;
use OpenId\Services\OpenIdServiceCatalog;
use Utils\Services\UtilsServiceCatalog;
use Auth\IAuthenticationExtensionService;
use Auth\Repositories\IUserRepository;
/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Auth::provider('custom', function($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new AuthProvider(
                App::make(IUserRepository::class),
                App::make(IAuthenticationExtensionService::class),
                App::make(OpenIdServiceCatalog::UserService),
                App::make(UtilsServiceCatalog::CheckPointService),
                App::make(UtilsServiceCatalog::TransactionService)
            );
        });
    }
}
