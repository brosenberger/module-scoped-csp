<?php

namespace BroCode\ScopedCsp\Model\Collector;

use BroCode\ScopedCsp\Api\Exceptions\ScopeMisconfigurationException;
use Magento\Csp\Api\PolicyCollectorInterface;
use Magento\Csp\Model\Policy\FetchPolicy;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Config\DataInterface as ConfigReader;
use Magento\Framework\Config\Scope;
use Magento\Store\Model\StoreManagerInterface;

class ScopedCspWhitelistXmlCollector implements PolicyCollectorInterface
{
    /**
     * @var ConfigReader
     */
    private $configReader;
    private StoreManagerInterface $storeManager;

    /**
     * @param ConfigReader $configReader
     */
    public function __construct(
        ConfigReader $configReader,
        StoreManagerInterface $storeManager
    ) {
        $this->configReader = $configReader;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function collect(array $defaultPolicies = []): array
    {
        $policies = $defaultPolicies;
        $config = $this->configReader->get(null);
        foreach ($config as $policyId => $values) {
            if ($this->isPolicyAppliable($policyId, $values)) {
                $policies[] = new FetchPolicy(
                    $policyId,
                    false,
                    $values['hosts'],
                    [],
                    false,
                    false,
                    false,
                    [],
                    $values['hashes'],
                    false,
                    false
                );
            }
        }

        return $policies;
    }

    protected function isPolicyAppliable($policyId, array &$values)
    {
        $values['hosts'] = array_map(function($host) {
                return $host['value'];
            },
            array_filter($values['hosts'], function($host) use ($policyId) {
                return $this->filterCorrectScopedValues($policyId, $host);
            }
        ));
        $values['hashes'] = array_map(function($hash) {
                return $hash['value'];
            }, array_filter($values['hashes'], function($hash) use ($policyId) {
                return $this->filterCorrectScopedValues($policyId, $hash);
            }
        ));
        return count($values['hosts']) > 0 || count($values['hashes']) > 0;
    }

    protected function filterCorrectScopedValues($policyId, array $values) {
        // if it is a scopetype == default, it should be available everytime
        if (!isset($values['scopeType']) || $values['scopeType'] === ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
            return true;
        }
        $scopeType = $values['scopeType'];
        $scopeCode = $values['scopeCode'];

        if (empty($scopeCode)) {
            throw new ScopeMisconfigurationException(sprintf(
                'Policy ID %s (%s -> %s) missing scope code for scope type %s',
                $policyId,
                $values['id'],
                $values['value'],
                $scopeType
            ));
        }

        // check if store code should be checked - easy
        if ($scopeType == 'store' || $scopeType == 'stores') {
            return $this->storeManager->getStore()->getCode() === $scopeCode;
        }
        if ($scopeType == 'website' || $scopeType == 'websites') {
            return $this->storeManager->getWebsite()->getCode() === $scopeCode;
        }
        if ($scopeType == 'group' || $scopeType == 'groups') {
            return $this->storeManager->getGroup()->getCode() === $scopeCode;
        }

        return false;
    }
}
