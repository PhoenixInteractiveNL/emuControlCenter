<?php
/**
*   Simple class for viewing PHP variables a var_dump() way
*    in PHP-Gtk - reloaded for PHP-Gtk2.
*
*   It displays arrays and objects in a tree with all their
*    children and subchildren and subsubchildren and ...
*
*   The class is memory-saving as it loads only the children
*    which are currently visible. If the user expands a row,
*    the next children will be loaded.
*
*   The tree has a small convenience feature: Left-click a row,
*    and it will be expanded. Right-click it, and it collapses.
*   Double-click or middle-click it, and all rows below the
*    current one will be expanded. They will be expanded "all" only
*    if they have been expanded before, as loading them recursively
*    is very dangerous (if there are loops).
*
*   Note that VarDump opens its own Gtk::main()-Loop, so your
*    own program will stop executing until the VarDump window
*    is closed.
*
*   Usage:
*   require_once('Gtk2/VarDump.php');
*   $ar = new array(1, 2, 3, 4, 'key' => array('this','is','cool');
*   new Gtk2_VarDump($ar);
*
*   Layout:
*   +--[Window title]------------------------------------------------+
*   |+--------------------------+/+---------------------------------+|
*   || Node      | Type         ^\| Key    | Type      | Value      ^|
*   ||                          |/|                                 ||
*   || Left tree with objects   |\|  Right list with simple values  ||
*   ||   and arrays             |/|     (int,float,string,...)      ||
*   ||                          v\|                                 v|
*   |+--------------------------+/+---------------------------------+|
*   |                             [OK]                               |
*   +----------------------------------------------------------------+
*
*   @author Christian Weiske <cweiske@php.net>
*/
class Gtk2_VarDump extends GtkWindow
{
    /**
    *   The tree on the left side of the window.
    *   @var GtkTreeView
    */
    protected $trTree    = null;

    /**
    *   List on the right side of the window.
    *   @var GtkTreeView
    */
    protected $trValues  = null;

    /**
    *   Model (data store) for the tree on the left.
    *   @var GtkTreeStore
    */
    protected $modTree   = null;

    /**
    *   Model (data store) for the list on the right.
    *   @var GtkListStore
    */
    protected $modValues = null;



    /**
    *   Create a new Gtk2_VarDump window and keep it displayed
    *   in its own Gtk::main()-loop.
    *   This main loop is stopped as soon the window is closed
    *
    *   @param mixed    $variable   The variable to inspect
    *   @param string   $title      The title for the window and the variable
    */
    public function __construct($variable, $title = 'Gtk2_VarDump')
    {
        parent::__construct();
        $this->buildDialog($title);
        $this->buildTree($variable, $title);
        $this->trTree->expand_row('0', false);//expand first row
        $this->show_all();
        Gtk::main();
    }//public function __construct($variable, $title = 'Gtk2_VarDump')



    /**
    *   Creates the dialog content, loads the tree models and so
    *
    *   @param  string  $title  The title for the window
    */
    protected function buildDialog($title)
    {
        $this->set_title($title);
        $this ->connect_simple('destroy', array($this, 'close'));

        $btnOk = GtkButton::new_from_stock(Gtk::STOCK_OK);
        $btnOk->connect_simple('clicked', array($this, 'close'));

        $vboxMain = new GtkVBox();
        $hpane = new GtkHPaned();
        $hpane->set_position(250);

        //Node, Type, original variable, if the children have been checked for subchildren
        $this->modTree   = new GtkTreeStore(Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_PHP_VALUE, Gtk::TYPE_BOOLEAN);
        //Keyname, Type (+ size), Value
        $this->modValues = new GtkListStore(Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING);

        $this->trTree       = new GtkTreeView();
        $this->trValues     = new GtkTreeView();
        $this->trTree       ->set_model($this->modTree);
        $this->trValues     ->set_model($this->modValues);

        $selection = $this->trTree->get_selection();
        $selection->connect         ('changed'              , array($this, 'selectTreeRow'));
        $this->trTree->connect      ('row-expanded'         , array($this, 'expandTree'));
        $this->trTree->connect_after('event' , array($this, 'clickedTree'));
        $this->trTree->set_events   (Gdk::_2BUTTON_PRESS | Gdk::BUTTON_RELEASE);

        $this->createColumns($this->trTree    , array('Node', 'Type'));
        $this->createColumns($this->trValues  , array('Key', 'Type', 'Value'));

        $scrwndTree   = new GtkScrolledWindow();
        $scrwndValues = new GtkScrolledWindow();
        $scrwndTree   ->set_policy(Gtk::POLICY_AUTOMATIC, Gtk::POLICY_AUTOMATIC);
        $scrwndValues ->set_policy(Gtk::POLICY_AUTOMATIC, Gtk::POLICY_AUTOMATIC);
        $scrwndTree   ->add($this->trTree);
        $scrwndValues ->add($this->trValues);

        $hpane->add1($scrwndTree);
        $hpane->add2($scrwndValues);

        $vboxMain->pack_start($hpane, true , true , 0);
        $vboxMain->pack_end(  $btnOk, false, false, 0);

        $this->add($vboxMain);

        $btnOk->set_flags($btnOk->flags() + Gtk::CAN_DEFAULT);
        $this->set_default($btnOk);
        $this->set_default_size(600, 400);
    }//protected function buildDialog($title)



