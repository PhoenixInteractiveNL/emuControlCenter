<?php
class PhpGtkEntryCompletion extends GtkEntry {
  protected $_list;
  protected $_model;
  protected $_completion;

  function __construct($list = null) {
    parent::__construct();
    $this->_list = $list;
    $this->create();

    if ($this->_list !== null)
      $this->completion_add_words($this->_list);
  }

  protected function create() {
    // Create a model
    $this->_model = new GtkListStore(Gtk::TYPE_STRING);

    // create completion entry
    $this->_completion = new GtkentryCompletion();
    $this->_completion->connect('match-selected', 
      array($this, 'completion_match_select'));
    $this->_completion->set_model($this->_model);
    $this->_completion->set_text_column(0);
    $this->set_completion($this->_completion);
  }

  function completion_add_word($word) {
    $this->_model->append(array($word));
  }

  function completion_add_words($words) {
    foreach($words as $word)
      $this->_model->append(array($word));
  }

  function completion_clear() {
    $this->_model->clear();
  }

  protected function completion_match($completion, $key, $iter) {
    $txt = $model->get_value($iter, 0);
    if (strpos($key, $txt, 0) === false)
      return false;
    return true;
  }

  public function completion_match_select(
    $completion, $model, $iter) {
    $txt = $model->get_value($iter, 0);
    $this->set_text($txt);
    $this->set_position(-1);
  }
}
?>