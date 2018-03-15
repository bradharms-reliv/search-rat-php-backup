<?php

namespace Reliv\SearchRat\MySql;

use Reliv\SearchRat\Core\Document;
use Reliv\SearchRat\Core\DocumentInterface;
use Reliv\SearchRat\Core\RepositoryInterface;

class MySqlRepository implements RepositoryInterface
{
    protected $pdo;
    protected $documentTableName;

    public function __construct(\PDO $pdo, $documentTableName = 'search_rat_documents')
    {
        $this->pdo = $pdo;
        $this->documentTableName = $documentTableName;
    }

    public function setDocuments(string $databaseId, array $documents)
    {
        $tempDatabaseId = $databaseId . '_temp_' . microtime(true) . '_' . rand();

        /**
         * @var DocumentInterface $document
         */
        foreach ($documents as $document) {
            //Insert each document into a temporary DB ID
            $stmt = $this->pdo->prepare('INSERT INTO `' . $this->documentTableName . '`'
                . ' (id, databaseId, title, text, metaData)'
                . ' VALUES (:id, :databaseId, :title, :text, :metaData)');
            $stmt->execute([
                'id' => $document->getId(),
                'databaseId' => $tempDatabaseId,
                'title' => $document->getTitle(),
                'text' => $document->getText(),
                'metaData' => '[]',
            ]);
        }

        //Delete any old documents
        $stmt = $this->pdo->prepare('DELETE FROM `' . $this->documentTableName . '`'
            . ' WHERE databaseId = :databaseId');
        $stmt->execute(['databaseId' => $databaseId]);

        //Rename our new documents into the correct DB ID
        $stmt = $this->pdo->prepare('UPDATE `' . $this->documentTableName . '`'
            . ' SET databaseId=:newDatabaseId'
            . ' WHERE databaseId = :oldDatabaseId');
        $stmt->execute([
            'oldDatabaseId' => $tempDatabaseId,
            'newDatabaseId' => $databaseId
        ]);
    }

    public function searchForDocuments(string $databaseId, string $searchText, $limit)
    {
        $stmt = $this->pdo->prepare('SELECT * , MATCH(title, text) AGAINST (:searchText) as relevance'
            . ' FROM search_rat_documents'
            . ' WHERE databaseId=:databaseId'
            . ' AND MATCH(title, text) AGAINST(:searchText)'
            . ' ORDER by relevance DESC'
            . ' LIMIT ' . (int)$limit);
        $stmt->execute([
            'databaseId' => $databaseId,
            'searchText' => $searchText
        ]);
        $results = $stmt->fetchAll();
        $documents = [];
        foreach ($results as $row) {
            $documents[] = new Document(
                $row['id'],
                $row['databaseId'],
                $row['title'],
                $row['text'],
                json_decode($row['metaData'])
            );
        }

        return $documents;
    }
}
