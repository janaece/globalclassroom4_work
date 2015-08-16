<?php

class GcrAutoLoginTable extends Doctrine_Table
{
	// This function generates a unique token which is 128 char long and uses upper and lower case
	// alphanumeric chars. This is used to authorize an admin who is administering other eschools.
	// Since the token is generated right before the user is redirected, only 30 sec is given to use
	// the token before it expires.
	public static function generateToken ()
	{
		return GcrEschoolTable::generateRandomString() . GcrEschoolTable::generateRandomString();
	}
}
