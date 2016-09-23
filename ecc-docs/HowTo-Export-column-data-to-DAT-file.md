## EXPORT COLUMN DATA TO DAT FILE (example expand exported metadata)
***
NOTES:
Always start at the end of the DAT values to expand it's exported data, never use fields in the
middle or so, or all older DATS are wrong and corrupt!

* ADD DATABSE FIELDS TO BE EXPORTED
These are the new exported fields in v1.20+

_- perspective_

_- visual_

_- gameplay_

_- description_

Edit file: ecc-system\manager\cDatFileExport.php, around line 175, ADD extra fields like:

    ".$v['perspective'].";".$v['visual'].";".$v['gameplay'].";".$v['description'].";#\r\n";


* ADD FIELDS TO CSV HEADER (first line of the file)

around line 175 (fields dat header) , ADD extra fields at the end like:

    perspective;visual;gameplay;description#\r\n";

around line 315 (fields csv header) , ADD extra fields at the end like:

    perspective;visual;gameplay;description#\r\n";
