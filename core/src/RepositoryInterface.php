<?php

namespace Reliv\SearchRat\Core;

interface RepositoryInterface
{
    /**
     * @param string $databaseId
     * @param array $documents (is an array of DocumentInterfaces)
     * @return mixed
     */
    public function setDocuments(string $databaseId, array $documents);

    /**
     * @param string $databaseId
     * @param string $searchText
     * @param $limit
     * @return array of DocumentInterfaces's
     */
    public function searchForDocuments(string $databaseId, string $searchText, $limit);
}
