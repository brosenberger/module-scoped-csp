
## Sample of a Scoped CSP Whitelist file:

scoped_csp_whitelist.xml:
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

## TODOs

- use XSD schema extension instead of XSD copy and extend of original csp_whitelist.xsd
- write better readme