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
 * EnterBoardingRequestType
 *
 * @package PayPal
 */
class EnterBoardingRequestType extends AbstractRequestType
{
    var $EnterBoardingRequestDetails;

    function EnterBoardingRequestType()
    {
        parent::AbstractRequestType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
        $this->_elements = array_merge($this->_elements,
            array (
              'EnterBoardingRequestDetails' => 
              array (
                'required' => true,
                'type' => 'EnterBoardingRequestDetailsType',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
            ));
    }

    function getEnterBoardingRequestDetails()
    {
        return $this->EnterBoardingRequestDetails;
    }
    function setEnterBoardingRequestDetails($EnterBoardingRequestDetails, $charset = 'iso-8859-1')
    {
        $this->EnterBoardingRequestDetails = $EnterBoardingRequestDetails;
        $this->_elements['EnterBoardingRequestDetails']['charset'] = $charset;
    }
}
