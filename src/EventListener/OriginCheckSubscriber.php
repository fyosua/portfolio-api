<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OriginCheckSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_ORIGIN_PATTERN = '#^https?://(localhost|127\.0\.0\.1|yosuaf\.com)(:[0-9]+)?$#';

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // Only enforce on non-safe methods (POST, PUT, PATCH, DELETE)
        if (in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return;
        }

        $origin = $request->headers->get('Origin');

        // If Origin is present, it must match the whitelist
        if ($origin && !preg_match(self::ALLOWED_ORIGIN_PATTERN, $origin)) {
            $event->setResponse(new JsonResponse(
                ['message' => 'Access denied: origin not allowed'],
                Response::HTTP_FORBIDDEN
            ));
            return;
        }

        // If no Origin header (curl, Postman, non-browser client), reject state-changing requests
        if (!$origin) {
            $event->setResponse(new JsonResponse(
                ['message' => 'Access denied: Origin header required'],
                Response::HTTP_FORBIDDEN
            ));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 15], // before firewall (priority 8)
        ];
    }
}