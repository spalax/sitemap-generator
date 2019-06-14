<?php
namespace SitemapGenerator;

class Sitemap implements SitemapInterface
{
    /**
     * Sitemap items
     *
     * @var SitemapItemInterface[]
     */
    protected $items = [];

    /**
     * @param SitemapItemInterface $item
     *
     * @return $this
     */
    public function addItem(SitemapItemInterface $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param string
     *
     * @return array
     */
    public function persist($fileToSave)
    {
        $cnt = ceil(count($this->items) / self::ITEM_PER_SITEMAP);

        $writtenFiles = [];
        for ($i = 0; $i < $cnt; $i ++) {
            $writtenFiles[] = $this->saveToFile($fileToSave,
                ($i * self::ITEM_PER_SITEMAP),
                ($i * self::ITEM_PER_SITEMAP) + self::ITEM_PER_SITEMAP,
                (!$i ? null : $i));
        }

        return $writtenFiles;
    }

    /**
     * @param string $fileToSave
     * @param int $offsetStart
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
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', self::SCHEMA);
        $writer->writeAttribute('xmlns:xhtml', self::SCHEMA_XHTML);

        $p = 0;
        /* @var $item SitemapItemInterface */
        for ($i = $offsetStart; $i < count($this->items) && $i < $limit; $i ++) {
            $item = $this->items[ $i ];

            $p++;

            $writer->startElement('url');
            $writer->writeElement('loc', $item->getLocation());
            if (!is_null($priority = $item->getPriority())) {
                $writer->writeElement('priority', $priority);
            }

            if (!is_null($freq = $item->getChangeFrequency())) {
                $writer->writeElement('changefreq', $freq);
            }
            if (!is_null($lastmod = $item->getLastModified())) {
                $writer->writeElement('lastmod', $lastmod);
            }

            foreach ($item->getAlternateTranslations() as $alternateTranslation) {
                $writer->startElement('xhtml:link');
                    $writer->writeAttribute('rel', 'alternate');
                    $writer->writeAttribute('hreflang', $alternateTranslation['language']);
                    $writer->writeAttribute('href', $alternateTranslation['href']);
                $writer->endElement();
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();

        return $filePath;
    }
}
