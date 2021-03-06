<?php
/*
 * This file is part of the prestaPaypalPlugin package.
 * (c) Matthieu CRINQUAND <mcrinquand@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'PayPal/Type/AbstractRequestType.php';

/**
 * GetMobileStatusRequestType
 *
 * @package PayPal
 */
class GetMobileStatusRequestType extends AbstractRequestType
{
    var $GetMobileStatusRequestDetails;

    function GetMobileStatusRequestType()
    {
        parent::AbstractRequestType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
        $this->_elements = array_merge($this->_elements,
            array (
              'GetMobileStatusRequestDetails' => 
              array (
                'required' => true,
                'type' => 'GetMobileStatusRequestDetailsType',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
            ));
    }

    function getGetMobileStatusRequestDetails()
    {
        return $this->GetMobileStatusRequestDetails;
    }
    function setGetMobileStatusRequestDetails($GetMobileStatusRequestDetails, $charset = 'iso-8859-1')
    {
        $this->GetMobileStatusRequestDetails = $GetMobileStatusRequestDetails;
        $this->_elements['GetMobileStatusRequestDetails']['charset'] = $charset;
    }
}
