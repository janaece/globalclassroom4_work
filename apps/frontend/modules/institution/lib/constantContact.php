<?php
/**
 * constantContact class
 * 
 * @author		Steven Nelson
 * Last modified:  May 19, 2010
 * 
 * This class will provide integration to Constant Contact for marketing and customer tracking.
 * 
 * Original 
 * @author		ConstantContact Dev Team
 * @version 2.0.0
 * @since 30.03.2010
 */

class constantContact {
	
	//API credentials
	private $login = gcr::CC_USER; //Username for your account CC_USER
	private $password = gcr::CC_PASS;  //Password for your account CC_PASS
	private $apikey = gcr::CC_APIKEY; // API key for your account //CC_APIKEY
	
	//CONTACT LIST OPTIONS:
	//TODO set which list will be used!
	private $contact_lists = array(gcr::CC_LIST); //define which lists will be available for sign-up
	private $force_lists = false; // Set this to true to take away the ability for users to select and de-select lists
	private $show_contact_lists = true; // Set this to false to hid the list name(s) on the sign-up form.
	
	//FORM OPT IN SOURCE
	private $actionBy = 'ACTION_BY_CUSTOMER';
	//ACTION_BY_CUSTOMER - Constant Contact Account holder.  Used in internal applications.
	//ACTION_BY_CONTACT - Action by Site visitor.  Used in web site sign-up forms.
	
	//for debugging
	private $curl_debug = true; // Set this to true to see the response code returned by cURL
	
	private $requestLogin;  //this contains full authentication string.
	private $lastError = ''; //this variable will contain last error message (if any)
	private $apiPath = 'https://api.constantcontact.com/ws/customers/'; //is used for server calls
	private $doNotIncludeLists = array('Removed', 'Do Not Mail', 'Active'); // define which lists shouldn't be returned
	
	public function __construct() {
		//login string must be created on object initialization
		$this->requestLogin		= $this->apikey."%".$this->login.":".$this->password;
		$this->apiPath 			= $this->apiPath.$this->login;	
	}

	protected function isValidEmail($email){
		return preg_match("~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$~", $email);
	}
	
	/**
	* Private function used to send requests to ConstantContact server
	* @param string $request - is the URL where the request will be made
	* @param string $parameter - if it is not empty then this parameter will be sent using POST method
	* @param string $type - GET/POST/PUT/DELETE
	* @return a string containing server output/response
	*/    	
	protected function doServerCall($request, $parameter = '', $type = "GET") {
		$ch = curl_init();
		$request = str_replace('http://', 'https://', $request);
		//Convert id URI to BASIC compliant
		curl_setopt($ch, CURLOPT_URL, $request);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $this->requestLogin);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:application/atom+xml"));
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		switch ($type) {
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
				break;
			case 'PUT':
				$tmpfile = tmpfile();
				fwrite($tmpfile, $parameter);
				fseek($tmpfile, 0);
				curl_setopt($ch, CURLOPT_INFILE, $tmpfile);
				curl_setopt($ch, CURLOPT_PUT, 1);
				curl_setopt($ch, CURLOPT_INFILESIZE, strlen($parameter));
				fclose($tmpfile);
				break;
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
			default:
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				break;	
		}
		
		$emessage = curl_exec($ch);
		if ($this->curl_debug) { echo $error = curl_error($ch); }
		curl_close($ch);
		