    /**
    *   Creates GtkTreeView columns out of an string array and
    *   appends them to the tree view.
    *   The columns will be resizable and sortable.
    *
    *   @param GtkTreeView  $tree       The tree to which the columns shall be appended
    *   @param array        $arColumns  Array of strings which are the titles for the columns
    */
    protected function createColumns($tree, $arColumns)
    {
        $cell_renderer = new GtkCellRendererText();
        foreach ($arColumns as $nId => $strTitle) {
            $column = new GtkTreeViewColumn($strTitle, $cell_renderer, "text", $nId);
            $column->set_resizable(true);
            $column->set_sort_column_id($nId);
            $tree->append_column($column);
        }
    }//protected function createColumns($tree, $arColumns)



    /**
    *   Appends the given $variable to the tree on the right.
    *   $name is used as title for the node, $parent is the parent node
    *   to which the new node will be appended.
    *
    *   @param mixed        $variable   The variable to append
    *   @param string       $name       The title for the variable (e.g. array key)
    *   @param GtkTreeIter  $parent     The parent node to which the new node shall be appended
    *   @param int          $nStop      After how many levels appending shall be stopped
    */
    protected function buildTree($variable, $name, $parent = null, $nStop = 1)
    {
        $type = gettype($variable);

        if ($type == 'array') {
            $type .= '[' . count($variable) . ']';
        } else if($type == 'object' && 
            //FIXME: Tell me how to distinguish between Gtk::TYPE_PHP_VALUE and Gtk::TYPE_OBJECT!
            ($variable instanceof GtkWidget || $variable instanceof GObject || $variable instanceof GdkRectangle)
        ) {
            $type = get_class($variable);
            $variable = new Gtk2_VarDump_PseudoClass($variable);
        } else if ($type == 'object') {
            $type = trim(get_class($variable));

            //FIXME: That here is a workaround until I know how to distinguish between Gtk::TYPE_PHP_VALUE and Gtk::TYPE_OBJECT
            if ($type == 'StyleHelper' || substr($type, 0, 3) == 'Gdk' || substr($type, 0, 5) == 'Pango') {
                $variable = null;
            }
        } else {
            //not an array and not an object
            $variable   = new Gtk2_VarDump_PseudoClass($variable);
            $nStop      = 0;
        }

        $node = $this->modTree->append($parent, array($name, $type, $variable, false));

        if ($nStop > 0) {
            $this->appendChildren($variable, $node, $nStop--);
        }

        if ($parent === null) {
            $this->trTree->get_selection()->select_path('0');
        }
    }//protected function buildTree($variable, $name, $parent = null, $nStop = 1)



    /**
    *   Appends all the children of the given variable to $node
    *
    *   @param mixed        $variable   The variable, whose children shall be appended
    *   @param GtkTreeIter  $node       The parent node to which the new ones shall be appended
    *   @param int          $nStop      After how many levels appending shall be stopped
    */
    protected function appendChildren($variable, $node, $nStop = 1)
    {
        $type = gettype($variable);

        if ($type == 'object' && $variable instanceof Gtk2_VarDump_PseudoClass) {
            $variable = $variable->value;
            $type     = gettype($variable);
        }

        switch ($type) {
            case 'object':
                $arKeys = array_keys(get_object_vars($variable));
                break;
            case 'array':
                $arKeys = array_keys($variable);
                break;
            default:
                return;
        }

        foreach ($arKeys as $key) {
            $value = ($type == 'array') ? $variable[$key] : $variable->$key;
            switch (gettype($value)) {
                case 'object':
                case 'array':
                    $this->buildTree($value, $key, $node, $nStop - 1);
                    break;
                default:
                    //other types aren't displayed in the tree
                    break;
            }
        }
    }//protected function appendChildren($variable, $node, $nStop = 1)



