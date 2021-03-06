<?php namespace OAuth2\Models;
use Models\OAuth2\Client;

/**
 * Interface IOAuth2User
 * @package OAuth2\Models
 */
interface IOAuth2User {

    /**
     *  OAUTH2 Server Admin Group Code (SS DB)
     *  Users that belongs to this group are allowed to enter on
     *  Server Admin Area
     */
    const OAuth2ServerAdminGroup      = 'oauth2-server-admins';
    /**
       OAUTH2 System Scope Admin Group Code (SS DB)
     * Users that belongs to this group are allowed to use
     * System Scopes for their OAUTH2 applications
     */
    const OAuth2SystemScopeAdminGroup = 'oauth2-system-scope-admins';

    /**
     * @return Client[]
     */
    public function getAvailableClients():array;

    /**
     * Could use system scopes on registered clients
     * @return bool
     */
    public function canUseSystemScopes();

    /**
     * Is Server Administrator
     * @return bool
     */
    public function isOAuth2ServerAdmin();

    /**
     * @return IApiScopeGroup[]
     */
    public function getGroups();

    /**
     * @return IApiScope[]
     */
    public function getGroupScopes();

} 