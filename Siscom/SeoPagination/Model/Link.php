<?php

namespace Siscom\SeoPagination\Model;

class Link
{
    /**
     * @var string
     */
    private $rel;
    /**
     * @var string
     */
    private $href;

    /**
     * Link constructor.
     *
     * @param $rel
     * @param $href
     */
    public function __construct($rel, $href)
    {
        $this->rel = $rel;
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }
}
