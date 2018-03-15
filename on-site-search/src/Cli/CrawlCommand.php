<?php

namespace Reliv\SearchRat\OnSiteSearch\Cli;

use Reliv\SearchRat\OnSiteSearch\CrawlAllSitesByListers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zrcms\Importer\Logger\CliLogger;

/**
 * Example way to run on vagrant local:
 * cd /www/web; ENV="local" bin/console zrcms-site-search:crawl
 *
 * Class CrawlCommand
 * @package Reliv\SearchRat\OnSiteSearchExpressive\Controller
 */
class CrawlCommand extends Command
{
    protected $crawl;

    public function __construct(
        CrawlAllSitesByListers $crawl
    ) {
        $this->crawl = $crawl;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('zrcms-site-search:crawl')->setDescription('Crawl all sites for the site search index.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->crawl->__invoke(new CliLogger($output));
    }
}
