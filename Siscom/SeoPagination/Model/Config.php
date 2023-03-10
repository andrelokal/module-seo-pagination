<?php

namespace Siscom\SeoPagination\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->getValue(self::XML_PATH_GENERA_ENABLE);
    }

    /**
     * @return ScopeConfigInterface
     */
    public function getScopeConfig()
    {
        return $this->scopeConfig;
    }

    /**
     * Retrieve config value by path
     *
     * @param string $path
     *
     * @return mixed
     * @api
     */
    public function getValue($path)
    {
        return $this->getScopeConfig()->getValue($path);
    }
}
