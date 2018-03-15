<?php

namespace Reliv\SearchRat\OnSiteSearch\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\SearchRat\OnSiteSearch\FindPagesWithText;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class SearchController implements MiddlewareInterface
{
    protected $findPages;
    protected $defaultLimit;

    public function __construct(FindPagesWithText $findPages, $defaultLimit = 50)
    {
        $this->findPages = $findPages;
        $this->defaultLimit = $defaultLimit;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if (!isset($request->getQueryParams()['q'])) {
            return new HtmlResponse('Bad request - Missing "q" param.', 400);
        }

        return new JsonResponse($this->findPages->__invoke(
            $request->getUri()->getHost(),
            $request->getQueryParams()['q'],
            isset($request->getQueryParams()['limit']) ? $request->getQueryParams()['limit'] : $this->defaultLimit
        ));
    }
}
