<?php
/**
*   Indexed Gtk2 combo box model. Can be used stand-alone
*   e.g. as model for a GtkComboBox from a glade file.
*
*   Both key and values can be strings or integers.
*
*   @category   Gtk2
*   @package    Gtk2_IndexedComboBox
*   @author     Christian Weiske <cweiske@php.net>
*   @license    LGPL
*   @version    CVS: $Id: IndexedComboBox.php,v 1.3 2006/04/05 07:13:27 cweiske Exp $
*/
class Gtk2_IndexedComboBox_Model extends GtkListStore
{
	
    /**
    *   Constructor.
    *
    *   @param array    $arData     If wished, you can set the initial data here.
    */
    public function __construct($arData = null)
    {
        parent::__construct(Gtk::TYPE_STRING, Gtk::TYPE_STRING);
        if ($arData !== null) {
            $this->set_array($arData);
        }
    }//public function __construct($arData = null)



    /**
    *   Appends a single key/value pair to the list.
    *
    *   @param mixed    $strId      (string) id to append, or an array to append
    *   @param string   $strValue   The value to append
    */
    public function append($strId, $strValue = null)
    {
        if (is_array($strId)) {
            parent::append($strId);
        } else {
            parent::append(array($strId, $strValue));
        }
    }//public function append_array($strId, $strValue = null)



    /**
    *   Appends an array (key and value) as data to the store.
    *
    *   @param array    $arData     The array to append
    */
    public function append_array($arData)
    {
        foreach ($arData as $strId => &$strValue) {
            parent::append(array($strId, $strValue));
        }
    }//public function append_array($arData)



    /**
    *   Returns the key/id of the given iter.
    *   If $iter is NULL, this method returns NULL.
    *
    *   @param GtkTreeIter  $iter   Iterator whose key shall be gotten.
    *   @return string  The id/key of the selected entry
    */
    public function get_key($iter)
    {
        if ($iter === null) {
            return null;
        }
        return $this->get_value($iter, 0);
    }//public function get_key($iter)



    /**
    *   Returns the string of the given iter.
    *   If $iter is NULL, this method returns NULL.
    *
    *   @param GtkTreeIter  $iter   Iterator whose string shall be gotten.
    *   @return string  The string value of the selected entry
    */
    public function get_text($iter)
    {
        if ($iter === null) {
            return null;
        }
        return $this->get_value($iter, 1);
    }//public function get_key($iter)



    /**
    *   Returns an array with all key/value pairs.
    *
    *   @return array Array with key/value pairs in the model
    */
    public function get_array()
    {
        $ar = array();

        $iter = $this->get_iter_first();
        if ($iter !== null) {
            do {
                $ar[$this->get_value($iter, 0)] = $this->get_value($iter, 1);
            } while (($iter = $this->iter_next($iter)) !== null);
        }

        return $ar;
    }//public function get_array()



    /**
    *   Inserts a single key/value pair (or an array) at
    *   a certain position into the list.
    *
    *   @param int      $nPosition  The position to insert the values at
    *   @param mixed    $strId      (string) id to append, or array to append
    *   @param string   $strValue   The value to append
    */
    public function insert($nPosition, $strId, $strValue = null)
    {
        if (is_array($strId)) {
            parent::insert($nPosition, $strId);
        } else {
            parent::insert($nPosition, array($strId, $strValue));
        }
    }//public function insert($nPosition, $strId, $strValue = null)



    /**
    *   Inserts an array (key and value) at a certain position into the list.
    *
    *   @param int      $nPosition  The position to insert the array at
    *   @param array    $arData     The array to append
    */
    public function insert_array($nPosition, $arData)
    {
        foreach ($arData as $strId => &$strValue) {
            parent::insert($nPosition++, array($strId, $strValue));
        }
    }//public function insert_array($nPosition, $arData)



    /**
    *   Prepends a single key/value pair to the list.
    *
    *   @param mixed    $strId      (string) id to prepend, or array to prepend
    *   @param string   $strValue   The value to append
    */
    public function prepend($strId, $strValue = null)
    {
        if (is_array($strId)) {
            parent::prepend($strId);
        } else {
            parent::prepend(array($strId, $strValue));
        }
    }//public function prepend($strId, $strValue = null)



    /**
    *   Prepends an array (key and value) at the beginning of the store
    *
    *   @param array    $arData     The array to append
    */
    public function prepend_array($arData)
    {
        $nPosition = 0;
        foreach ($arData as $strId => &$strValue) {
            parent::insert($nPosition++, array($strId, $strValue));
        }
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
        $iter = $this->get_iter_first();
        if ($iter !== null) {
            do {
                if ($this->get_value($iter, 0) == $strId) {
                    break;
                }
            } while (($iter = $this->iter_next($iter)) !== null);

            if ($iter !== null) {
                $this->remove($iter);
                return true;
            }
        }
        return false;
    }//public function remove_key($strId)



    /**
    *   Sets an array (key and value) as data into the store.
    *   Clears any previous entries.
    *
    *   @param array    $arData     The array to set
    */
    public function set_array($arData)
    {
        $this->clear();
        return $this->append_array($arData);
    }//public function set_array($arData)



    /*
    *   PEAR-style camelCaseNamed method aliases
    */



    public function appendArray($arData) {
        return $this->append_array($arData);
    }



    public function getArray() {
        return $this->get_array();
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



    public function setArray($arData) {
        return $this->set_array($arData);
    }

}//class Gtk2_IndexedComboBox_Model extends GtkListStore
?>
