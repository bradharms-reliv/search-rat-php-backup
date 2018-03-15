<?php

namespace Reliv\SearchRat\OnSiteSearch;

use Psr\Log\LoggerInterface;

class SiteMapPageLister implements PageListerInterface
{
    public function __invoke(
        string $baseUrl,
        LoggerInterface $logger
    ): array {
        $logger->debug(
            'Requesting sitemap.xml for "' . $baseUrl . '"'
        );
        $siteMapXml = simplexml_load_string($this->httpGet($baseUrl . '/sitemap.xml'));
        $urls = [];

        if (!is_object($siteMapXml) || !property_exists($siteMapXml, 'url')) {
            //@TODO throw an exception here once all sites are working on local
            $logger->error('sitemap.xml could not be parsed for "' . $baseUrl . '"');

            return [];
        }

        foreach ($siteMapXml->url as $url) {
            $urls[] = (string)$url->loc;
        }

//        return array_splice($urls, 0, 3); //Uncomment for test mode

        return array_values(array_unique($urls));
    }

    protected function httpGet($url)
    {
        return @file_get_contents(
            $url,
            false,
            stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))
        );
    }
}
