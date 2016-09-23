## SET LABELS WITH DATABASE DATA (example 3 fields)
***
### ADD TO GUI > 3 rows and 2 columns with items:

Items:

* Static labels (left side)

_infotab_lbl_perspective_

_infotab_lbl_visual_

_infotab_lbl_gameplay_


* Data labels (right side)

_media_nb_info_perspective_

_media_nb_info_visual_

_media_nb_info_gameplay_

### TRANSLATE GUI STATIC LABELS
* Add labels to translation file

Edit file: ecc-system\translations\[LANGUAGE]\i18n_meta.php, around line 8440, in the META array, add:

	/* 1.2.0 */
	'lbl_perspective' =>
		"Perspectief",
	'lbl_visual' =>
		"Visueel",
	'lbl_gameplay' =>
		"Gameplay",

* Load Labels from translation file(s)

Edit file: ecc-system\ecc.php, around line 8440, add:

$this->setSpanMarkup($this->infotab_lbl_perspective, I18N::get('meta', 'lbl_perspective'), false, 'b', false);
$this->setSpanMarkup($this->infotab_lbl_visual, I18N::get('meta', 'lbl_visual'), false, 'b', false);
$this->setSpanMarkup($this->infotab_lbl_gameplay, I18N::get('meta', 'lbl_gameplay'), false, 'b', false);

### FILL GUI LABELS WITH DATA

Edit file: ecc-system\ecc.php, around line 3085, add:

$this->setSpanMarkup($this->media_nb_info_perspective, $romMeta->getPerspective());
$this->setSpanMarkup($this->media_nb_info_visual, $romMeta->getVisual());
$this->setSpanMarkup($this->media_nb_info_gameplay, $romMeta->getGameplay());

### ADD COLUMN TO ROM METADATA CHECKSUM
Edit file: ecc-system\manager\model\RomMeta.php, around line 130 ($checksumInclude array), add:

    'perspective',
    'visual',
    'gameplay',

### GET METADATA FROM DATABASE
around line 386 (function fillFromDatabase), add:

    $this->setPerspective($dbEntry['md_perspective']);
    $this->setVisual($dbEntry['md_visual']);
    $this->setGameplay($dbEntry['md_gameplay']);

### ADD NEW DATA TO SQL FIELDS
Edit file: ecc-system\manager\cTreeviewData.php, around line 60 ($sqlFields), add:

    md.perspective as md_perspective,
    md.visual as md_visual,
    md.gameplay as md_gameplay,