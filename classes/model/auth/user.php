<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * Jelly Auth User Model
 * @package Jelly Auth
 * @author	Israel Canasa
 */
abstract class Model_Auth_User extends Jelly_Model
{
    public static function initialize(Jelly_Meta $meta)
    {
        $meta->table('users')
        ->name_key('username')
        ->sorting(array('username' => 'ASC'))
        
        // Fields defined by the model
        ->fields(array(
            'id' => Jelly::field('primary'),
            'email' => Jelly::field('email', array(
				'label' => 'E-mail',
				'rules' => array(
                    array('not_empty'),
                    array('min_length', array(':value', 4)),
                    array('max_length', array(':value', 127)),
                ),
				'unique' => TRUE,
            )),
            'username' => Jelly::field('string', array(
				'label' => 'Username',
				'rules' => array(
                    array('not_empty'),
                    array('min_length', array(':value', 4)),
                    array('max_length', array(':value', 32)),
                    array('regex', array(':value', '/^[-\pL\pN_.]++$/uD')),
                ),
				'unique' => TRUE,
            )),
			'password' => Jelly::field('password', array(
				'label' => 'Password',
				'rules' => array(
                    array('not_empty'),
                    array('min_length', array(':value', 8)),
                ),
				'hash_with' => array(Auth::instance(), 'hash'),
            )),
			'password_confirm' => Jelly::field('password', array(
			    'in_db'	=> FALSE,
				'label' => 'Password confirm',
				'rules' => array(
                    array('not_empty'),
                    array('matches', array(':validation', 'password', ':field')),
                ),
            )),
			'logins' => Jelly::field('integer', array(
				'default' => 0,
				'convert_empty' => TRUE,
				'empty_value' => 0,
            )),
			'last_login' => Jelly::field('timestamp'),

			
            // Relationships to other models
            'user_tokens' => Jelly::field('hasmany', array(
				'foreign' => 'user_token',
            )),
            'roles' => Jelly::field('manytomany', array(
            	'through' => array(
            		'model' => 'users_has_roles',
            		'columns' => array('user_id', 'role_id')
                )
            )),
        ));
    }


    /**
     * Check if user has a particular role
     * @param mixed $role 	Role to test for, can be Model_Role object, string role name of integer role id
     * @return bool			Whether or not the user has the requested role
     */
    public function has_role($role)
    {
        // Check what sort of argument we have been passed
        if ($role instanceof Model_Role)
        {
            $key = 'id';
            $val = $role->id;
        }
        elseif (is_string($role))
        {
            $key = 'name';
            $val = $role;
        }
        else
        {
            $key = 'id';
            $val = (int) $role;
        }

        foreach ($this->roles as $user_role)
        {
            if ($user_role->{$key} === $val)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

} // End Model_Auth_User