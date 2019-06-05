<?php
namespace SitemapGenerator;

class SitemapItem implements SitemapItemInterface
{
    /**
     * @var string
     */
    protected $loc = '';

    /**
     * @var null|string
     */
    protected $domain = null;

    /**
     * @var null|string
     */
    protected $priority = null;

    /**
     * @var null|string
     */
    protected $lastModified = null;

    /**
     * @var string | null
     */
    protected $changeFrequency = null;

    /**
     * @var array
     */
    protected $alternateTranslations = [];

    /**
     * SitemapItem constructor.
     *
     * @param $loc
     * @param string $domain
     * @param float $priority
     * @param null $changeFrequency
     * @param \DateTime|null $lastModified
     * @param array $alternateTranslations
     */
    public function __construct(
        $loc, $domain = '', $priority = self::DEFAULT_PRIORITY,
        $changeFrequency = null, \DateTime $lastModified = null,
        array $alternateTranslations = []
    ) {
        $this->loc = $loc;
        if (!empty($domain)) {
            $this->loc = $domain . $loc;
        }

        $this->priority = $priority;

        if (!is_null($changeFrequency)) {
            $this->changeFrequency = $changeFrequency;
        }
        if (!is_null($lastModified)) {
            $this->lastModified = $lastModified->format(self::MODIFIED_DATE_FORMAT);
        }

        $this->alternateTranslations = $alternateTranslations;
        return $this;
    }

    /**
     * @return array
     */
    public function getAlternateTranslations()
    {
        return $this->alternateTranslations;
    }

    /**
     * Get Site Map item location URL
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->loc;
    }

    /**
     * Get defined domain name
     *
     * @return string | null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Get priority of the current item
     *
     * @return string | null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get defined last modified dt
     * formatted according to Sitemap
     * standard .
     *
     * @link http://www.sitemaps.org/
     *
     * @return string | null
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Get change frequency for current item
     *
     * @return string | null
     */
    public function getChangeFrequency()
    {
        return $this->changeFrequency;
    }
}
