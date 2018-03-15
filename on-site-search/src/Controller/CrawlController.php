<?php

namespace Reliv\SearchRat\OnSiteSearch\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zrcms\Logger\Service\LoggerNoop;
use Reliv\SearchRat\OnSiteSearch\CrawlAllSitesByListers;

class CrawlController implements MiddlewareInterface
{
    protected $crawl;

    public function __construct(CrawlAllSitesByListers $crawl)
    {
        $this->crawl = $crawl;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->crawl->__invoke(new LoggerNoop());

        return new HtmlResponse('200 OK');
    }
}
