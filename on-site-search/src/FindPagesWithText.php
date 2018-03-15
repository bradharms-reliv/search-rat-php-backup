<?php

namespace Reliv\SearchRat\OnSiteSearch;

use Reliv\SearchRat\Core\DocumentInterface;
use Reliv\SearchRat\Core\RepositoryInterface;

class FindPagesWithText
{
    protected $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($hostName, $searchText, $limit = PHP_INT_MAX, $maxExcerptLength = 320)
    {
        $documents = $this->repository->searchForDocuments($hostName, $searchText, $limit);

        $pages = [];
        /**
         * @var DocumentInterface $document
         */
        foreach ($documents as $document) {
            $excerpt = $document->getText();

            if (strlen($excerpt) > $maxExcerptLength) {
                $excerpt = mb_substr($document->getText(), 0, $maxExcerptLength - 4) . ' ...';
            }

            $pages[] = [
                'url' => 'https://' . $document->getDatabaseId() . $document->getId(),
                'title' => $document->getTitle(),
                'excerpt' => $excerpt
            ];
        }

        return $pages;
    }
}
