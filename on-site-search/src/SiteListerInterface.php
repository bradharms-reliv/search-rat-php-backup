<?php


namespace Reliv\SearchRat\OnSiteSearch;


use Psr\Log\LoggerInterface;

interface SiteListerInterface
{
    /**
     * @param LoggerInterface $logger
     * @return array a list of site URLs as strings
     */
    public function __invoke(LoggerInterface $logger): array;
}
