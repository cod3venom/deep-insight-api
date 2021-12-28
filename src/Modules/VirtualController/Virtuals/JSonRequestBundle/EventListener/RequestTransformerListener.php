<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 28.12.2021
 * Time: 22:13
*/

namespace App\Modules\VirtualController\Virtuals\JSonRequestBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestTransformerListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (false === $this->supports($request)) {
            return;
        }

        try {
            $data = \json_decode((string) $request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

            if (\is_array($data)) {
                $request->request->replace($data);
            }
        } catch (\JsonException $exception) {
            $event->setResponse(new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST));
        }
    }

    private function supports(Request $request): bool
    {
        return 'json' === $request->getContentType() && $request->getContent();
    }
}
