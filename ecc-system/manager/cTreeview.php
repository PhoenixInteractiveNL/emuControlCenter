<?php
#define("MY_MASK", Gdk::BUTTON_PRESS_MASK);

/**
 * Class creates and updates the different GtkTreeView instances
 *
 */
class Treeview {
	
	private $treeView;
	
	public function init(&$parent, $isIconView = false){
		
		# parent available?
		if (!$parent  || !is_object($parent)) throw new Exception('ERROR!');
		
		if (!$isIconView) {
			# reset, if allready initialized
			if ($this->treeView && $this->treeView instanceof GtkTreeView) $this->treeView = NULL;
			# create new treeview and add to parent
			$this->treeView = new GtkTreeView();
		}
		else {
			# reset, if allready initialized
			if ($this->treeView && $this->treeView instanceof GtkIconView) $this->treeView = NULL;
			# create new treeview and add to parent
			$this->treeView = new GtkIconView();			
		}
				
		if ($parent->child) $parent->remove($parent->child);
		$parent->add($this->treeView);

		return $this->treeView;
	}
	
	public function getTreeView(){
		return $this->treeView;
	}
	
	public function setModel(&$model){
		$this->treeView->set_model($model);
		$this->treeView->show();
		return $this->treeView;
	}
	
	public function connect($action, $callback, $param1 = false){
		$this->treeView->connect($action, $callback, $param1);
	}
	
	public function getSelection(){
		$selection = $this->treeView->get_selection(); 
		$selection->set_mode(Gtk::SELECTION_BROWSE);
		return $selection;
	}
	
	private function connectSignals(){

	}
	
	
}
?>