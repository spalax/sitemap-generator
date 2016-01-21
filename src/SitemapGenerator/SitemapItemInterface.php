<?php
namespace SitemapGenerator;

interface SitemapItemInterface extends ModifiableInterface
{
    const DEFAULT_PRIORITY = 0.5;

    const FREQUENCY_ALWAYS = 'always';
    const FREQUENCY_HOURLY = 'hourly';
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_MONTHLY = 'monthly';
    const FREQUENCY_YEARLY = 'yearly';
    const FREQUENCY_NEVER = 'never';

    /**
     * Get Site Map item location URL
     *
     * @return string
     */
    public function getLocation();

    /**
     * Get defined domain name
     *
     * @return string | null
     */
    public function getDomain();

    /**
     * Get priority of the current item
     *
     * @return string | null
     */
    public function getPriority();

    /**
     * Get change frequency for current item
     *
     * @return string | null
     */
    public function getChangeFrequency();

    /**
     * Get defined last modified dt
     * formatted according to Sitemap
     * standard .
     *
     * @link http://www.sitemaps.org/
     *
     * @return string | null
     */
    public function getLastModified();
}
