## SET DROPDOWN WITH DATABASE DATA (example 2 fields)
***
### ADD DROPDOWN OPTIONS TO ECCCORE
Edit file: ecc-system\ecccore.php, around line 490, add these arrays:

	'dropdownPerspective' => array(
		0 => 'unknown',
		1 => '1st-person',
		2 => '3rd-person',
		3 => 'Audio game',
		4 => 'Behind view',
		5 => 'Birds-eye view',
		6 => 'Side view',
		7 => 'Text-based / Spreadsheet',
		8 => 'Top-down',
	),

	'dropdownVisual' => array(
		0 => 'unknown',
		1 => '2D scrolling',
		2 => 'Cinematic camera',
		3 => 'Fixed / Flip-screen',
		4 => 'Free-roaming camera',
		5 => 'Isometric',
	),	

### LOAD DROPDOWN OPTIONS FROM ECCCORE
Edit file: ecc-system\ecc.php, around line 7840 (funtion: loadEccConfig), add:

	$this->dropdownPerspective = $mngrValidator->getEccCoreKey('dropdownPerspective');
	$this->dropdownVisual = $mngrValidator->getEccCoreKey('dropdownVisual');

### ADD TO GUI > 2 rows and 2 columns with items:

* Static labels (left side)

_media_nb_info_perspective_

_media_nb_info_visual_

* Event boxes (right side)

_GtkEventBox > nbMediaInfoStatePerspectiveEvent_

_GtkEventBox > nbMediaInfoStateVisualEvent_

### TRANSLATE GUI STATIC LABELS
* Add labels to translation file

Edit file: ecc-system\translations\[LANGUAGE]\i18n_meta.php, around line 90, in the META array, add:

	/* 1.20 */
	'lbl_perspective' =>
		"Perspectief",	
	'lbl_visual' =>
		"Visueel",	

* Load Labels from translation file(s)

Edit file: ecc-system\ecc.php, around line 8480, add:

		$this->setSpanMarkup($this->infotab_lbl_perspective, I18N::get('meta', 'lbl_perspective'), false, 'b', false);
		$this->setSpanMarkup($this->infotab_lbl_visual, I18N::get('meta', 'lbl_visual'), false, 'b', false);

### CONNECT EVENTS TO DROPDOWNCONTROL
NOTES: Color effects are set with colEventOptionSelect1 / colEventOptionSelect2

Edit file: ecc-system\ecc.php, around line 930, add:

		// perspective
		$this->dropdownPerspective = I18n::translateArray('dropdownPerspective', $this->dropdownPerspective);
		$this->nbMediaInfoStatePerspectiveEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_perspective').'?', $this->dropdownPerspective, 'metaEditDirectUpdate', 'setPerspective', true);
		$this->nbMediaInfoStatePerspectiveEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));

		// visual
		$this->dropdownVisual = I18n::translateArray('dropdownVisual', $this->dropdownVisual);
		$this->nbMediaInfoStateVisualEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_visual').'?', $this->dropdownVisual, 'metaEditDirectUpdate', 'setVisual', true);
		$this->nbMediaInfoStateVisualEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect2));

### TRANSLATE DROPDOWN LABELS (example dutch)
* Add labels to translation file

Edit file: ecc-system\translations\[LANGUAGE]\i18n_meta.php, around line, at the end add these array's, add:

    $i18n['dropdownVisual'] = array(
	'unknown' =>
		"Onbekend",
	'2D scrolling' =>
		"2D scrollend",
	'Cinematic camera' =>
		"Cinematische camera",
	'Fixed / Flip-screen' =>
		"Vast / Flip-screen",
	'Free-roaming camera' =>
		"Vrije camera",
	'Isometric' =>
		"Isometrisch",
    );

    $i18n['dropdownPerspective'] = array(
	'unknown' =>
		"Onbekend",
	'1st-person' =>
		"1e persoon",
	'3rd-person' =>
		"3e Persoon",
	'Behind view' =>
		"Van achteren",
	'Birds-eye view' =>
		"Vogel-oog zicht",
	'Side view' =>
		"Vanaf de zijkant",
	'Text-based / Spreadsheet' =>
		"Tekst gebaseerd / Spreadsheet",
	'Top-down' =>
		"Van boven af",
    );

