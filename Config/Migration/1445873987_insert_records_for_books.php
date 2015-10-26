<?php
class InsertRecordsForBooks extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'insert_records_for_books';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
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
	  $Book = ClassRegistry::init('Book');
          if ($direction === 'up') {
            // $data['Book'][0]['id'] = 1;
            $data = array( 
              'Book' => array( 
                'name' => 'CakePHP Book Sample',
                'body' => 'This is a content of this book!'
              )
            );
            $Book->create();
            if ($Book->save($data)) {
              $this->callback->out('book records are inserted');
            } else {
              
              $this->callback->out('book records insertion is failed!' . print_r( $Book->validationErrors , true)  );
            }
          } elseif ($direction === 'down') {
          }
          return true;
	}
}