    /**
    *   Adds all the children of the given $variable to the list 
    *   on the right side.
    *   Arrays and objects are not added, as they appear on the
    *   tree on the left.
    *
    *   @param mixed    $variable   The variable whose children values shall be shown
    */
    protected function buildValues($variable)
    {
        $this->modValues->clear();
        switch (gettype($variable))
        {
            case 'object':
                $arKeys = array_keys(get_object_vars($variable));
                foreach ($arKeys as $key) {
                    $value = $variable->$key;
                    $this->appendValue($key, $value);
                }
                break;

            case 'array':
                $arKeys = array_keys($variable);
                foreach ($arKeys as $key) {
                    $value = $variable[$key];
                    $this->appendValue($key, $value);
                }
                break;

            default:
                //do nothing
                //shouldn't happen
        }
    }//protected function buildValues($variable)



    /**
    *   Appends one value to the list on the right.
    *   Arrays and objects will not be displayed, as they already
    *   appear on the tree on the left side.
    *
    *   @param mixed    $key    The title for the node
    *   @param mixed    $value  The value to display
    */
    protected function appendValue($key, $value)
    {
        switch (gettype($value)) {
            case 'object':
            case 'array':
                //Don't display arrays and objects in the values list
                continue;
            case 'string':
                $this->modValues->append(
                    array(
                        $key,
                        'string[' . strlen($value) . ']',
                        $value
                    )
                );
                break;
            default:
                $this->modValues->append(
                    array(
                        $key,
                        gettype($value),
                        $value
                    )
                );
                break;
        }
    }//protected function appendValues($variable, $arKeys)



    /**
    *   Called whenever a tree row is expanded.
    *   It is used to load the children of the node's children
    *   if they haven't been loaded yet.
    *
    *   @param  GtkTreeView $tree       The tree on which the signal has been emitted 
    *   @param  GtkTreeIter $iterator   The node which has been expanded
    */
    public function expandTree($tree, $iterator)
    {
        if ($this->modTree->get_value($iterator, 3)) {
            //already checked
            return;
        }

        //check if children have subchildren and load them
        $child = $this->modTree->iter_children($iterator);
        while ($child !== null) {
            $this->appendChildren(
                $this->modTree->get_value($child, 2),
                $child
            );
            //get the next child
            $child = $this->modTree->iter_next($child);
        }
        $this->modTree->set($iterator, 3, true);
    }//public function expandTree($tree, $iterator)



    /**
    *   Called whenever a row on the left tree has been selected.
    *   It is used to show the children of the selected variable
    *   on the right list.
    *
    *   @param array    $selection  Array consisting of the model and the currently selected node (GtkTreeIter)
    */
    public function selectTreeRow($selection)
    {
        list($model, $iter) = $selection->get_selected();
        if ($iter === null) {
            return;
        }
        $variable = $model->get_value($iter, 2);
        $this->buildValues($variable);
    }//public function selectTreeRow($selection)



    /**
    *   The tree has been clicked, and the currently selected row
    *   will be expanded or collapsed, depending which mouse button has
    *   been clicked.
    *   The left mouse button will expand the node,
    *   the right mouse button will collapse it.
    *   Middle mouse button and a double-clicked left button will
    *    expand all children but *only* if they have been expanded 
    *    before - it would be too dangerous to expand all children to
    *    any depth recursively if there are loops.
    *
    *   @param GtkTreeView  $tree   The tree which has been clicked
    *   @param GdkEvent     $event  The event data for the click event
    */
    public function clickedTree($tree, $event)
    {
        if ($event->type !== Gdk::_2BUTTON_PRESS && $event->type !== Gdk::BUTTON_RELEASE) {
            return;
        }

        list($model, $arSelected) = $tree->get_selection()->get_selected_rows();
        $path = implode(':', $arSelected[0]);

        if ($event->button == 1) {
            //left mouse button
            //If double-click: expand all rows down
            $tree->expand_row($path, $event->type == Gdk::_2BUTTON_PRESS);
        } else if ($event->button == 2) {
            //middle mouse button - expand all
            $tree->expand_row($path, true);
        } else if ($event->button == 3) {
            //right mouse button
            $tree->collapse_row($path);
        }
    }//public function clickedTree($tree, $event)



    /**
    *   Called when the user clicks "OK" or tries to close the window.
    *   This function quits the main loop opened in the constructor.
    */
    public function close()
    {
        //quit our own main loop
        $this->destroy();
        Gtk::main_quit();
    }//public function close()

}//class Gtk2_VarDump extends GtkWindow



/**
*   Pseudo class wrapper around simple data types.
*   Needed to store a simple data type in a GtkTreeStore.
*
*   The bug has been fixed in CVS by Andrei on 2005-11-22,
*   but I will leave this until there is a new w32 version
*   of php-gtk2 so that all people can use Gtk2_VarDump.
*/
class Gtk2_VarDump_PseudoClass
{
    public $value = null;


    public function __construct($value)
    {
        $this->value = $value;
    }//public function __construct($value)



    public function __toString()
    {
        return $this->value;
    }//public function __toString()

}//class Gtk2_VarDump_PseudoClass
?>