<?php namespace App\Services\Auth;
/**
 * Copyright 2020 OpenStack Foundation
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

use App\Models\SSO\Disqus\DisqusUserProfile;
use App\Services\IBaseService;
use models\exceptions\EntityNotFoundException;

/**
 * Interface IDisqusSSOService
 * @package App\Services\Auth
 */
interface IDisqusSSOService extends IBaseService
{
    /**
     * @param string $forum_slug
     * @return DisqusUserProfile|null
     * @throws EntityNotFoundException
     */
    public function getUserProfile(string $forum_slug):?DisqusUserProfile;
}