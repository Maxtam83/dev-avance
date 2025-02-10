<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Redirect404Listener
{
	private RouterInterface $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	public function onKernelException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		// Vérifie si l'erreur est une 404 (page non trouvée)
		if ($exception instanceof NotFoundHttpException || $exception instanceof ResourceNotFoundException) {
			// Rediriger vers la page d'accueil ou une autre route
			$response = new RedirectResponse($this->router->generate('app_login'));
			$event->setResponse($response);
		}
	}
}
