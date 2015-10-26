<?php
class Books extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'books';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		  'create_table' => array(
		    'books' => array(
		      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		      'name' => array('type'=>'string','null' => false),
                      'body' => array('type'=>'text','null' => false),
                      'indexes' => array(
                        'PRIMARY' => array('column' => 'id' , 'unique' => 1),
                      )
		    )
		  )
		),
		'down' => array(
		  'drop_table' => array( 'books' )
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
