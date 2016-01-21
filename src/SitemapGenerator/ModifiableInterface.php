<?php
namespace SitemapGenerator;

interface ModifiableInterface
{
    const MODIFIED_DATE_FORMAT = 'Y-m-d';

    /**
     * Get defined last modified dt
     * formatted according to Sitemap
     * standard .
     *
     * @link http://www.sitemaps.org/
     *
     * @return string
     */
    public function getLastModified();
}