		return $emessage;
	}

	/**
	 * Recursive Method that retrieves all the Email Lists from ConstantContact.
	 * @param string $path [default is empty]
	 * @return array of lists
	 */
	public function getLists($path = '') {
		$mailLists = array();
		
		if(empty($path)) {
			$call = $this->apiPath.'/lists';
		} else {
			$call = $path;
		}
		
		$response = $this->doServerCall($call);
		$parsedResponse = simplexml_load_string($response);
		$call2 = '';
		
		foreach ($parsedResponse->link as $item) {
			$tmp = $item->Attributes();
			$nextURL = '';
			if ((string) $tmp->rel == 'next') {
				$nextUrl = (string) $tmp->href;
				$arrTmp = explode($this->login, $nextUrl);
				$nextUrl = $arrTmp[1];
				$call2 = $this->apiPath.$nextUrl;
				break;
			}
		}
		
		foreach ($parsedResponse->entry as $item) {
			if($this->contact_lists){
				if(in_array((string) $item->title, $this->contact_lists)) {
					$tmp = array();
					$tmp['id'] = (string) $item->id;
					$tmp['title'] = (string) $item->title;
					$mailLists[] = $tmp;
				}
			} elseif (!in_array((string) $item->title, $this->doNotIncludeLists)) {
				$tmp = array();
				$tmp['id'] = (string) $item->id;
				$tmp['title'] = (string) $item->title;
				$mailLists[] = $tmp;
			}
		}
		
		if(empty($call2)) {
			return $mailLists;
		} else {
			return array_merge($mailLists, $this->getLists($call2));
		}
	}
	
	/**
	 * Method that retrieves all Registered Email Addresses.
	 * @param string $email_id [default is empty]
	 * @return array of lists
	 */
	public function getAccountLists($email_id = '') {
		$mailAccountList = array();

		if(empty($email_id)) {
			$call = $this->apiPath.'/settings/emailaddresses';
		} else {
			$call = $this->apiPath.'/settings/emailaddresses/'.$email_id;
		}
		
		$response = $this->doServerCall($call);
		$parsedResponse = simplexml_load_string($response);
		
		foreach ($parsedResponse->entry as $item) {
			$nextStatus			= $item->content->Email->Status;
			$nextEmail 			= (string) $item->title;
			$nextId 			= $item->id;
			$nextAccountList	= array('Email'=>$nextEmail, 'Id' => $nextId);
			if($nextStatus == 'Verified') {
				$mailAccountList[] = $nextAccountList;
			}
		}
		return $mailAccountList;
	}

	/* FUNCTIONS USED FOR CONTACT MANAGEMENT
	
	/**
	 * Method that checks if a subscriber already exists
	 * @param string $email
	 * @return subscriber's id if it exists or false if it doesn't
	 */
	public function subscriberExists($email = '') {
		$call = $this->apiPath.'/contacts?email='.$email;
		$response = $this->doServerCall($call);
		$xml = simplexml_load_string($response);
		$id = $xml->entry->id;
		if($id) { return $id; }
		else { return false; }
	}
	
	/**
	 * Method that retrieves from Constant Contact a collection with all the Subscribers
	 * If email parameter is mentioned than only mentioned contact is retrieved.
	 * @param string $email
	 * @return Bi-Dimensional array with information about contacts.
	 */
	public function getSubscribers($email = '', $page = '') {
		$contacts = array();
		$contacts['items'] = array();
		
		if (!empty($email)) {
			$call = $this->apiPath.'/contacts?email='.$email;
		} else {
			if(!empty($page)) {
				$call = $this->apiPath.$page;
			} else {
				$call = $this->apiPath.'/contacts';
			}
		}
		
		$response = $this->doServerCall($call);
		$parsedResponse = simplexml_load_string($response);
		//We parse here the link array to establish which are the next page and previous page
		foreach ($parsedResponse->link as $item) {
			$attributes = $item->Attributes();
			
			if (!empty($attributes['rel']) && $attributes['rel'] == 'next') {
				$tmp = explode($this->login, $attributes['href']);
				$contacts['next'] = $tmp[1];
			}
			if (!empty($attributes['rel']) && $attributes['rel'] == 'first') {
				$tmp = explode($this->login, $attributes['href']);
				$contacts['first'] = $tmp[1];
			}
			if (!empty($attributes['rel']) && $attributes['rel'] == 'current') {
				$tmp = explode($this->login, $attributes['href']);
				$contacts['current'] = $tmp[1];
			}
		}
		
		foreach ($parsedResponse->entry as $item) {
			$tmp = array();
			$tmp['id'] = (string) $item->id;
			$tmp['title'] = (string) $item->title;
			$tmp['status'] = (string) $item->content->Contact->Status;
			$tmp['EmailAddress'] = (string) $item->content->Contact->EmailAddress;
			$tmp['EmailType'] = (string) $item->content->Contact->EmailType;
			$tmp['Name'] = (string) $item->content->Contact->Name;
			$contacts['items'][] = $tmp;
		}
		return $contacts;
	}
	
	/**
	 * Retrieves all the details for a specific contact identified by $email
	 * We may need this in the future, if so reference cc_class.php
	 * @param string $email
	 * @return array with all information about the contact.
	 */
	public function getSubscriberDetails($email) {
		$contact = $this->getSubscribers($email);
		$fullContact = array();
		$call = str_replace('http://', 'https://', $contact['items'][0]['id']);
		//Convert id URI to BASIC compliant
		$response = $this->doServerCall($call);
		$parsedResponse = simplexml_load_string($response);
		$fullContact['id'] = $parsedResponse->id;
		$fullContact['email_address'] = $parsedResponse->content->Contact->EmailAddress;
		$fullContact['first_name'] = $parsedResponse->content->Contact->FirstName;
		$fullContact['last_name'] = $parsedResponse->content->Contact->LastName;
		$fullContact['middle_name'] = $parsedResponse->content->Contact->MiddleName;
		$fullContact['company_name'] = $parsedResponse->content->Contact->CompanyName;
		$fullContact['job_title'] = $parsedResponse->content->Contact->JobTitle;
		$fullContact['home_number'] = $parsedResponse->content->Contact->HomePhone;
		$fullContact['work_number'] = $parsedResponse->content->Contact->WorkPhone;
		$fullContact['address_line_1'] = $parsedResponse->content->Contact->Addr1;
		$fullContact['address_line_2'] = $parsedResponse->content->Contact->Addr2;
		$fullContact['address_line_3'] = $parsedResponse->content->Contact->Addr3;
		$fullContact['city_name'] = $parsedResponse->content->Contact->City;
		$fullContact['state_code'] = $parsedResponse->content->Contact->StateCode;
		$fullContact['state_name'] = $parsedResponse->content->Contact->StateName;
		$fullContact['country_code'] = $parsedResponse->content->Contact->CountryCode;
		$fullContact['zip_code'] = $parsedResponse->content->Contact->PostalCode;
		$fullContact['sub_zip_code'] = $parsedResponse->content->Contact->SubPostalCode;
		$fullContact['custom_field_1'] = $parsedResponse->content->Contact->CustomField1;
		//etc....
		$fullContact['notes'] = $parsedResponse->content->Contact->Note;
		$fullContact['mail_type'] = $parsedResponse->content->Contact->EmailType;
		$fullContact['status'] = $parsedResponse->content->Contact->Status;
		$fullContact['lists'] = array();
		
		if($parsedResponse->content->Contact->ContactLists->ContactList) {
			foreach ($parsedResponse->content->Contact->ContactLists->ContactList as $item) {
				$fullContact['lists'][] = trim( (string) $item->Attributes());
			}
		}
		return $fullContact;
	}
	
	/**
	 * Method that modifies a contact State to DO NOT MAIL
	 * @param string $email - contact email address
	 * @return TRUE in case of success or FALSE otherwise
	 */
	public function deleteSubscriber($email) {
		if(empty($email)) { return false; }
		$contact = $this->getSubscribers($email);
		$id = $contact['items'][0]['id'];
		$response = $this->doServerCall($id, '', 'DELETE');
		if(!empty($response)) { return false; }
		return true;
	}

	/**
	 * Method that modifies a contact State to REMOVED
	 * @param string $email - contact email address
	 * @return TRUE in calse of success of FALSE otherwise
	 */
	public function removeSubscriber($email) {
		$contact = $this->getSubscriberDetails($email);
		$contact['lists'] = array();
		$xml = $this->createContactXML($contact['id'], $contact);
		
		if ($this->editSubscriber($contact['id'], $xml)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Upload a new contact to Constant Contact server
	 * @param $contactXML - formatted XML with contact information
	 * @return TRUE in case of success or FALSE otherwise
	 */
	public function addSubscriber($contactXML) {
		$call = $this->apiPath.'/contacts';
		$response = $this->doServerCall($call, $contactXML, 'POST');
		$parsedResponse = simplexml_load_string($response);
		
		if ($response) {
			return true;
		} else {
			$xml = simplexml_load_string($contactXML);
			$emailAddress = $xml->content->Contact->EmailAddress;
			if ($this->subscriberExists($emailAddress)) {
				$this->lastError = 'This contact already exists.';
			} else { $this->lastError = 'While trying to add a Subscriber, An Error Occurred'; }
			return false;
		}
	}
	
	/**
	 * Modifies a contact
	 * @param string $contactUrl - identifies the url for the modified contact
	 * @param string $contactXML - formed XML with contact information
	 * @return TRUE in case of success or FALSE otherwise
	 */
	public function editSubscriber($contactUrl, $contactXML) {
		$response = $this->doServerCall($contactUrl, $contactXML, 'PUT');
		
		if (!empty($response)) {
			if (strpos($response, '<') !== false) {
				$parsedResponse = simplexml_load_string($response);
				
				if (!empty($parsedResponse->message)) {
					$this->lastError = $parsedReturn->message;
				}
			} else {
				$this->lastError = $parsedResponse->message;
			}
			return false;
		}
		return true;
	}
	
	/** Method that compose the needed XML format for a contact
	 * @param string $id
	 * @param array $params
	 * @return Formed XML
	 */
	public function createContactXML($id, $params = array()) {
		if (empty($id)) {
			$id = "urn:uuid:E8553C09F4XCVXCCC53F481214230867087";
		}
		
		$update_date		= date("Y-m-d").'T'.date("H:i:s").'+01:00';
		$xml_string			= "<entry xmlns='http://www.w3.org/2005/Atom'></entry>";
		$xml_object			= simplexml_load_string($xml_string);
		$title_node			= $xml_object->addChild("title", htmlspecialchars(""));
		$title_node->addAttribute("type", "text");
		$updated_node		= $xml_object->addChild("updated", htmlspecialchars($update_date));
		$author_node		= $xml_object->addChild("author");
		$author_name		= $author_node->addChild("name", htmlspecialchars(""));
		$id_node			= $xml_object->addChild("id", $id);
		$summary_node		= $xml_object->addChild("summary", htmlspecialchars("Entry"));
		$summary_node->addAttribute("type", "text");
		$content_node		= $xml_object->addChild("content");
		$content_node->addAttribute("type", "application/vnd.ctct+xml");
		$contact_node		= $content_node->addChild("Contact", htmlspecialchars("Customer Contact"));
		$contact_node->addAttribute("xmlns", "http://ws.constantcontact.com/ns/1.0/");
		$email_node			= $contact_node->addChild("EmailAddress", htmlspecialchars($params['email_address']));
		$fname_node			= $contact_node->addChild("FirstName", urldecode(htmlspecialchars($params['first_name'])));
		$lname_node			= $contact_node->addChild("LastName", urldecode(htmlspecialchars($params['last_name'])));
		//$mname_node		= $contact_node->addChild("MiddleName", urldecode(htmlspecialchars($params['middle_name'])));
		//$cname_node		= $contact_node->addChild("CompanyName", urldecode(htmlspecialchars($params['company_name'])));
		//$jname_node		= $contact_node->addChild("JobTitle", urldecode(htmlspecialchars($params['job_title'])));	
		$optin_node			= $contact_node->addChild("OptInSource", htmlspecialchars($this->actionBy));
		$hn_node			= $contact_node->addChild("HomePhone", htmlspecialchars($params['home_number']));
		$wn_node			= $contact_node->addChild("WorkPhone", htmlspecialchars($params['work_number']));
		$ad1_node			= $contact_node->addChild("Addr1", htmlspecialchars($params['address_line_1']));
		$ad2_node			= $contact_node->addChild("Addr2", htmlspecialchars($params['address_line_2']));
		//$ad3_node			= $contact_node->addChild("Addr3", htmlspecialchars($params['address_line_3']));
		$city_node			= $contact_node->addChild("City", htmlspecialchars($params['city_name']));
		//$state_node		= $contact_node->addChild("StateCode", htmlspecialchars($params['state_code']));
		$state_name			= $contact_node->addChild("StateName", htmlspecialchars($params['state_name']));
		$ctry_node			= $contact_node->addChild("CountryCode", htmlspecialchars($params['country_code']));
		$zip_node			= $contact_node->addChild("PostalCode", htmlspecialchars($params['zip_code']));
		//$subzip_node		= $contact_node->addChild("SubPostalCode", htmlspecialchars($params['sub_zip_code']));
		//$note_node		= $contact_node->addChild("Note", htmlspecialchars($params['notes']));
		//$emailtype_node	= $contact_node->addChild("EmailType", htmlspecialchars($params['mail_type']));
		
		//if (!empty($params['custom_fields'])) {
		//	foreach ($params['custom_fields'] as $k=>$v) {
		//		$contact_node->addChild("CustomField".$k, htmlspecialchars($v));
		//	}
		//}
		
		$contactlists_node = $contact_node->addChild("ContactLists");
		$contactlist_node = $contactlists_node->addChild("ContactList");
		if (!empty($params['lists'])) {
			foreach($params['lists'] as $list) {
				$contactlist_node->addAttribute("id", $list['id']);	
			}
		}
		
		$entry = $xml_object->asXML();
		return $entry;
	}
	
}	