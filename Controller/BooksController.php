<?php 

class BooksController extends AppController {
  public $helpers = array('Html','Form');

  public function index() {
   $this->set('books', $this->Book->find('all') );

  }
}

?>


