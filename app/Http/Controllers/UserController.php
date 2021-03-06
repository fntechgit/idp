<?php namespace App\Http\Controllers;
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
use App\Http\Controllers\OpenId\DiscoveryController;
use App\Http\Controllers\OpenId\OpenIdController;
use Auth\Exceptions\AuthenticationException;
use Auth\Exceptions\UnverifiedEmailMemberException;
use Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use OAuth2\Repositories\IApiScopeRepository;
use OAuth2\Repositories\IClientRepository;
use OpenId\Services\IUserService;
use OAuth2\Services\IApiScopeService;
use OAuth2\Services\IClientService;
use OAuth2\Services\IMementoOAuth2SerializerService;
use OAuth2\Services\IResourceServerService;
use OAuth2\Services\ISecurityContextService;
use OAuth2\Services\ITokenService;
use OpenId\Services\IMementoOpenIdSerializerService;
use OpenId\Services\ITrustedSitesService;
use models\exceptions\ValidationException;
use Services\IUserActionService;
use Sokil\IsoCodes\IsoCodesFactory;
use Strategies\DefaultLoginStrategy;
use Strategies\IConsentStrategy;
use Strategies\OAuth2ConsentStrategy;
use Strategies\OAuth2LoginStrategy;
use Strategies\OpenIdConsentStrategy;
use Strategies\OpenIdLoginStrategy;
use Utils\IPHelper;
use Utils\Services\IAuthService;
use Utils\Services\IServerConfigurationService;
use Utils\Services\IServerConfigurationService as IUtilsServerConfigurationService;
/**
 * Class UserController
 * @package App\Http\Controllers
 */
final class UserController extends OpenIdController
{
    /**
     * @var IMementoOpenIdSerializerService
     */
    private $openid_memento_service;
    /**
     * @var IMementoOAuth2SerializerService
     */
    private $oauth2_memento_service;
    /**
     * @var IAuthService
     */
    private $auth_service;
    /**
     * @var IServerConfigurationService
     */
    private $server_configuration_service;
    /**
     * @var DiscoveryController
     */
    private $discovery;
    /**
     * @var IUserService
     */
    private $user_service;
    /**
     * @var IUserActionService
     */
    private $user_action_service;
    /**
     * @var DefaultLoginStrategy
     */
    private $login_strategy;
    /**
     * @var IConsentStrategy
     */
    private $consent_strategy;
    /**
     * @var IClientRepository
     */
    private $client_repository;
    /**
     * @var IApiScopeRepository
     */
    private $scope_repository;
    /**
     * @var ITokenService
     */
    private $token_service;
    /**
     * @var IResourceServerService
     */
    private $resource_server_service;
    /**
     * @var IUtilsServerConfigurationService
     */
    private $utils_configuration_service;

    /**
     * @var ISecurityContextService
     */
    private $security_context_service;

