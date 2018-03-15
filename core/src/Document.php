<?php

namespace Reliv\SearchRat\Core;

class Document implements DocumentInterface
{
    protected $id;
    protected $databaseId;
    protected $text;
    protected $title;
    protected $metaData;

    /**
     * Document constructor.
     * @param string $id
     * @param string $databaseId
     * @param string $title
     * @param string $text
     * @param array $metaData
     */
    public function __construct(string $id, string $databaseId, string $title, string $text, array $metaData = [])
    {
        $this->id = $id;
        $this->databaseId = $databaseId;
        $this->text = $text;
        $this->title = $title;
        $this->metaData = $metaData;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDatabaseId(): string
    {
        return $this->databaseId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }
}
