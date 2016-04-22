<?php
namespace Jaybizzle\CrawlerDetect\Fixtures;

abstract class AbstractProvider
{
    public function getAll()
    {
        return $this->data;
    }
}