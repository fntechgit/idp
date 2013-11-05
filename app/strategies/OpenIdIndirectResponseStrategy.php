<?php


namespace strategies;

use openid\strategies\IOpenIdResponseStrategy;
use Redirect;
use Response;

class OpenIdIndirectResponseStrategy implements IOpenIdResponseStrategy
{

    public function handle($response)
    {
        $query_string = $response->getContent();
        $return_to = $response->getReturnTo();
        if (is_null($return_to) || empty($return_to)) {
            return \View::make('404');
        }
        $return_to = (strpos($return_to, "?") === false) ? $return_to . "?" . $query_string : $return_to . "&" . $query_string;
        return Redirect::to($return_to);
    }
}