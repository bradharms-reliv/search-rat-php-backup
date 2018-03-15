<?php


namespace Reliv\SearchRat\OnSiteSearch;

use Psr\Log\LoggerInterface;

interface PageListerInterface
{
    /**
     * @param string $baseUrl
     * @param LoggerInterface $logger
     * @return array a list of page URLs as strings
     */
    public function __invoke(
        string $baseUrl,
        LoggerInterface $logger
    ): array;
}
