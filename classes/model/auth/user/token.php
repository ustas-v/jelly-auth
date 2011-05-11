<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * Jelly Auth User Token Model
 * @package Jelly Auth
 * @author	Israel Canasa
 */
abstract class Model_Auth_User_Token extends Jelly_Model
{
 	public static function initialize(Jelly_Meta $meta)
	{
        // The table the model is attached to
        $meta->table('users_tokens');

        // Fields defined by the model
        $meta->fields(array(
            'id' => Jelly::field('primary'),
			'user_agent' => Jelly::field('string'),
			'token' => Jelly::field('string', array(
				'unique' => TRUE,
			)),
			'type' => Jelly::field('string'),
			'created' => Jelly::field('timestamp', array(
				'auto_now_create' => TRUE,
			)),
			'expires' => Jelly::field('timestamp'),

            // Relationships to other models
            'user' => Jelly::field('belongsto'),
        ));
		
		if (mt_rand(1, 100) === 1)
		{
			// Do garbage collection
			Jelly::delete('user_token')->where('expires', '<', time())->execute();
		}
	}
	
	public function create()
	{		
		// Set hash of the user agent
		$this->user_agent = sha1(Request::$user_agent);

		// Create a new token each time the token is saved
		$this->token = $this->create_token();
		
		return parent::save();
	}
	
	public function update()
	{
		// Create a new token each time the token is saved
		$this->token = $this->create_token();
		
		return parent::save();
	}

	/**
	 * Finds a new unique token, using a loop to make sure that the token does
	 * not already exist in the database. This could potentially become an
	 * infinite loop, but the chances of that happening are very unlikely.
	 *
	 * @return  string
	 */
	public function create_token()
	{
		while (TRUE)
		{
			// Create a random token
			$token = text::random('alnum', 32);

			// Make sure the token does not already exist
			if( ! Jelly::select('user_token')->where('token', '=', $token)->count())
			{
				// A unique token has been found
				return $token;
			}
		}
	}
} // End Model_Auth_User_Token