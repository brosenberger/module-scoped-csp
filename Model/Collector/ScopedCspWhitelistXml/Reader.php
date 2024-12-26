<?php

declare(strict_types=1);

namespace BroCode\ScopedCsp\Model\Collector\ScopedCspWhitelistXml;

use Magento\Framework\Config\Reader\Filesystem;

/**
 * Config reader for csp_whitelist.xml files.
 */
class Reader extends Filesystem
{
    /**
     * List of id attributes for merge
     *
     * @var array
     */
    protected $_idAttributes = [
        '/csp_whitelist/policies/policy' => ['id'],
        '/csp_whitelist/policies/policy/values/value' => ['id']
    ];
}
