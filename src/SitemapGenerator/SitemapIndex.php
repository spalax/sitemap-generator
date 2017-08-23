<?php
namespace SitemapGenerator;

class SitemapIndex implements PersistingInterface
{
    const ITEM_PER_SITEMAP_INDEX = 1000;

    /**
     * @var SitemapInterface[]
     */
    protected $items = [];

    /**
     * @param SitemapInterface $sitemap
     * @param string $fileToSave
     * @param string $siteMapUrl
     * @param \DateTime|null $lastModified
     *
     * @return $this
     */
    public function addSitemap(
        SitemapInterface $sitemap,
        $fileToSave, $siteMapUrl,
        \DateTime $lastModified = null
    )
    {
        if (is_null($lastModified)) {
            $lastModified = new \DateTime();
        }

        $this->items[] = [
            'sitemap'  => $sitemap,
            'file'     => $fileToSave,
            'url'      => $siteMapUrl,
            'modified' => $lastModified
        ];

        return $this;
    }

    /**
     * @param string $fileToSave
     *
     * @return array
     */
    public function persist($fileToSave)
    {
        foreach ($this->items as &$item) {
            /* @var $sitemap SitemapInterface */
            $sitemap       = $item['sitemap'];
            $item['files'] = $sitemap->persist($item['file']);
        }

        $itemsCount = array_reduce($this->items, function ($carry, $item) {
            return count($item['files']) + $carry;
        }, 0);

        $cnt = ceil($itemsCount / self::ITEM_PER_SITEMAP_INDEX);

        $writtenFiles = [];
        for ($i = 0; $i < $cnt; $i ++) {
            $writtenFiles[] = $this->saveToFile($fileToSave,
                ($i * self::ITEM_PER_SITEMAP_INDEX),
                self::ITEM_PER_SITEMAP_INDEX,
                (!$i ? null : $i));
        }

        return $writtenFiles;
    }

    /**
     * @param string $fileToSave
     * @param number $offsetStart
     * @param number $limit
     * @param null | string $suffix
     *
     * @return string
     */
    protected function saveToFile($fileToSave, $offsetStart, $limit, $suffix = null)
    {
        $writer = new \XMLWriter();

        $path     = pathinfo($fileToSave);
        $filePath = $path['dirname'] . '/' . $path['filename'];

        if (!is_null($suffix)) {
            $filePath .= self::SEPERATOR . $suffix;
        }

        if (empty($path['extension'])) {
            $filePath .= self::XML_EXT;
        } else {
            $filePath .= '.' . $path['extension'];
        }

        $writer->openURI($filePath);

        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(true);
        $writer->startElement('sitemapindex');
        $writer->writeAttribute('xmlns', self::SCHEMA);

        for ($i = $offsetStart; $i < count($this->items) && $i < $limit; $i ++) {
            $item = $this->items[ $i ];

            if (count($item['files']) > 1) {
                foreach ($item['files'] as $file) {
                    $writer->startElement('sitemap');
                    $writer->writeElement('loc', dirname($item['url']) . '/' . basename($file));
                    $writer->writeElement('lastmod', $item['modified']->format(ModifiableInterface::MODIFIED_DATE_FORMAT));
                    $writer->endElement();
                }
            } else {
                $writer->startElement('sitemap');
                $writer->writeElement('loc', $item['url']);
                $writer->writeElement('lastmod',
                    $item['modified']->format(ModifiableInterface::MODIFIED_DATE_FORMAT));
                $writer->endElement();
            }
        }

        $writer->endElement();
        $writer->endDocument();

        return $filePath;
    }
}
