<?php
namespace SitemapGenerator;

interface SitemapInterface extends PersistingInterface
{
    const ITEM_PER_SITEMAP = 50000;

    /**
     * Add new item to the sitemap
     *
     * @param SitemapItemInterface $item
     *
     * @return void
     */
    public function addItem(SitemapItemInterface $item);
}
