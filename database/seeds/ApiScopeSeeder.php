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
use OAuth2\OAuth2Protocol;
use Models\OAuth2\Api;
use Models\OAuth2\ApiScope;
use Illuminate\Database\Seeder;
use LaravelDoctrine\ORM\Facades\EntityManager;
use App\libs\OAuth2\IUserScopes;
use Illuminate\Support\Facades\DB;
/**
 * Class ApiScopeSeeder
 */
class ApiScopeSeeder extends Seeder {

    public function run()
    {
        DB::table('oauth2_api_endpoint_api_scope')->delete();
        DB::table('oauth2_client_api_scope')->delete();
        DB::table('oauth2_api_scope')->delete();
        $this->seedUsersScopes();
        $this->seedRegistrationScopes();
    }

    public static function seedScopes(array $scopes_definitions, string $api_name = null){

        $api = null;
        if(!is_null($api_name))
            $api = EntityManager::getRepository(Api::class)->findOneBy(['name' => $api_name]);

        foreach ($scopes_definitions as $scope_info) {
            $scope = new ApiScope();
            $scope->setName($scope_info['name']);
            $scope->setShortDescription($scope_info['short_description']);
            $scope->setDescription($scope_info['description']);
            $scope->setActive(true);
            if(isset($scope_info['system']))
                $scope->setSystem($scope_info['system']);

            if(isset($scope_info['default']))
                $scope->setDefault($scope_info['default']);

            if(isset($scope_info['groups']))
                $scope->setAssignedByGroups($scope_info['groups']);

            if(!is_null($api))
                $scope->setApi($api);
            EntityManager::persist($scope);
        }

        EntityManager::flush();
    }

    private function seedUsersScopes(){

        self::seedScopes([
            [
                'name'               => IUserScopes::Profile,
                'short_description'  => 'Allows access to your profile info.',
                'description'        => 'This scope value requests access to the End-Users default profile Claims, which are: name, family_name, given_name, middle_name, nickname, preferred_username, profile, picture, website, gender, birthdate, zoneinfo, locale, and updated_at.',
                'system'             => false,
                'default'            => false,
            ],
            [
                'name'               => IUserScopes::Email,
                'short_description'  => 'Allows access to your email info.',
                'description'        => 'This scope value requests access to the email and email_verified Claims.',
                'system'             => false,
                'default'            => false,
            ],
            [
                'name'               => IUserScopes::Address,
                'short_description'  => 'Allows access to your Address info.',
                'description'        => 'This scope value requests access to the address Claim.',
                'system'             => false,
                'default'            => false,
            ],
            [
                'name'               => IUserScopes::ReadAll,
                'short_description'  => 'Allows access to users info',
                'description'        => 'This scope value requests access to users info',
                'system'             => false,
                'default'            => false,
                'groups'             => true,
            ]
        ], 'users');

        self::seedScopes(
            [
                [
                    'name'               => OAuth2Protocol::OpenIdConnect_Scope,
                    'short_description'  => 'OpenId Connect Protocol',
                    'description'        => 'OpenId Connect Protocol',
                    'system'             => true,
                    'default'            => true,
                ],
                [
                    'name'               => OAuth2Protocol::OfflineAccess_Scope,
                    'short_description'  => 'allow to emit refresh tokens (offline access without user presence)',
                    'description'        => 'allow to emit refresh tokens (offline access without user presence)',
                    'system'             => true,
                    'default'            => true,
                ]
            ]
        );
    }

    private function seedRegistrationScopes(){
        self::seedScopes([
            [
                'name'               => IUserScopes::Registration,
                'short_description'  => 'Allows to request user registrations.',
                'description'        => 'Allows to request user registrations.',
                'system'             => false,
                'default'            => false,
                'groups'             => true,
            ],

        ], 'user-registration');

    }
}