<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace BroCode\ScopedCsp\Model\Collector\ScopedCspWhitelistXml;

use Magento\Framework\Config\ConverterInterface;

/**
 * Converts csp_whitelist.xml files' content into config data.
 */
class Converter implements ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convert($source)
    {
        $policyConfig = [];

        /** @var \DOMNodeList $policies */
        $policies = $source->getElementsByTagName('policy');
        /** @var \DOMElement $policy */
        foreach ($policies as $policy) {
            if ($policy->nodeType != XML_ELEMENT_NODE) {
                continue;
            }
            $id = $policy->attributes->getNamedItem('id')->nodeValue;
            if (!array_key_exists($id, $policyConfig)) {
                $policyConfig[$id] = ['hosts' => [], 'hashes' => []];
            }
            /** @var \DOMElement $value */
            foreach ($policy->getElementsByTagName('value') as $value) {
                if ($value->attributes->getNamedItem('type')->nodeValue === 'host') {
                    $policyConfig[$id]['hosts'][$value->attributes->getNamedItem('id')->nodeValue] = [
                        'value' => $value->nodeValue,
                        'id' => $value->attributes->getNamedItem('id')->nodeValue,
                        'scopeType' => $this->getOptionalAttribute($value, 'scopeType', 'default'),
                        'scopeCode' => $this->getOptionalAttribute($value, 'scopeCode')
                    ];
                } else {
                    $policyConfig[$id]['hashes'][$value->nodeValue] = [
                        'value' => $value->attributes->getNamedItem('algorithm')->nodeValue,
                        'id' => $value->attributes->getNamedItem('id')->nodeValue,
                        'scopeType' => $this->getOptionalAttribute($value, 'scopeType', 'default'),
                        'scopeCode' => $this->getOptionalAttribute($value, 'scopeCode')
                    ];
                }
            }
        }

        return $policyConfig;
    }

    protected function getOptionalAttribute($element, $attributeName, $defaultValue = null)
    {
        if ($element->hasAttribute($attributeName)) {
            return $element->getAttribute($attributeName);
        }
        return $defaultValue;
    }
}
