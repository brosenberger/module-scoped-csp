<?php
declare(strict_types=1);

namespace BroCode\ScopedCsp\Model\Collector\ScopedCspWhitelistXml;

use Magento\Framework\Module\Dir;
use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir\Reader;

/**
 * CSP whitelist config schema locator.
 */
class SchemaLocator implements SchemaLocatorInterface
{
    /**
     * Path to corresponding XSD file with validation rules for merged config and per file config.
     *
     * @var string
     */
    private $schema;

    /**
     * @param Reader $moduleReader
     */
    public function __construct(Reader $moduleReader)
    {
        $this->schema = $moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'BroCode_ScopedCsp')
            . '/scoped_csp_whitelist.xsd';
    }

    /**
     * @inheritDoc
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @inheritDoc
     */
    public function getPerFileSchema()
    {
        return $this->schema;
    }
}
