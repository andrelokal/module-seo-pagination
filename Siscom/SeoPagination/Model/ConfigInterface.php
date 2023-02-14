<?php

namespace Siscom\SeoPagination\Model;


interface ConfigInterface
{

    const XML_PATH_GENERA_ENABLE = 'catalog/seo/siscom_seo_pagination_enable';

    /**
     * @return bool
     */
    public function isEnable();
}
