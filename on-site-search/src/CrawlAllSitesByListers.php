<?php

namespace Reliv\SearchRat\OnSiteSearch;

use Psr\Log\LoggerInterface;
use Reliv\SearchRat\Core\RepositoryInterface;

/**
 * NOTE: This gets the domain list from config_config for now but could  use something more dynamic after ZRCMS launch.
 *
 * Class CrawlAllSites
 * @package Zrcms\ZrcmsSiteSearch
 */
class CrawlAllSitesByListers
{
    protected $crawlSite;
    protected $repository;
    protected $siteLister;
    protected $pageLister;

    public function __construct(
        CrawlSiteByUrlList $crawlSite,
        RepositoryInterface $repository,
        SiteListerInterface $siteLister,
        PageListerInterface $pageLister
    ) {
        $this->crawlSite = $crawlSite;
        $this->repository = $repository;
        $this->siteLister = $siteLister;
        $this->pageLister = $pageLister;
    }

    public function __invoke(LoggerInterface $logger)
    {
        set_time_limit(0);

        $startTime = microtime(true);
        $logger->info('Starting.');

        $totalUrlCount = 0;

        $siteList = $this->siteLister->__invoke($logger);

        if (count($siteList) === 0) {
            $logger->warning('No sites found');
        }

        foreach ($siteList as $siteUrl) {
            $logger->info('Processing domain "' . $siteUrl . '".');
            $pageList = $this->pageLister->__invoke($siteUrl, $logger);
            if (count($pageList) === 0) {
                $logger->warning('No pages found for site URL "' . $siteUrl . '"');
            }
            $this->crawlSite->__invoke($siteUrl, $pageList, $logger);
            $totalUrlCount += count($pageList);
        }

        $logger->info(
            'Done. Processed ' . $totalUrlCount . ' URLs'
            . ' in ' . floor(microtime(true) - $startTime) . ' seconds.'
        );
    }
}
