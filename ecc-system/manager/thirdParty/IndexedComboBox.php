<?php
require_once 'Gtk2/IndexedComboBox/Model.php';

/**
*   Indexed Gtk2 combo box similar to the HTML select box.
*   Lets you not only store values as the normal GtkComboBox,
*   but associated keys as well. The active key can be easily
*   received with get_active_key.
*
*   It imitates the convenience methods of a text-based GtkComboBox
*   that is constructed with GtkComboBox::new_text().
*
*   Both key and values can be strings or integers.
*
*   Method names aren't camelCase but with underscores to be close
*   the naming of the original Gtk2 methods.
*
*   @category   Gtk2
*   @package    Gtk2_IndexedComboBox
*   @author     Christian Weiske <cweiske@php.net>
*   @license    LGPL
*   @version    CVS: $Id: IndexedComboBox.php,v 1.3 2006/04/05 07:13:27 cweiske Exp $
*/
class Gtk2_IndexedComboBox extends GtkComboBox
{
    /**
    *   The cell renderer for the text
    *   @var GtkCellRenderer
    */
    protected $renderer = null;



    public function __construct($arData = null)
    {
        parent::__construct();
        $model = new Gtk2_IndexedComboBox_Model();
        $this->set_model($model);

        //show the second column only
        $this->renderer = new GtkCellRendererText();
        $this->pack_start($this->renderer);
        $this->set_attributes($this->renderer, 'text', 1);

        if ($arData !== null) {
            $this->set_array($arData);
        }
    }//public function __construct($arData = null)



    /**
    *   Appends a single key/value pair to the list.
    *
    *   @param string   $strId      The id to append
    *   @param string   $strValue   The value to append
    */
    public function append($strId, $strValue)
    {
        $this->get_model()->append($strId, $strValue);
    }//public function append_array($strId, $strValue)



    /**
    *   Appends an array (key and value) as data to the store.
    *
    *   @param array    $arData     The array to append
    */
    public function append_array($arData)
    {
        $this->get_model()->append_array($arData);
    }//public function append_array($arData)



    /**
    *   Returns the id of the active entry.
    *   If there is no active key, NULL will be returned.
    *
    *   @return string  The id/key of the selected entry
    */
    public function get_active_key()
    {
        $nActive = $this->get_active();
        if ($nActive === -1) {
            return null;
        }
        //workaround bug in php-gtk2: get_active_iter wants a parameter instead of giving one
        return $this->get_model()->get_key($this->get_model()->get_iter($nActive));
        //That's the better one (that doesn't work as of 2006-03-04)
        return $this->get_model()->get_key($this->get_active_iter());
    }//public function get_active_key()



    /**
    *   Returns the string of the active entry.
    *   If there is no active entry, NULL will be returned.
    *
    *   @return string  The string value of the selected entry
    */
    public function get_active_text()
    {
        $nActive = $this->get_active();
        if ($nActive === -1) {
            return null;
        }
        //workaround bug in php-gtk2: get_active_iter wants a parameter instead of giving one
        return $this->get_model()->get_text($this->get_model()->get_iter($nActive));
        //That's the better one (that doesn't work as of 2006-03-04)
        return $this->get_model()->get_text($this->get_active_iter());
    }//public function get_active_text()



    /**
    *   Returns an array with all key/value pairs.
    *
    *   @return array Array with key/value pairs in the model
    */
    public function get_array()
    {
        return $this->get_model()->get_array();
    }//public function get_array()



    /**
    *   Returns the default cell renderer.
    *
    *   @return GtkCellRenderer The default cell renderer
    */
    public function get_cell_renderer()
    {
        return $this->renderer;
    }//public function get_cell_renderer()



    /**
    *   Inserts a single key/value pair at a certain position into the list.
    *
    *   @param int      $nPosition  The position to insert the values at
    *   @param string   $strId      The id to append
    *   @param string   $strValue   The value to append
    */
    public function insert($nPosition, $strId, $strValue)
    {
        $this->get_model()->insert($nPosition, $strId, $strValue);
    }//public function insert($nPosition, $strId, $strValue)



    /**
    *   Inserts an array (key and value) at a certain position into the list.
    *
    *   @param int      $nPosition  The position to insert the array at
    *   @param array    $arData     The array to append
    */
    public function insert_array($nPosition, $arData)
    {
        $this->get_model()->insert_array($nPosition, $arData);
    }//public function insert_array($nPosition, $arData)



    /**
    *   Prepends a single key/value pair to the list.
    *
    *   @param string   $strId      The id to append
    *   @param string   $strValue   The value to append
    */
    public function prepend($strId, $strValue)
    {
        $this->get_model()->prepend($strId, $strValue);
    }//public function prepend($strId, $strValue)



    /**
    *   Prepends an array (key and value) at the beginning of the store
    *
    *   @param array    $arData     The array to append
    */
    public function prepend_array($arData)
    {
        $this->get_model()->prepend_array($arData);
    }//public function prepend_array($arData)



    /**
    *   Removes the first entry with the given key from the list.
    *
    *   @param string   $strId      The key of the entry to remove
    *
    *   @return boolean     True if an entry has been deleted
    */
    public function remove_key($strId)
    {
        return $this->get_model()->remove_key($strId);
    }//public function remove_key($strId)



    /**
    *   Sets the model row with the given key as active.
    *
    *   @param string   $strId      The key of the entry to be made active
    *
    *   @return boolean     True if an entry has been set active
    */
    public function set_active_key($strId)
    {
        if ($strId === null) {
            $this->set_active(-1);
            return true;
        }

        $model = $this->get_model();
        $iter  = $model->get_iter_first();
        if ($iter !== null) {
            do {
                if ($model->get_key($iter) == $strId) {
                    break;
                }
            } while (($iter = $model->iter_next($iter)) !== null);

            if ($iter !== null) {
                $this->set_active_iter($iter);
                return true;
            }
        }
        return false;
    }//public function set_active_key($strId)



    /**
    *   Sets an array (key and value) as data into the store.
    *   Clears any previous entries.
    *
    *   @param array    $arData     The array to set
    */
    public function set_array($arData)
    {
        return $this->get_model()->set_array($arData);
    }//public function set_array($arData)



    /*
    *   PEAR-style camelCaseNamed method aliases
    */



    public function appendArray($arData) {
        return $this->append_array($arData);
    }



    public function getActiveKey() {
        return $this->get_active_key();
    }



    public function getActiveText() {
        return $this->get_active_text();
    }



    public function getArray() {
        return $this->get_array();
    }



    public function getCellRenderer() {
        return $this->get_cell_renderer();
    }



    public function insertArray($nPosition, $arData) {
        return $this->insert_array($nPosition, $arData);
    }



    public function prependArray($arData) {
        return $this->prepend_array($arData);
    }



    public function removeKey($strId) {
        return $this->remove_key($strId);
    }



    public function setActiveKey($strId) {
        return $this->set_active_key($strId);
    }



    public function setArray($arData) {
        return $this->set_array($arData);
    }

}//class Gtk2_IndexedComboBox extends GtkMessageDialog
?>
