<?php
$updateConfig = array(
	'db' => array(

'BEGIN TRANSACTION;',

/* START: UPDATE TABLE */

# 1-1 first create an TEMP-Table like the old one!
"
CREATE TABLE [mdata_TEMP] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[eccident] VARCHAR(8)  NULL,
[crc32] VARCHAR(8)  NULL,
[name] VARCHAR(255)  NULL,
[extension] VARCHAR(8)  NULL,
[info] VARCHAR(128)  NULL,
[info_id] VARCHAR(32)  NULL,
[running] INTEGER  NULL,
[bugs] INTEGER  NULL,
[trainer] INTEGER  NULL,
[intro] INTEGER  NULL,
[usermod] INTEGER  NULL,
[freeware] INTEGER  NULL,
[multiplayer] INTEGER  NULL,
[netplay] INTEGER  NULL,
[year] VARCHAR(4)  NULL,
[usk] INTEGER  NULL,
[storage] INTEGER  NULL,
[rating] INTEGER DEFAULT '0' NULL,
[category] INTEGER  NULL,
[category_base] INTEGER  NULL,
[creator] VARCHAR(128)  NULL,
[publisher] VARCHAR(128)  NULL,
[programmer] VARCHAR(128)  NULL,
[musican] VARCHAR(128) NULL,
[graphics] VARCHAR(128) NULL,
[media_type] INTEGER  NULL,
[media_current] INTEGER  NULL,
[media_count] INTEGER  NULL,
[region] INTEGER  NULL,
[cdate] INTEGER  NULL,
[uexport] VARCHAR(15)  NULL,
[filesize] INTEGER  NULL
)
",
# 1-2. then insert old data into TEMP-Table
"
INSERT INTO [mdata_TEMP] SELECT * FROM [mdata];
",
# 1-3.Then drop original table
"
DROP TABLE [mdata];
",
# 1-4 create new table structure
"
CREATE TABLE [mdata] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[eccident] VARCHAR(8)  NULL,
[crc32] VARCHAR(8)  NULL,
[name] VARCHAR(255)  NULL,
[extension] VARCHAR(8)  NULL,
[info] VARCHAR(128)  NULL,
[info_id] VARCHAR(32)  NULL,
[running] INTEGER  NULL,
[bugs] INTEGER  NULL,
[trainer] INTEGER  NULL,
[intro] INTEGER  NULL,
[usermod] INTEGER  NULL,
[freeware] INTEGER  NULL,
[multiplayer] INTEGER  NULL,
[netplay] INTEGER  NULL,
[year] VARCHAR(4)  NULL,
[usk] INTEGER  NULL,
[storage] INTEGER  NULL,
[rating] INTEGER DEFAULT '0' NULL,
[category] INTEGER  NULL,
[category_base] INTEGER  NULL,
[creator] VARCHAR(128)  NULL,
[publisher] VARCHAR(128)  NULL,
[programmer] VARCHAR(128)  NULL,
[musican] VARCHAR(128) NULL,
[graphics] VARCHAR(128) NULL,
[media_type] INTEGER  NULL,
[media_current] INTEGER  NULL,
[media_count] INTEGER  NULL,
[region] INTEGER  NULL,
[dump_type] INTEGER  NULL,
[cdate] INTEGER  NULL,
[uexport] VARCHAR(15)  NULL,
[filesize] INTEGER  NULL
)
",
# 1-5 insert old data into new table
"
INSERT INTO [mdata]
(
[id],
[eccident],
[crc32],
[name],
[extension],
[info],
[info_id],
[running],
[bugs],
[trainer],
[intro],
[usermod],
[freeware],
[multiplayer],
[netplay],
[year],
[usk],
[storage],
[rating],
[category],
[category_base],
[creator],
[publisher],
[programmer],
[musican],
[graphics],
[media_type],
[media_current],
[media_count],
[region],
[cdate],
[uexport],
[filesize]
)
SELECT * FROM [mdata_TEMP];
",
# drop TEMP-Table
"
DROP TABLE [mdata_TEMP];
",

/* START: ADD INDICES */
'
CREATE INDEX [IDX_MDATA_CREATOR] ON [mdata](
[creator]  ASC
)
',
'
CREATE INDEX [IDX_MDATA_PUBLISHER] ON [mdata](
[publisher]  ASC
)
',
'
CREATE INDEX [IDX_MDATA_YEAR] ON [mdata](
[year]  ASC
)
',
'
CREATE INDEX [IDX_MDATA_crc32] ON [mdata](
[eccident]  ASC,
[crc32]  ASC,
[name]  ASC
)
',
'
CREATE INDEX [IDX_MDATA_identCrcName] ON [mdata](
[eccident]  ASC,
[crc32]  ASC
)
',
'
CREATE INDEX [IDX_MDATA_rating] ON [mdata](
[rating]  ASC
)
',

'COMMIT;',

	),
)
?>