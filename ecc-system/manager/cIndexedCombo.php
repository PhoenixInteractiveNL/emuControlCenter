<?php
require_once ECC_DIR_SYSTEM.'/manager/thirdParty/IndexedComboBox/Model.php';

class IndexedCombo {
	
	public function __construct() {}
	
	public function set($comboBox, $dataArray, $wrap_width=false) {
		
		$renderer = new GtkCellRendererText();
		$comboBox->pack_start($renderer);
		$comboBox->set_attributes($renderer, 'text', 1);
		
		$mod = new Gtk2_IndexedComboBox_Model();
		$comboBox->set_model($mod);
		$comboBox->set_wrap_width((int)$wrap_width);
		$mod->append_array($dataArray);
		
		return $comboBox;
	}
	
    /**
    *   Sets the model row with the given key as active.
    *
    *   @param string   $strId      The key of the entry to be made active
    *
    *   @return boolean     True if an entry has been set active
    */
    public function set_active_key($comboBox, $strId)
    {
        if ($strId === null) {
            $comboBox->set_active(-1);
            return true;
        }

        $model = $comboBox->get_model();
        $iter  = $model->get_iter_first();
        if ($iter !== null) {
            do {
                if ($model->get_key($iter) == $strId) {
                    break;
                }
            } while (($iter = $model->iter_next($iter)) !== null);

            if ($iter !== null) {
                $comboBox->set_active_iter($iter);
                return true;
            }
        }
        return false;
    }//public function set_active_key($strId)
	
	public function getKey($combo) {
		$nActive = $combo->get_active();
		if ($nActive == -1) $nActive = 0;
		$iter    = $combo->get_model()->get_iter($nActive);
		return $combo->get_model()->get_key($iter);
	}
	
	public function getValue($combo) {
		$nActive = $combo->get_active();
		$iter    = $combo->get_model()->get_iter($nActive);
		return $combo->get_model()->get_text($iter);		
	}
}




?>