<?php namespace OpenId\Requests;
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
use OpenId\Exceptions\InvalidOpenIdMessageException;
use OpenId\Helpers\OpenIdUriHelper;
use OpenId\OpenIdMessage;
use OpenId\OpenIdProtocol;
/**
 * Class OpenIdAuthenticationRequest
 * @package OpenId\Requests
 */
class OpenIdAuthenticationRequest extends OpenIdRequest
{

    /**
     * @var null|string
     */
    private $user_identity_endpoint;

    /**
     * @param OpenIdMessage $message
     * @param string|null $user_identity_endpoint
     * @throws InvalidOpenIdMessageException
     */
    public function __construct(OpenIdMessage $message, $user_identity_endpoint = null)
    {
        parent::__construct($message);
        $this->user_identity_endpoint = $user_identity_endpoint;
        if (!empty($this->user_identity_endpoint)) {
            if (!str_contains($this->user_identity_endpoint, '@identifier')) {
                throw new InvalidOpenIdMessageException("user_identity_endpoint value  must contain @identifier placeholder!.");
            }
        }
    }

    /**
     * @param OpenIdMessage $message
     * @return bool
     */
    public static function IsOpenIdAuthenticationRequest(OpenIdMessage $message)
    {
        $mode = $message->getMode();
        if ($mode == OpenIdProtocol::ImmediateMode || $mode == OpenIdProtocol::SetupMode) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getAssocHandle()
    {
        return $this->getParam(OpenIdProtocol::OpenIDProtocol_AssocHandle);
    }

    /**
     * @return bool
     * @throws InvalidOpenIdMessageException
     */
    public function isValid()
    {
        $return_to                     = $this->getReturnTo();
        $claimed_id                    = $this->getClaimedId();
        $identity                      = $this->getIdentity();
        $mode                          = $this->getMode();
        $realm                         = $this->getRealm();
        $valid_id                      = $this->isValidIdentifier($claimed_id, $identity);
        $valid_realm                   = OpenIdUriHelper::isValidRealm($realm);
        $valid_return_to_against_realm = OpenIdUriHelper::checkRealm($realm, $return_to);
        $valid_return_to               = OpenIdUriHelper::checkReturnTo($return_to);

        if (empty($return_to)) {
            throw new InvalidOpenIdMessageException('return_to is empty.');
        }

        if (empty($realm)) {
            throw new InvalidOpenIdMessageException('realm is empty.');
        }

        if (!$valid_realm) {
            throw new InvalidOpenIdMessageException
            (
                sprintf
                (
                    'realm is not valid ( %s )',
                    $realm
                )
            );
        }

        if (!$valid_return_to_against_realm) {
            throw new InvalidOpenIdMessageException
            (
                sprintf
                (
                    'return to url check against provided realm is not valid ( realm %s - return_to %s).',
                    $realm,
                    $return_to
                )
            );
        }

        if (!$valid_return_to) {
            throw new InvalidOpenIdMessageException(sprintf('invalid return_to url ( %s )', $return_to));
        }

        if (empty($claimed_id)) {
            throw new InvalidOpenIdMessageException('claimed_id is empty.');
        }

        if (empty($identity)) {
            throw new InvalidOpenIdMessageException('identity is empty.');
        }

        if (!$valid_id) {
            throw new InvalidOpenIdMessageException(sprintf('identity check is not valid claimed_id %s - identity %s.',
                $claimed_id, $identity));
        }

        if (empty($mode)) {
            throw new InvalidOpenIdMessageException('mode is empty.');
        }

        if (!($mode == OpenIdProtocol::ImmediateMode || $mode == OpenIdProtocol::SetupMode)) {
            throw new InvalidOpenIdMessageException(sprintf('mode %s is invalid.', $mode));
        }

        return true;
    }

    public function getReturnTo()
    {
        return  $this->getParam(OpenIdProtocol::OpenIDProtocol_ReturnTo);
    }

    public function getClaimedId()
    {
        return $this->getParam(OpenIdProtocol::OpenIDProtocol_ClaimedId);
    }

    public function getIdentity()
    {
        return $this->getParam(OpenIdProtocol::OpenIDProtocol_Identity);
    }

    public function getRealm()
    {
        $realm = $this->getParam(OpenIdProtocol::OpenIDProtocol_Realm);

        return $realm;
    }

    public function isIdentitySelectByOP()
    {
        $claimed_id = $this->getClaimedId();
        $identity = $this->getIdentity();
        //http://specs.openid.net/auth/2.0/identifier_select
        if ($claimed_id == $identity && $identity == OpenIdProtocol::IdentifierSelectType) {
            return true;
        }

        return false;
    }

    /**
     * @param $claimed_id
     * @param $identity
     * @return bool
     * @throws InvalidOpenIdMessageException
     */
    private function isValidIdentifier($claimed_id, $identity)
    {
        /*
         * openid.claimed_id" and "openid.identity" SHALL be either both present or both absent.
         * If neither value is present, the assertion is not about an identifier, and will contain
         * other information in its payload, using extensions.
         */

        if (empty($this->user_identity_endpoint)) {
            throw new InvalidOpenIdMessageException("user_identity_endpoint is not set.");
        }

        if (is_null($claimed_id) && is_null($identity)) {
            return false;
        }
        //http://specs.openid.net/auth/2.0/identifier_select
        if ($claimed_id == $identity && $identity == OpenIdProtocol::IdentifierSelectType) {
            return true;
        }

        if (OpenIdUriHelper::isValidUrl($claimed_id) && OpenIdUriHelper::isValidUrl($identity)) {
            $identity_url_pattern = $this->user_identity_endpoint;
            $url_parts = explode("@", $identity_url_pattern, 2);
            $base_identity_url = $url_parts[0];
            if (strpos($identity, $base_identity_url) !== false) {
                return true;
            }
            if (strpos($claimed_id, $base_identity_url) !== false) {
                return true;
            }
        }

        return false;
    }

}
