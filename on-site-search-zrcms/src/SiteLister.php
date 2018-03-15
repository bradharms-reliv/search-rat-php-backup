<?php

namespace Reliv\SearchRat\OnSiteSearchZrcms;

use Psr\Log\LoggerInterface;
use Reliv\RcmConfig\Service\ConfigService;
use Reliv\SearchRat\OnSiteSearch\SiteListerInterface;

class SiteLister implements SiteListerInterface
{
    protected $configService;

    public function __construct(
        ConfigService $configService
    ) {
        $this->configService = $configService;
    }

    public function __invoke(LoggerInterface $logger): array
    {
        return array_map(
            function ($domain) {
                return 'https://' . $domain;
            },
            $this->configService
                ->getValue('rcmCountryOptions', '_DEFAULT', 'search-crawl-domains')
        );
    }
}
