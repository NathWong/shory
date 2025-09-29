<?php

namespace App\Listener;

use App\Attribute\Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class TransactionListener
{
    public function __construct(
        private ControllerResolverInterface $controllerResolver,
        private EntityManager $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    #[AsEventListener(KernelEvents::CONTROLLER_ARGUMENTS)]
    public function beginTransaction(ControllerArgumentsEvent $event): void
    {
        $transaction = $this->getTransactionAttribute($event->getRequest());
        if ($transaction) {
            $this->entityManager->beginTransaction();
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[AsEventListener(KernelEvents::RESPONSE)]
    public function closeTransaction(ResponseEvent $event): void
    {
        $transaction = $this->getTransactionAttribute($event->getRequest());
        if (!$transaction) {
            return;
        }

        if ($this->match($event->getResponse(), $transaction)) {
            $this->entityManager->flush();
            $this->entityManager->commit();
        } else {
            $this->entityManager->rollback();
        }
    }

    /**
     * @return ?Transaction
     */
    private function getTransactionAttribute(Request $request): ?object
    {
        $controller = $this->controllerResolver->getController($request);

        if (false === $controller) {
            return null;
        }

        try {
            $reflection = match (true) {
                \is_array($controller) => new \ReflectionMethod($controller[0], $controller[1]),
                \is_object($controller) => new \ReflectionMethod($controller, '__invoke'),
                default => null,
            };
        } catch (\ReflectionException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return null;
        }

        if (null === $reflection) {
            return null;
        }

        $transactions = $reflection->getAttributes(Transaction::class);

        if (!$transactions) {
            return null;
        }

        return $transactions[0]->newInstance();
    }

    private function match(Response $response, Transaction $transaction): bool
    {
        if (true === $transaction->on) {
            return true;
        }

        return \in_array($response->getStatusCode(), (array) $transaction->on, true);
    }
}
