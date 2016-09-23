## ADD QUICKFILTERS FOR METADATA (example 3 fields)
***
### ADD NEW FIELDS INTO ECC CORE

Edit file: ecc-system\ecccore.php, around line 380, in the 'freeformSearchFields' array, add:

    'PERSPECTIVE' => '[[perspective]]',
    'VISUAL' => '[[visual]]',
    'GAMEPLAY' => '[[gameplay]]',

### Add labels to translation file

Edit file: ecc-system\translations\[LANGUAGE]\i18n_metaStorage.php, around line 50, in the "dropdown_search_fields" array, add:

	/* 1.2.0 */'
	'[[perspective]]' =>
		"Perspectief",
	'[[visual]]' =>
		"Visueel",
	'[[gameplay]]' =>
		"Gameplay",

### ADD FIELDS TO SQL SEARCH

Edit file: ecc-system\ecc.php, around line 7400, add:

    case 'PERSPECTIVE':
        $searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post,"md.perspective like '%s'", $this->searchFreeformOperator);
        break;
    case 'VISUAL':
        $searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post,"md.visual like '%s'", $this->searchFreeformOperator);
        break;
    case 'GAMEPLAY':
        $searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post,"md.gameplay like '%s'", $this->searchFreeformOperator);
        break;

### ADD FIELDS TO FASTFILTER ON TOP

Edit file: ecc-system\ecc.php, around line 350, add:

    case 'PERSPECTIVE':
      $field = 'perspective';
      break;
    case 'VISUAL':
      $field = 'visual';
      break;
    case 'GAMEPLAY':
      $field = 'gameplay';
      break;

### ADD FIELDS TO FASTFILTER IN MENU

Edit file: ecc-system\ecc.php, around line 4490, add:

    'PERSPECTIVE' => array('romMeta', 'getPerspective'),
    'VISUAL' => array('romMeta', 'getVisual'),
    'GAMEPLAY' => array('romMeta', 'getGameplay'),
