<?php
class AddPriceIntoBooks extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_price_into_books';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'books' => array(
					'price' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'body'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'books' => array('price'),
			),
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