    /**
     * UserController constructor.
     * @param IMementoOpenIdSerializerService $openid_memento_service
     * @param IMementoOAuth2SerializerService $oauth2_memento_service
     * @param IAuthService $auth_service
     * @param IUtilsServerConfigurationService $server_configuration_service
     * @param ITrustedSitesService $trusted_sites_service
     * @param DiscoveryController $discovery
     * @param IUserService $user_service
     * @param IUserActionService $user_action_service
     * @param IClientRepository $client_repository
     * @param IApiScopeRepository $scope_repository
     * @param ITokenService $token_service
     * @param IResourceServerService $resource_server_service
     * @param IUtilsServerConfigurationService $utils_configuration_service
     * @param ISecurityContextService $security_context_service
     */
    public function __construct
    (
        IMementoOpenIdSerializerService $openid_memento_service,
        IMementoOAuth2SerializerService $oauth2_memento_service,
        IAuthService $auth_service,
        IServerConfigurationService $server_configuration_service,
        ITrustedSitesService $trusted_sites_service,
        DiscoveryController $discovery,
        IUserService $user_service,
        IUserActionService $user_action_service,
        IClientRepository $client_repository,
        IApiScopeRepository $scope_repository,
        ITokenService $token_service,
        IResourceServerService $resource_server_service,
        IUtilsServerConfigurationService $utils_configuration_service,
        ISecurityContextService $security_context_service
    )
    {

        $this->openid_memento_service       = $openid_memento_service;
        $this->oauth2_memento_service       = $oauth2_memento_service;
        $this->auth_service                 = $auth_service;
        $this->server_configuration_service = $server_configuration_service;
        $this->trusted_sites_service        = $trusted_sites_service;
        $this->discovery                    = $discovery;
        $this->user_service                 = $user_service;
        $this->user_action_service          = $user_action_service;
        $this->client_repository            = $client_repository;
        $this->scope_repository             = $scope_repository;
        $this->token_service                = $token_service;
        $this->resource_server_service      = $resource_server_service;
        $this->utils_configuration_service  = $utils_configuration_service;
        $this->security_context_service     = $security_context_service;

        $this->middleware(function ($request, $next) {
            if ($this->openid_memento_service->exists())
            {
                //openid stuff
                $this->login_strategy   = new OpenIdLoginStrategy
                (
                    $this->openid_memento_service,
                    $this->user_action_service,
                    $this->auth_service
                );

                $this->consent_strategy = new OpenIdConsentStrategy
                (
                    $this->openid_memento_service,
                    $this->auth_service,
                    $this->server_configuration_service,
                    $this->user_action_service
                );

            }
            else if ($this->oauth2_memento_service->exists())
            {

                $this->login_strategy = new OAuth2LoginStrategy
                (
                    $this->auth_service,
                    $this->oauth2_memento_service,
                    $this->user_action_service,
                    $this->security_context_service
                );

                $this->consent_strategy = new OAuth2ConsentStrategy
                (
                    $this->auth_service,
                    $this->oauth2_memento_service,
                    $this->scope_repository,
                    $this->client_repository
                );
            }
            else
            {
                //default stuff
                $this->login_strategy   = new DefaultLoginStrategy($this->user_action_service, $this->auth_service);
                $this->consent_strategy = null;
            }

            return $next($request);
        });
    }

    public function getLogin()
    {
        return $this->login_strategy->getLogin();
    }

    public function cancelLogin()
    {
        return $this->login_strategy->cancelLogin();
    }

    public function postLogin()
    {
        $max_login_attempts_2_show_captcha = $this->server_configuration_service->getConfigValue("MaxFailed.LoginAttempts.2ShowCaptcha");
        $login_attempts                    = 0;
        $username                          = '';
        try
        {

            $data = Input::all();

            if(isset($data['username']))
                $data['username'] = trim($data['username']);
            if(isset($data['password']))
                $data['password'] = trim($data['password']);

            $login_attempts = intval(Input::get('login_attempts'));
            // Build the validation constraint set.
            $rules = array
            (
                'username' => 'required|email',
                'password' => 'required',
            );
            if ($login_attempts >= $max_login_attempts_2_show_captcha)
            {
                $rules['g-recaptcha-response'] = 'required|recaptcha';
            }
            // Create a new validator instance.
            $validator = Validator::make($data, $rules);

            if ($validator->passes())
            {
                $username = $data['username'];
                $password = $data['password'];
                $remember = Input::get("remember");

                $remember = !is_null($remember);
                if ($this->auth_service->login($username, $password, $remember))
                {
                    return $this->login_strategy->postLogin();
                }

                //failed login attempt...
                $user = $this->auth_service->getUserByUsername($username);

                if (!is_null($user))
                {
                    $login_attempts = $user->getLoginFailedAttempt();
                }

                return $this->login_strategy->errorLogin
                (
                    array
                    (
                        'max_login_attempts_2_show_captcha' => $max_login_attempts_2_show_captcha,
                        'login_attempts'                    => $login_attempts,
                        'username'                          => $username,
                        'error_message'                     => "We are sorry, your username or password does not match an existing record."
                    )
                );
            }
            // validator errors
            return $this->login_strategy->errorLogin
            (
                array
                (
                    'max_login_attempts_2_show_captcha' => $max_login_attempts_2_show_captcha,
                    'login_attempts'                    => $login_attempts,
                    'validator'                         => $validator
                )
            );
        }
        catch(UnverifiedEmailMemberException $ex1)
        {
            Log::warning($ex1);
            return $this->login_strategy->errorLogin
            (
                array
                (
                    'max_login_attempts_2_show_captcha' => $max_login_attempts_2_show_captcha,
                    'login_attempts'                    => $login_attempts,
                    'username'                          => $username,
                    'error_message'                     => $ex1->getMessage()
                )
            );
        }
        catch(AuthenticationException $ex2){
            Log::warning($ex2);
            return Redirect::action('UserController@getLogin');
        }
        catch (Exception $ex)
        {
            Log::error($ex);
            return Redirect::action('UserController@getLogin');
        }
    }

