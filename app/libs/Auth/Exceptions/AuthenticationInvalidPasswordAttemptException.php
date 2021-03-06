<?php namespace Auth\Exceptions;
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
use Exception;
/**
 * Class AuthenticationInvalidPasswordAttemptException
 * @package Auth\Exceptions
 */
final class AuthenticationInvalidPasswordAttemptException extends Exception
{

    /**
     * @var int
     */
    private $user_id;

    /**
     * AuthenticationInvalidPasswordAttemptException constructor.
     * @param int $user_id
     * @param string $message
     */
    public function __construct(int $user_id, string $message = "")
    {
        $message = "Invalid Password Attempt: " . $message;
        $this->user_id = $user_id;
        parent::__construct($message, 0, null);
    }

    /**
     * @return int
     */
    public function getUserId(): int{
        return $this->user_id;
    }
}