<?php
$updateConfig = array(
	'db' => array(
		"update fdata set state = 0;",
		
		
"
BEGIN TRANSACTION;
",
		
'
CREATE TABLE "udata_TEMP" (
"id" INTEGER  NOT NULL PRIMARY KEY,
"eccident" VARCHAR(8)  NOT NULL,
"crc32" VARCHAR(8)  NOT NULL,
"rating" INTEGER  NULL,
"difficulty" INTEGER  NULL,
"notes" TEXT  NULL
)
',

"
INSERT INTO [udata_TEMP] SELECT * FROM [udata];
",

"
DROP TABLE [udata];
",

"
CREATE TABLE [udata] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[eccident] VARCHAR(8)  NOT NULL,
[crc32] VARCHAR(8)  NOT NULL,
[notes] TEXT  NULL,
[rating] INTEGER  NULL,
[rating_fun] INTEGER  NULL,
[rating_gameplay] INTEGER  NULL,
[rating_graphics] INTEGER  NULL,
[rating_music] INTEGER  NULL,
[review_title] VARCHAR(255)  NULL,
[review_body] BLOB  NULL,
[review_export_allowed] INTEGER  NULL,
[preferred_emu] VARCHAR(16)  NULL,
[hiscore] VARCHAR(64)  NULL,
[difficulty] INTEGER  NULL,
[launchcnt] INTEGER DEFAULT '0' NULL,
[launchtime] INTEGER  NULL,
[cdate] INTEGER  NULL
)
",
		
"
INSERT INTO [udata]
(
[id],
[eccident],
[crc32],
[rating],
[difficulty],
[notes]
)
SELECT * FROM [udata_TEMP];
",
		
"
DROP TABLE [udata_TEMP];
",
		'
COMMIT;
		',
		
"
CREATE UNIQUE INDEX [IDX_UDATA_] ON [udata](
[eccident]  ASC,
[crc32]  ASC
)
",
		
	),
)
?>