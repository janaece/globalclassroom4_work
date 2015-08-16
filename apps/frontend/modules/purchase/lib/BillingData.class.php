<?php
class BillingData
{
	protected $first_name;
  	protected $last_name;
  	protected $address;
  	protected $city;
  	protected $state;
  	protected $country;
  	protected $zip;
  	
	function __construct($first_name, $last_name, $address, $city, $state, $country, $zip)
	{
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->address = $address;
		$this->city = $city;
		$this->state = $state;
		$this->country = $country;
		$this->zip= $zip;
	}
  	
	public function getFirstName()
  	{
  		return $this->first_name;
  	}
  	public function setFirstName($first_name)
  	{
  		$this->first_name = $first_name;
  	}
	public function getLastName()
  	{
  		return $this->last_name;
  	}
  	public function setLastName($last_name)
  	{
  		$this->last_name = $last_name;
  	}
	public function getAddress()
  	{
  		return $this->address;
  	}
  	public function setAddress($address)
  	{
  		$this->address = $address;
  	}
	public function getCity()
  	{
  		return $this->city;
  	}
  	public function setCity($city)
  	{
  		$this->city = $city;
  	}
	public function getState()
  	{
  		return $this->state;
  	}
  	public function setState($state)
  	{
  		$this->state = $state;
  	}
	public function getCountry()
  	{
  		return $this->country;
  	}
  	public function setCountry($country)
  	{
  		$this->country = $country;
  	}
	public function getZip()
  	{
  		return $this->zip;
  	}
  	public function setZip($zip)
  	{
  		$this->zip = $zip;
  	}
}