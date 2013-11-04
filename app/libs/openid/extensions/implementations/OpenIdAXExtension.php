<?php
/**
 * Created by JetBrains PhpStorm.
 * User: smarcet
 * Date: 10/16/13
 * Time: 2:41 PM
 * To change this template use File | Settings | File Templates.
 */

namespace openid\extensions\implementations;


use openid\extensions\OpenIdExtension;
use openid\OpenIdProtocol;
use openid\requests\contexts\RequestContext;
use openid\requests\OpenIdRequest;
use openid\responses\contexts\ResponseContext;
use openid\responses\OpenIdResponse;
use openid\services\Registry;
use openid\requests\contexts\PartialView;
use openid\services\ServiceCatalog;


class OpenIdAXExtension extends OpenIdExtension
{
    public static $available_properties;

    const Prefix             = "ax";
    const NamespaceUrl       = "http://openid.net/srv/ax/1.0";
    const RequiredAttributes = "required";
    const Mode               = "mode";
    const Country            = "country";
    const Email              = "email";
    const FirstMame          = "firstname";
    const Language           = "language";
    const LastName           = "lastname";
    const Type               = "type";
    const Value              = "value";
    const FetchResponse      = "fetch_response";
    const FetchRequest       = "fetch_request";

    public function __construct($name, $namespace,$view, $description)
    {
        parent::__construct($name, $namespace,$view, $description);
        self::$available_properties[OpenIdAXExtension::Country]   = "http://axschema.org/contact/country/home";
        self::$available_properties[OpenIdAXExtension::Email]     = "http://axschema.org/contact/email";
        self::$available_properties[OpenIdAXExtension::FirstMame] = "http://axschema.org/namePerson/first";
        self::$available_properties[OpenIdAXExtension::LastName]  = "http://axschema.org/namePerson/last";
        self::$available_properties[OpenIdAXExtension::Language]  = "http://axschema.org/pref/language";
    }

    public function parseRequest(OpenIdRequest $request, RequestContext $context)
    {
        $ax_request = new OpenIdAXRequest($request->getMessage());
        if (!$ax_request->IsValid()) return;
        $attributes = $ax_request->getRequiredAttributes();
        $data = array();
        foreach($attributes as $attr){
            array_push($data,$attr);
        }
        $partial_view = new PartialView($this->view,array("attributes"=>$data));
        $context->addPartialView($partial_view);
    }

    public function prepareResponse(OpenIdRequest $request, OpenIdResponse $response, ResponseContext $context)
    {
        $ax_request = new OpenIdAXRequest($request->getMessage());
        if (!$ax_request->IsValid()) return;

        $response->addParam(self::paramNamespace(), self::NamespaceUrl);
        $response->addParam(self::param(self::Mode), self::FetchResponse);
        $context->addSignParam(self::param(self::Mode));
        $attributes   = $ax_request->getRequiredAttributes();
        $auth_service = Registry::getInstance()->get(ServiceCatalog::AuthenticationService);
        $user = $auth_service->getCurrentUser();
        foreach ($attributes as $attr) {
            $response->addParam(self::param(self::Type) . "." . $attr, self::$available_properties[$attr]);
            $context->addSignParam(self::param(self::Type) . "." . $attr);
            $context->addSignParam(self::param(self::Value). "." . $attr);
            if ($attr == "email") {
                $response->addParam(self::param(self::Value) . "." . $attr, $user->getEmail());
            }
            if ($attr == "country") {
                $response->addParam(self::param(self::Value) . "." . $attr, $user->getCountry());
            }
            if ($attr == "firstname") {
                $response->addParam(self::param(self::Value). "." . $attr, $user->getFirstName());
            }
            if ($attr == "lastname") {
                $response->addParam(self::param(self::Value) . "." . $attr, $user->getLastName());
            }
            if ($attr == "language") {
                $response->addParam(self::param(self::Value) . "." . $attr, $user->getLanguage());
            }
        }
    }

    public function getTrustedData(OpenIdRequest $request){

        $data = array();
        $ax_request = new OpenIdAXRequest($request->getMessage());
        if ($ax_request->IsValid()){
            $attributes = $ax_request->getRequiredAttributes();
            foreach($attributes as $attr){
                array_push($data,$attr);
            }
        }
        return $data;
    }

    public static function param($param, $separator='.'){
        return OpenIdProtocol::OpenIdPrefix.$separator.self::Prefix.$separator.$param;
    }

    public static function paramNamespace($separator='.'){
        return OpenIdProtocol::OpenIdPrefix . $separator . OpenIdProtocol::OpenIDProtocol_NS . $separator . self::Prefix;
    }
}