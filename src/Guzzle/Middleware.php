<?php

namespace SoapBox\Idempotency\Guzzle;

use Ramsey\Uuid\Uuid;
use SoapBox\Idempotency\Idempotency;
use Psr\Http\Message\RequestInterface;

class Middleware
{
    /**
     * Invokes the idempotency middleware.
     *
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (Idempotency::supportedRequestMethod($request->getMethod())) {
                $request = $request->withHeader(
                	config('idempotency.header'),
                	Uuid::uuid4()->toString()
                );
            }

            return $handler($request, $options);
        };
    }
}
