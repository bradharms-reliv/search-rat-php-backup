<?php


namespace Reliv\SearchRat\Core;

interface DocumentInterface
{
    /**
     * DocumentInterface constructor.
     * @param string $id
     * @param string $databaseId
     * @param string $title
     * @param string $text
     * @param array $metaData
     */
    public function __construct(string $id, string $databaseId, string $title, string $text, array $metaData = []);

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getDatabaseId(): string;

    /**¬
     * @return string
     */
    public function getTitle(): string;

    /**¬
     * @return string
     */
    public function getText(): string;

    /**
     * @return array
     */
    public function getMetaData(): array;
}
