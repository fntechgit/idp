<?php namespace Services\OAuth2;
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
use models\utils\IEntity;
use OAuth2\Services\IServerPrivateKeyService;
use OAuth2\Repositories\IServerPrivateKeyRepository;
use Utils\Db\ITransactionService;
use Models\OAuth2\ServerPrivateKey;
use DateTime;
use phpseclib\Crypt\RSA;
use models\exceptions\ValidationException;
/**
 * Class ServerPrivateKeyService
 * @package Services\OAuth2
 */
final class ServerPrivateKeyService extends AsymmetricKeyService implements IServerPrivateKeyService
{

    /**
     * @var RSA
     */
    private $rsa;

    /**
     * ServerPrivateKeyService constructor.
     * @param IServerPrivateKeyRepository $repository
     * @param ITransactionService $tx_service
     */
    public function __construct
    (
        IServerPrivateKeyRepository $repository,
        ITransactionService $tx_service
    )
    {
        parent::__construct($repository, $tx_service);
        $this->rsa = new RSA();
    }

    /**
     * @param array $params
     * @return IEntity
     * @throws ValidationException
     */
    public function create(array $params):IEntity
    {
        return $this->tx_service->transaction(function() use($params)
        {
            $pem      = isset($params['pem_content']) ? trim($params['pem_content']) : '';
            $password = isset($params['password'])? trim($params['password']) : '';

            $old_active_key = $this->repository->getByValidityRange
            (
                $params['type'],
                $params['usage'],
                $params['alg'],
                new DateTime($params['valid_from']),
                new DateTime($params['valid_to'])
            );

            if(count($old_active_key) > 0 )
                $old_active_key = $old_active_key[0];

            if(empty($pem))
            {
                if(!empty($password))
                    $this->rsa->setPassword($password);
                /**
                 * array(
                 *    'privatekey' => $privatekey,
                 *   'publickey' => $publickey,
                 *   'partialkey' => false
                 *   );
                 */
                $res = $this->rsa->createKey(2048);
                $pem = $res['privatekey'];
            }

            $key = ServerPrivateKey::build
            (
                $params['kid'],
                new DateTime($params['valid_from']),
                new DateTime($params['valid_to']),
                $params['type'],
                $params['usage'],
                $params['alg'],
                $old_active_key ? false : $params['active'],
                $pem,
                $password
            );

            $this->repository->add($key);

            return $key;
        });
    }

}