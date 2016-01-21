<?php
namespace SitemapGenerator;

interface PersistingInterface
{
    const SEPERATOR = '-';
    const XML_EXT = '.xml';
    const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * @param string $fileToSave
     *
     * @return array
     */
    public function persist($fileToSave);
}
