<?
class IndexedCombobox {
	
	private $instance_init = false;
	private $instance_fill = false;
	
	private $combobox = false;
	private $liststore = false;
	
	public function __construct($combobox=false, $create=false, $data=array(), $wrap_width=false, $index=false)
	{
		if (!$combobox) return false;
		
		if ($create === false) {
			$this->init_combobox_simple($combobox, $wrap_width);
			$this->fill($data, $index);
		}
		else {
			$this->init_combobox($combobox, $data, $wrap_width);
		}
	}
	
	
	public function init_combobox_simple($combobox=false, $wrap_width=false) {
		
		if (!$combobox) return false;
		
		$data = array(
			'id' => array(
				'renderer' => 'text',
				'visible' => false,
			),
			'label' => array(
				'renderer' => 'text',
				'visible' => true,
			),
		);
		$this->init_combobox($combobox, $data, $wrap_width);
	}
	
	public function init_combobox($combobox=false, $data=array(), $wrap_width=false) {
		
		if (!$combobox) return false;
		$this->combobox = $combobox;
		
		$col_cnt = 0;
		$create = array();
		foreach ($data as $title => $attr) {
			
			switch($attr['renderer']) {
				case 'text':
					$create['liststore_type'][$col_cnt] = "Gtk::TYPE_STRING";
					$create['attribute_type'][$col_cnt] = "text";
					$create['renderer'][$col_cnt] = new GtkCellRendererText();
					break;
				case 'pixbuf':
					$create['liststore_type'][$col_cnt] = "GdkPixbuf::gtype";
					$create['attribute_type'][$col_cnt] = "pixbuf";
					$create['renderer'][$col_cnt] = new GtkCellRendererPixbuf();
					break;
				default:
					print "unknown renderer in ".__FILE__." ".__LINE__."\n";
					break;
			}
			$create['visible'][$col_cnt] = $attr['visible'];
			
			$col_cnt++;
		}
		
		$renderer_string = implode(", ", $create['liststore_type']);
		eval('$this->liststore = new GtkListStore('.$renderer_string.');');
		
		foreach ($create['renderer'] as $key => $value) {
			if ($create['visible'][$key]) {
				$this->combobox->pack_start($create['renderer'][$key], true);
				$this->combobox->add_attribute($create['renderer'][$key], $create['attribute_type'][$key], $key);
				$this->combobox->set_wrap_width((int)$wrap_width);
			}
		}
		
		// set model
		$this->combobox->set_model($this->liststore);
		
		// connect changed dropdown
		#$this->combobox->connect("changed", array($this, 'get_active'));
		
		// set create visible for all methods
		$this->create = $create;
	}
	
	public function fill($data, $index=false)
	{
		
		if ($index === false) $index = 0;
		
		foreach ($data as $id => $value) {
			if (is_array($value)) {
				$this->liststore->append($value);
			}
			else {
				$this->liststore->append(array($id, $value));
			}
		}
		$this->combobox->set_active($index);
	}
	
	public function get_active_text() {
		$test =  $this->combobox->get_active_text();
		return $test;
	}
	
	/*
	public function set_active($index) {
		$this->combobox->set_active($index);
	}
	*/
}

?>
