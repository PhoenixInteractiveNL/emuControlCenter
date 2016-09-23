## TEXTBOX WITH SEARCH BUTTON (example "database folder")
***
### ADD TO GUI > HBOX with 4 items:

Items:

_Image   (TAB GENERAL> Name> DatabaseFolderImage) | (TAB Packing> position> 0)_

_Label   (TAB GENERAL> Name> DatabaseFolderLabel) | (TAB Packing> position> 1)_

_Textbox (TAB GENERAL> Name> DatabaseFolderTextbox) | (TAB Packing> position> 2)_

_Button  (TAB GENERAL> Name> DatabaseFolderButton) | (TAB Packing> position> 3)_

## IMPLEMENTATION STEPS

### CONFIGURE IMAGE
ADD a PNG "ecc_config_database.png" to the path "ecc-themes\default\icon".

* LOAD the PNG into the GUI

Edit file: cGuiPopConfig, around line 752, add:

    $this->DatabaseFolderImage->set_from_file(FACTORY::get('manager/GuiTheme')- >getThemeFolder('icon/ecc_config_database.png'));

### CONFIG LABEL LANGUAGE (example NL)
* ADD label translation section in the i18n

Edit file: ecc-system\translations\nl\i18n_popupConfig.php, at the NEAR END, add:

    /* ECCVERSION */
    'DatabaseFolderLabel' =>
    "Database map",

* LOAD label from i18n config into GUI
Edit file: ecc-system\manager\cGuiPopConfig.php, around line 1050, add:

    $this->DatabaseFolderLabel->set_text(I18N::get('popupConfig', 'DatabaseFolderLabel'));

### LOAD TEXTBOX DATA
* ADD config section/option into ecc config.

Edit "vanilla ecc_config", file: "ecc-system\system\config\ecc_general.ini", in section [USER_DATA] add:

    database_path = "database/"

* LOAD config section/key into the GUI.

Edit file: ecc-system\manager\cGuiPopConfig.php, around line 1080, add:

    $this->DatabaseFolderTextbox->set_text($iniManager->getKey('USER_DATA', 'database_path'));

* SAVE config to INI file.

Edit file: ecc-system\manager\cGuiPopConfig.php, around line 1240, ADD:

    $this->globalIni['USER_DATA']['database_path'] = $this->DatabaseFolderTextbox->get_text();

### USING THE VARIABLE IN THE TEXTBOX IN ECC
* LOAD folder config from INI,

Edit (for example) ecc.php, ADD:

  $databaseFolder = $this->ini->getKey('USER_DATA', 'database_path'); // Load database folder from INI.

### CONFIG BUTTON LANGUAGE (example NL)
* LOAD label from i18n config into GUI button

Edit file: ecc-system\manager\cGuiPopConfig.php, around line 1050, ADD:

    $this->DatabaseFolderLabel->set_text(I18N::get('popupConfig', 'DatabaseFolderLabel'));

### USE THE BUTTON
* ADD A SIGNAL TO THE BUTTON (to run a php function "DatabaseFolderSearch")

Edit file: ecc-system\manager\cGuiPopConfig.php, around line 140, ADD:

  $this->DatabaseFolderButton->connect_simple('clicked', array($this, 'DatabaseFolderSelect'));

* ADD A PHP FUNCTION WITH SEARCHBOX

Edit file: ecc-system\manager\cGuiPopConfig.php, around line 1500, ADD:

    public function DatabaseFolderSelect() {
    $oOs = FACTORY::get('manager/Os');
    $path = realpath($this->DatabaseFolderTextbox->get_text());
    $title = I18N::get('popupConfig', 'dialogDatabaseFolder');
    $path_new = $oOs->openChooseFolderDialog($path, $title, false);
    $path_new = $oOs->eccSetRelativeDir($path_new);
    if ($path_new) $this->DatabaseFolderTextbox->set_text($path_new);
    }