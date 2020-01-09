<?php

namespace LamBelcebur\MvcBasicTools\Controller\Plugin;

use Doctrine\ORM\ORMInvalidArgumentException;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result as AuthenticationResult;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;


class AuthenticatePlugin extends AbstractPlugin
{
    protected $authenticationService;

    /**
     * Constructor.
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }


    /**
     * @param string $identity
     * @param string $credential
     *
     * @return AuthenticationResult
     * @throws ORMInvalidArgumentException
     * @throws ServiceNotFoundException
     */
    public function authenticate($identity, $credential): AuthenticationResult
    {
        /**
         * @var AuthenticationResult $result
         */

        /** @var  ObjectRepository $adapter */
        $adapter = $this->authenticationService->getAdapter();
        $adapter->setIdentity($identity);
        $adapter->setCredential($credential);

        $result = $this->authenticationService->authenticate($adapter);
        if ($result->getCode() === AuthenticationResult::SUCCESS) {
            $this->authenticationService->getStorage()->write($result->getIdentity());
        }

        return $result;
    }

    public function logout(): void
    {
        $this->authenticationService->clearIdentity();
    }
}
