<?php

namespace Reliv\SearchRat\OnSiteSearch;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Psr\Log\LoggerInterface;
use RcmHtmlPurifier\Service\HtmlPurifier;
use Reliv\SearchRat\Core\Document;
use Reliv\SearchRat\Core\RepositoryInterface;

class CrawlSiteByUrlList
{
    protected $repository;
    protected $htmlToDocument;
    protected $concurrency;

    public function __construct(
        ConvertHtmlToDocument $htmlToDocument,
        RepositoryInterface $repository,
        $concurrency = 8
    ) {
        $this->htmlToDocument = $htmlToDocument;
        $this->repository = $repository;
        $this->concurrency = $concurrency;
    }

    public function __invoke(
        string $baseUrl,
        array $urlList,
        LoggerInterface $logger
    ) {
        $documents = [];
        $databaseId = parse_url($baseUrl)['host'];

        $logger->debug(
            'There are ' . count($urlList) . ' urls for "' . $databaseId . '"'
        );

        $client = new Client(['verify' => false, 'allow_redirects' => false]);

        $requests = function () use ($urlList, $client, $logger) {
            $urlCount = count($urlList);
            for ($i = 0; $i < $urlCount; $i++) {
                yield function () use ($client, $urlList, $i, $logger) {
                    $url = $urlList[$i];
                    $logger->debug('Requesting ' . $url);

                    return $client->getAsync($url);
                };
            }
        };

        $pool = new Pool(
            $client,
            $requests(),
            [
                'concurrency' => $this->concurrency,
                'fulfilled' => function ($response, $index) use (
                    $urlList,
                    $logger,
                    $baseUrl,
                    $databaseId,
                    &$documents
                ) {
                    if ($response->getStatusCode() != 200) {
                        return;
                    }
                    $url = $urlList[$index];
                    $logger->debug('Received ' . $url);
                    $html = $response->getBody()->getContents();
                    $documents[] = $this->htmlToDocument->__invoke($databaseId, explode($baseUrl, $url)[1], $html);
                },
                'rejected' => function ($reason, $index) use ($urlList, $logger) {
                    $logger->warning('Error on URL "' . $urlList[$index] . '"'/*.': ' . $reason*/);
                },
            ]
        );

        $pool->promise()->wait(true);

        $this->repository->setDocuments($databaseId, $documents);
    }
}
