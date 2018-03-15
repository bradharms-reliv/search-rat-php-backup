<?php

namespace Reliv\SearchRat\OnSiteSearchZrcms;

use Reliv\RcmConfig\Service\ConfigService;
use Reliv\SearchRat\OnSiteSearch\SiteListerInterface;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    SiteListerInterface::class => [
                        'class' => SiteLister::class,
                        'arguments' => [
                            ConfigService::class
                        ]
                    ],
                ]
            ]
        ];
    }
}
