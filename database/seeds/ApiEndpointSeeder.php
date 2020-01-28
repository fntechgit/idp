<?php
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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
/**
 * Class ApiEndpointSeeder
 */
class ApiEndpointSeeder extends Seeder
{

    public function run()
    {

        DB::table('oauth2_api_endpoint_api_scope')->delete();
        DB::table('oauth2_api_endpoint')->delete();
        $this->seedUsersEndpoints();
        $this->seedRegistrationEndpoints();
    }


    private function seedUsersEndpoints()
    {
        SeedUtils::seedApiEndpoints('users', [
                // get user info
                [
                    'name' => 'get-user-info',
                    'active' => true,
                    'route' => '/api/v1/users/me',
                    'http_method' => 'GET',
                    'scopes' => [
                       'profile', 'email', 'address'
                    ],
                ],
                // get user info 2
                [
                    'name' => 'get-user-claims-get',
                    'active' => true,
                    'route' => '/api/v1/users/info',
                    'http_method' => 'GET',
                    'scopes' => [
                        'profile', 'email', 'address'
                    ],
                ],
                // get user info 4
                [
                    'name' => 'get-user-claims-post',
                    'active' => true,
                    'route' => '/api/v1/users/info',
                    'http_method' => 'POST',
                    'scopes' => [
                        'profile', 'email', 'address'
                    ],
                ]
                ,
                // get users
                [
                    'name' => 'get-users',
                    'active' => true,
                    'route' => '/api/v1/users',
                    'http_method' => 'GET',
                    'scopes' => [
                        \App\libs\OAuth2\IUserScopes::ReadAll
                    ],
                ]

            ]
        );
    }

    private function seedRegistrationEndpoints(){
        SeedUtils::seedApiEndpoints('user-registration', [
            [
                'name' => 'request-user-registration',
                'active' => true,
                'route' => '/api/v1/user-registration-requests',
                'http_method' => 'POST',
                'scopes'      => [
                    \App\libs\OAuth2\IUserScopes::Registration
                ],
            ],
        ]);
    }

}