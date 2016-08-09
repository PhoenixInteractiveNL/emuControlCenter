<?php
class AutoCompletion {
	
	public function connect($entry, $data) {
		$this->connectGtkEntry($entry, $this->initCompletion($data));
	}
	
	public function createListStore($listStore = false) {
		$listStore = ($listStore) ? $listStore : new GtkListStore(GObject::TYPE_STRING, GObject::TYPE_STRING);
		return $listStore;
	}
	
	public function initCompletion(Array $data) {
		$listStore = $this->createListStore();
		foreach($data as $key => $value) {
			$listStore->append(array($key, $value));
		}
		$completion = new GtkEntryCompletion;
		$completion->set_model($listStore);
 		$completion->set_text_column(1);
 		return $completion;
	}
	
	public function connectGtkEntry($gtkEntryObject, $competionObject) {
		$gtkEntryObject->set_completion($competionObject);
	}
}
?>