<?php

namespace Reliv\SearchRat\OnSiteSearch;

use Reliv\SearchRat\Core\Document;

class ConvertHtmlToDocument
{
    protected $htmlPurifier;

    public function __construct()
    {
        $this->htmlPurifier = new \HTMLPurifier();
    }

    public function __invoke($databaseId, $path, $html)
    {
        $text = $html;
        $title = preg_match('!<title.*>(.*?)</title>!i', $text, $matches) ? $matches[1] : '';
        $text = str_replace('>', '> ', $text);//Prevent words from touching each-other
        $text = preg_replace(' /\{\{(.+?)\}\}/', '', $text); //replace angular vars away
        $text = preg_replace('#<header(.*?)>(.*?)</header>#is', '', $text);
        $text = preg_replace('#<footer(.*?)>(.*?)</footer>#is', '', $text);
        $text = preg_replace('#<nav(.*?)>(.*?)</nav>#is', '', $text);
        $text = str_replace(['&nbsp;', "\xc2\xa0", "\n"], ' ', $text);
        $text = $this->htmlPurifier->purify($text); //Get rid of any JS
        $text = strip_tags($text);
        $text = preg_replace('/\s\s+/', ' ', $text); //get rid of extra white space
        $text = trim($text);

        return new Document(
            $path,
            $databaseId,
            $title,
            $text
        );
    }
}
