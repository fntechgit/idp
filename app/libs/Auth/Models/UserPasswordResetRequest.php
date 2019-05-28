<?php namespace Auth;
/**
 * Copyright 2019 OpenStack Foundation
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
use App\Events\UserPasswordResetRequestCreated;
use App\Models\Utils\BaseEntity;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Doctrine\ORM\Mapping AS ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repositories\DoctrineUserPasswordResetRequestRepository")
 * @ORM\Table(name="user_password_reset_request")
 * @ORM\HasLifecycleCallbacks
 * Class UserPasswordResetRequest
 * @package Auth
 */
class UserPasswordResetRequest extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Auth\User", inversedBy="reset_password_requests")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @var User
     */
    private $owner;

    /**
     * @ORM\Column(name="lifetime", type="integer")
     * @var int
     */
    private $lifetime;

    /**
     * @ORM\Column(name="hash", type="string")
     * @var string
     */
    private $hash;

    /**
     * @var \DateTime
     * @ORM\Column(name="redeem_at", nullable=true, type="datetime")
     */
    private $redeem_at;


    public function __construct()
    {
        parent::__construct();
        $this->lifetime = Config::get("auth.password_reset_lifetime", 10 * 60);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name) {
        return $this->{$name};
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    public const TokenLen = 50;

    /**
     * @return string
     */
    public function generateToken(): string {
        $token = strval($this->id).str_random(self::TokenLen);
        $this->hash = self::hash($token);
        return $token;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function compare(string $token):bool{
        return $this->hash == self::hash($token);
    }

    /**
     * @param string $token
     * @return string
     */
    public static function hash(string $token):string{
        return md5($token);
    }

    /**
     * @return \DateTime
     */
    public function getRedeemAt(): \DateTime
    {
        return $this->redeem_at;
    }

    public function redeem():void{
        $this->redeem_at = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return bool
     */
    public function isRedeem():bool {
        return !is_null($this->redeem_at);
    }

    /**
     * @return bool
     */
    public function isValid():bool{
        $void_date = $this->created_at->add(new \DateInterval('PT'.$this->lifetime.'S'));
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        return $void_date > $now;
    }

    /**
     * @ORM\PostPersist
     */
    public function inserted($args){
        Event::fire(new UserPasswordResetRequestCreated($this->getId()));
    }
}