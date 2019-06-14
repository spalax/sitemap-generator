<?php
namespace SitemapGenerator;

interface PersistingInterface
{
    const SEPERATOR = '-';
    const XML_EXT = '.xml';
    const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const SCHEMA_XHTML = 'http://www.w3.org/1999/xhtml';

    /**
     * @param string $fileToSave
     *
     * @return array
     */
    public function persist($fileToSave);
}
