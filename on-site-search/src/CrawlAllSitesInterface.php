<?php

namespace Reliv\SearchRat\OnSiteSearch;

use Psr\Log\LoggerInterface;

interface CrawlAllSitesInterface
{
    public function __invoke(LoggerInterface $logger);
}
