<?php

namespace Reliv\SearchRat\OnSiteSearchExpressive;

use Reliv\SearchRat\Core\RepositoryInterface;
use Reliv\SearchRat\MySql\RepositoryFactoryWithDoctrineAdaptor;
use Reliv\SearchRat\OnSiteSearch\Cli\CrawlCommand;
use Reliv\SearchRat\OnSiteSearch\Controller\CrawlController;
use Reliv\SearchRat\OnSiteSearch\Controller\SearchController;
use Reliv\SearchRat\OnSiteSearch\ConvertHtmlToDocument;
use Reliv\SearchRat\OnSiteSearch\CrawlAllSitesByListers;
use Reliv\SearchRat\OnSiteSearch\CrawlAllSitesInterface;
use Reliv\SearchRat\OnSiteSearch\CrawlSiteByUrlList;
use Reliv\SearchRat\OnSiteSearch\FindPagesWithText;
use Reliv\SearchRat\OnSiteSearch\PageListerInterface;
use Reliv\SearchRat\OnSiteSearch\SiteListerInterface;
use Reliv\SearchRat\OnSiteSearch\SiteMapPageLister;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'console' => [
                'commands' => [
                    CrawlCommand::class => CrawlCommand::class,
                ],
            ],
            'routes' => [
                [
                    /**
                     * Example call:
                     * curl -X POST -k https://local.reliv.com/zrcms-site-search/crawl
                     */
                    'path' => '/zrcms-site-search/crawl',
                    'middleware' => CrawlController::class,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => '/zrcms-site-search/search',
                    'middleware' => SearchController::class,
                    'allowed_methods' => ['GET'],
                ],
            ],
            'dependencies' => [
                'config_factories' => [
                    FindPagesWithText::class => [
                        'arguments' => [
                            RepositoryInterface::class
                        ]
                    ],
                    SearchController::class => [
                        'arguments' => [
                            FindPagesWithText::class
                        ]
                    ],
                    ConvertHtmlToDocument::class => [],
                    CrawlSiteByUrlList::class => [
                        'arguments' => [
                            ConvertHtmlToDocument::class,
                            RepositoryInterface::class
                        ]
                    ],
                    CrawlAllSitesInterface::class => [
                        'class' => CrawlAllSitesByListers::class,
                        'arguments' => [
                            CrawlSiteByUrlList::class,
                            RepositoryInterface::class,
                            SiteListerInterface::class,
                            PageListerInterface::class
                        ]
                    ],
                    CrawlController::class => [
                        'arguments' => [
                            CrawlAllSitesInterface::class
                        ]
                    ],
                    CrawlCommand::class => [
                        'arguments' => [
                            CrawlAllSitesInterface::class
                        ]
                    ],
                    PageListerInterface::class => [
                        'class' => SiteMapPageLister::class
                    ],

                    RepositoryInterface::class => [
                        'factory' => RepositoryFactoryWithDoctrineAdaptor::class
                    ]
                ]
            ]
        ];
    }
}
