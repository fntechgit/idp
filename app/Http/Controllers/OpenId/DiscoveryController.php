<?php namespace App\Http\Controllers\OpenId;
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
use OpenId\IOpenIdProtocol;
use OpenId\Services\IServerConfigurationService;
use Utils\Services\IAuthService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
/**
 * Class DiscoveryController
 * @package App\Http\Controllers\OpenId
 */
class DiscoveryController extends OpenIdController
{
    /**
     * @var IOpenIdProtocol
     */
    private $openid_protocol;
    /**
     * @var IAuthService
     */
    private $auth_service;
    /**
     * @var IServerConfigurationService
     */
    private $server_config_service;

    /**
     * DiscoveryController constructor.
     * @param IOpenIdProtocol $openid_protocol
     * @param IAuthService $auth_service
     * @param IServerConfigurationService $server_config_service
     */
    public function __construct
    (
        IOpenIdProtocol $openid_protocol,
        IAuthService $auth_service,
        IServerConfigurationService $server_config_service
    )
    {
        $this->openid_protocol       = $openid_protocol;
        $this->auth_service          = $auth_service;
        $this->server_config_service = $server_config_service;
    }

    /**
     * XRDS discovery(eXtensible Resource Descriptor Sequence)
     * @return xrds document on response
     */
    public function idp()
    {
        $response = Response::make($this->openid_protocol->getXRDSDiscovery(IOpenIdProtocol::OpenIdXRDSModeIdp), 200);
        $this->setDiscoveryResponseType($response);
        return $response;
    }

    /**
     * If the Claimed Identifier was not previously discovered by the Relying Party
     * (the "openid.identity" in the request was "http://specs.openid.net/auth/2.0/identifier_select"
     * or a different Identifier, or if the OP is sending an unsolicited positive assertion),
     * the Relying Party MUST perform discovery on the Claimed Identifier in
     * the response to make sure that the OP is authorized to make assertions about the Claimed Identifier.
     * @param $identifier
     * @return mixed
     */
    public function user($identifier)
    {
        $user = $this->auth_service->getUserByOpenId($identifier);
        if (is_null($user))
            return View::make("errors.404");

        $local_identifier = $this->server_config_service->getUserIdentityEndpointURL($identifier);
        $response = Response::make($this->openid_protocol->getXRDSDiscovery(IOpenIdProtocol::OpenIdXRDSModeUser, $local_identifier), 200);
        $this->setDiscoveryResponseType($response);
        return $response;
    }

}