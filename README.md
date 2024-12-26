# Magento 2 Scoped CSP 

This module provides an additional configuration possibility for CSP whitelists with scope definition (website, store or storeview).

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/brosenberger)

## Installation

```
composer require brocode/module-scoped-csp
bin/magento setup:upgrade
```

The module should be enabled by default and does not need separate enabling.

## Configuration

Add a `scoped_csp_whitelist.xml` into your etc-folder containing all needed policies.

**Sample `scoped_csp_whitelist.xml`**:
````xml 
<?xml version="1.0" encoding="UTF-8"?>
<csp_whitelist xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:module:BroCode_ScopedCsp:etc/scoped_csp_whitelist.xsd">
        <policies>
            <policy id="img-src">
                <values>
                    <value id="data-brocode" scopeType="website" scopeCode="base" type="host">https://brocode.at</value>
                    <value id="data-other-brocode" scopeType="website" scopeCode="otherbase" type="host">https://other.brocode.at</value>
                </values>
            </policy>
        </policies>
</csp_whitelist>
````

If no scope type is given, the policy is applied to all scopes (so basically the same behavior as the default csp_whitelist.xml).

If a scope type other then `default` is given, a scope code must be set as well.

## TODOs

- use XSD schema extension instead of XSD copy and extend of original csp_whitelist.xsd