### UPDATE ROM PANEL (ON CHANGE)
Edit file: ecc-system\ecc.php, around line 3070 (function: updateRomInfoPanel), add:

				// option perspective
				$perspective = (!$romMeta->getPerspective()) ? 0 : $romMeta->getPerspective();
				$this->setSpanMarkup($this->media_nb_info_perspective, $this->dropdownPerspective[$perspective]);

				// option visual
				$visual = (!$romMeta->getVisual()) ? 0 : $romMeta->getVisual();
				$this->setSpanMarkup($this->media_nb_info_visual, $this->dropdownVisual[$visual]);

### ADD COLUMN TO ROM METADATA CHECKSUM
Edit file: ecc-system\manager\model\RomMeta.php, around line 130 ($checksumInclude array), add:

    'perspective',
    'visual',

### GET METADATA FROM DATABASE
around line 386 (function fillFromDatabase), add:

    $this->setPerspective($dbEntry['md_perspective']);
    $this->setVisual($dbEntry['md_visual']);

### STORE METADATA IN DATABASE
around line 510 (function store), add:

	perspective = ".$this->getCleanInteger($this->getPerspective()).",
	visual = ".$this->getCleanInteger($this->getVisual()).",

around line 550 (function store), add:

	perspective,
	visual,

around line 590 (function store), add:

	".$this->getCleanInteger($this->getPerspective()).",
	".$this->getCleanInteger($this->getVisual()).",

### ADD NEW DATA TO SQL FIELDS
Edit file: ecc-system\manager\cTreeviewData.php, around line 60 ($sqlFields), add:

    md.perspective as md_perspective,
    md.visual as md_visual,

* Add new functions to get and store data

around line 150, add these functions:

	public function setVisual($visual){
		$this->Visual = $visual;
	}

	public function getVisual(){
		return $this->Visual;
	}	

* Add new fields to functions (save data)

around line 640 (function: update_file_info), add:

	perspective = ".sqlite_escape_string($data['perspective']).",
	visual = ".sqlite_escape_string($data['visual']).",

around line 690 (function: insert_file_info), add:

	perspective,
	visual,

around line 730 (function: insert_file_info), add:

	'".sqlite_escape_string($data['perspective'])."',
	'".sqlite_escape_string($data['visual'])."',

## ADD OPTIONS TO META EDITOR

### ADD TO GUI (META EDITOR) > 2 rows and 2 columns with items:

* Static labels (left side)

_labelMetaEditPerspective_

_labelMetaEditVisual_

* Combo boxes (right side)

_GtkComboBox > cb_perspective_

_GtkComboBox > cb_visual_

### LOAD TRANSLATIONS FOR LABELS (META EDIT GUI)
Edit file: ecc-system\ecc.php, around line 5465 (function: metaEditPopupOpen), add:

	$this->labelMetaEditPerspective->set_markup(i18n::get('meta', 'lbl_perspective'));
	$this->labelMetaEditVisual->set_markup(i18n::get('meta', 'lbl_visual'));

### LOAD SETTINGS FROM DATABASE (META EDIT GUI)
Edit file: ecc-system\ecc.php, around line 5555 (function: metaEditPopupOpen), add:

		// perspective (before $mdata['md_perspective'] = 0) // Added 2016-09-11 v1.20
		$perspective = $romMeta->getPerspective();
		if (!$this->obj_perspective) $this->obj_perspective = new IndexedCombobox($this->cb_perspective, false, $this->dropdownPerspective);
		if ($perspective === null) $perspective = 0;
		$this->cb_perspective->set_active($perspective);

		// visual (before $mdata['md_visual'] = 0) // Added 2016-09-11 v1.20
		$visual = $romMeta->getVisual();
		if (!$this->obj_visual) $this->obj_visual = new IndexedCombobox($this->cb_visual, false, $this->dropdownVisual);
		if ($visual === null) $visual = 0;
		$this->cb_visual->set_active($visual);

### SAVE SETTINGS FROM DATABASE (META EDIT GUI)
Edit file: ecc-system\ecc.php, around line 6050 (function: metaEditPopupSave), add:

		$romMeta->setPerspective($this->cb_perspective->get_active());
		$romMeta->setVisual($this->cb_visual->get_active());
