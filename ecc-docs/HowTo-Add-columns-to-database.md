## ADD COLUMNS TO DATABASE (example "mdata" table)
***
NOTES:

The ECC database version is based on ECC version and build.
Example: a ECC v1.20 Build 02 will have a database version string of "1.2002"
The database version is stored in the database in table "eccdb_state" in the column: "version".

So, in this particulair case we add some columns to the existing database.

### CREATE PHP UPDATE FILE FOR DATABASE

NOTES: You have to create a PHP update file in the folder "ecc-system\updates" HIGHER then the current
ecc release/db version, so in this example "update_1.2003.php"

First create an TEMP-Table like the old one, these tables are (< ECC v1.20)

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
    [dump_type] INTEGER  NULL,
    [cdate] INTEGER  NULL,
    [uexport] VARCHAR(15)  NULL,
    [filesize] INTEGER  NULL

Then insert old data into "TEMP-Table".

    INSERT INTO [mdata_TEMP] SELECT * FROM [mdata];

Then drop original table.

    DROP TABLE [mdata];

Then create the new table structure with new columns:

_[description]_

_[perspective]_

_[visual]_

_[gameplay]_

So all columns will be like:

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
    [description] VARCHAR(4096)  NULL,
    [perspective] VARCHAR(128)  NULL,
    [visual] VARCHAR(128)  NULL,
    [gameplay] VARCHAR(128)  NULL,
    [programmer] VARCHAR(128)  NULL,
    [musican] VARCHAR(128) NULL,`
    [graphics] VARCHAR(128) NULL,
    [media_type] INTEGER  NULL,
    [media_current] INTEGER  NULL,
    [media_count] INTEGER  NULL,
    [region] INTEGER  NULL,
    [dump_type] INTEGER  NULL,
    [cdate] INTEGER  NULL,
    [uexport] VARCHAR(15)  NULL,
    [filesize] INTEGER  NULL

Now insert old data into the "new table".

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
    [dump_type],
    [cdate],
    [uexport],
    [filesize]
    )

    SELECT * FROM [mdata_TEMP];

Now drop the "TEMP-Table"

    DROP TABLE [mdata_TEMP];

### ADD NEW UPDATE FILE TO ECC STARTUP CONFIGURATION

Edit file: ecc-system\manager\cEccUpdate.php, around line 50, ADD:

    case $eccDbVersion < '1.2003':
      if ($this->updateEccFromConfig('1.2003')) $this->updateEccDbVersion('1.2003');
    else {
      $errorVersion = '1.2003';
      break;
    }

### TRIGGER DATABASE UPDATE

NOTES: You have to increase the version or build to trigger the database.

* Increase the BUILD from 02 to 03:

Edit file: ecc-system\ecccore.php, around line 3, CHANGE:

    $build = '03';
