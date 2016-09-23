## EXPORT DATABASE DATA TO DAT FILE (example expand exported metadata)
***
### DEFINE NEW LINE TERMINATOR

Add lineterminator for new version, 4 extra fields are exported in ECC v1.20+

Edit file: ecc-system\manager\cDatFileImport.php, around line 710, add:

    case '1.2003':
      $terminator = 37;
    break;

### DEFINE CSV LOCATION TO ECC DATABASE COLUMNS

around line 780, add:

    // v1.2003
    if ($version >= '1.2003') {
      $data['perspective'] = (($res[33] != "")) ? $res[33] : "NULL";
      $data['visual'] = (($res[34] != "")) ? $res[34] : "NULL";
      $data['gameplay'] = (($res[35] != "")) ? $res[35] : "NULL";
      $data['description'] = (($res[36] != "")) ? $res[36] : "NULL";
    }

### IMPORT DATA TO ECC DATABASE COLUMNS

around line 900, add:

    perspective,
    visual,
    gameplay,
    description

around line 940 (add metadate function), add:

    '".sqlite_escape_string($data['perspective'])."',
    '".sqlite_escape_string($data['visual'])."',
    '".sqlite_escape_string($data['gameplay'])."',
    '".sqlite_escape_string($data['description'])."'

around line 980 (update metadata function), add:

    perspective = ".sqlite_escape_string($data['perspective']).",
    visual = ".sqlite_escape_string($data['visual']).",
    gameplay = ".sqlite_escape_string($data['gameplay']).",
    description = ".sqlite_escape_string($data['description']).",