    public function getConsent()
    {
        if (is_null($this->consent_strategy))
        {
            return View::make("errors.404");
        }

        return $this->consent_strategy->getConsent();
    }

    public function postConsent()
    {
        try
        {
            $data  = Input::all();
            $rules = array
            (
                'trust' => 'required|oauth2_trust_response',
            );
            // Create a new validator instance.
            $validator = Validator::make($data, $rules);
            if ($validator->passes())
            {
                if (is_null($this->consent_strategy))
                {
                    return View::make("errors.404");
                }

                return $this->consent_strategy->postConsent(Input::get("trust"));
            }
            return Redirect::action('UserController@getConsent')->withErrors($validator);
        }
        catch (Exception $ex)
        {
            Log::error($ex);
            return Redirect::action('UserController@getConsent');
        }
    }

    public function getIdentity($identifier)
    {
        try
        {
            $user = $this->auth_service->getUserByOpenId($identifier);
            if (is_null($user))
            {
                return View::make("errors.404");
            }

            if ($this->isDiscoveryRequest())
            {
                /*
                * If the Claimed Identifier was not previously discovered by the Relying Party
                * (the "openid.identity" in the request was "http://specs.openid.net/auth/2.0/identifier_select"
                * or a different Identifier, or if the OP is sending an unsolicited positive assertion),
                * the Relying Party MUST perform discovery on the Claimed Identifier in
                * the response to make sure that the OP is authorized to make assertions about the Claimed Identifier.
                */
                return $this->discovery->user($identifier);
            }

            $redirect = Session::get('backurl');
            if (!empty($redirect)) {
                Session::forget('backurl');
                Session::save();
                return Redirect::to($redirect);
            }

            $current_user = $this->auth_service->getCurrentUser();
            $another_user = false;
            if ($current_user && $current_user->getIdentifier() != $user->getIdentifier())
            {
                $another_user = true;
            }

            $assets_url = $this->utils_configuration_service->getConfigValue("Assets.Url");
            $pic_url = $user->getPic();
            $pic_url = str_contains($pic_url, 'http') ? $pic_url : $assets_url . $pic_url;

            $params = [

                'show_fullname' => $user->getShowProfileFullName(),
                'username' => $user->getFullName(),
                'show_email' => $user->getShowProfileEmail(),
                'email' => $user->getEmail(),
                'identifier' => $user->getIdentifier(),
                'show_pic' => $user->getShowProfilePic(),
                'pic' => $pic_url,
                'another_user' => $another_user,
            ];

            return View::make("identity", $params);
        }
        catch (Exception $ex)
        {
            Log::error($ex);
            return View::make("errors.404");
        }
    }

    public function logout()
    {
        $this->user_action_service->addUserAction
        (
            $this->auth_service->getCurrentUser()->getId(),
            IPHelper::getUserIp(),
            IUserActionService::LogoutAction
        );
        $this->auth_service->logout();
        Session::flush();
        Session::regenerate();
        return Redirect::action("UserController@getLogin");
    }

    public function getProfile()
    {
        $user    = $this->auth_service->getCurrentUser();
        $sites   = $user->getTrustedSites();
        $actions = $user->getLatestNActions(10);

        // init database
        $isoCodes = new IsoCodesFactory();

        // get languages database
        $languages = $isoCodes->getLanguages()->toArray();
        $lang2Code = [];
        foreach ($languages as $lang){
            if(!empty($lang->getAlpha2()))
                $lang2Code[] = $lang;
        }

        // get countries database
        $countries = $isoCodes->getCountries()->toArray();

        return View::make("profile", [
            'user'       => $user,
            "openid_url" => $this->server_configuration_service->getUserIdentityEndpointURL($user->getIdentifier()),
            "sites"      => $sites,
            'actions'    => $actions,
            'countries'  => $countries,
            'languages'  => $lang2Code,
        ]);
    }

    public function deleteTrustedSite($id)
    {
        $this->trusted_sites_service->delete($id);
        return Redirect::action("UserController@getProfile");
    }

}