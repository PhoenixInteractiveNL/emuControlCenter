<?php
$updateConfig = array(
	'db' => array(
		'
BEGIN TRANSACTION;
		',
		'
CREATE TABLE "mdata_temp" (
"id" INTEGER  PRIMARY KEY NOT NULL,
"eccident" VARCHAR(8)  NULL,
"crc32" VARCHAR(8)  NULL,
"name" VARCHAR(255)  NULL,
"extension" VARCHAR(8)  NULL,
"info" VARCHAR(128)  NULL,
"info_id" VARCHAR(32)  NULL,
"running" INTEGER  NULL,
"bugs" INTEGER  NULL,
"trainer" INTEGER  NULL,
"intro" INTEGER  NULL,
"usermod" INTEGER  NULL,
"freeware" INTEGER  NULL,
"multiplayer" INTEGER  NULL,
"netplay" INTEGER  NULL,
"year" VARCHAR(4)  NULL,
"usk" INTEGER  NULL,
"rating" INTEGER DEFAULT "0" NULL,
"category" INTEGER  NULL,
"creator" VARCHAR(128)  NULL,
"cdate" INTEGER  NULL,
"uexport" VARCHAR(15)  NULL
);
		',
		'
INSERT INTO "mdata_temp" SELECT * FROM "mdata";
		',
		'
DROP TABLE "mdata";
		',
		'
CREATE TABLE "mdata" (
"id" INTEGER  PRIMARY KEY NOT NULL,
"eccident" VARCHAR(8)  NULL,
"crc32" VARCHAR(8)  NULL,
"name" VARCHAR(255)  NULL,
"extension" VARCHAR(8)  NULL,
"info" VARCHAR(128)  NULL,
"info_id" VARCHAR(32)  NULL,
"running" INTEGER  NULL,
"bugs" INTEGER  NULL,
"trainer" INTEGER  NULL,
"intro" INTEGER  NULL,
"usermod" INTEGER  NULL,
"freeware" INTEGER  NULL,
"multiplayer" INTEGER  NULL,
"netplay" INTEGER  NULL,
"year" VARCHAR(4)  NULL,
"usk" INTEGER  NULL,
"storage" INTEGER  NULL,
"rating" INTEGER DEFAULT "0" NULL,
"category" INTEGER  NULL,
"creator" VARCHAR(128)  NULL,
"publisher" VARCHAR(128)  NULL,
"region" INTEGER  NULL,
"cdate" INTEGER  NULL,
"uexport" VARCHAR(15)  NULL
);
		',
		'
INSERT INTO "mdata"
(
"id",
"eccident",
"crc32",
"name",
"extension",
"info",
"info_id",
"running",
"bugs",
"trainer",
"intro",
"usermod",
"freeware",
"multiplayer",
"netplay",
"year",
"usk",
"rating",
"category",
"creator",
"cdate",
"uexport"
)
SELECT * FROM "mdata_temp";
		',
		'
DROP TABLE "mdata_temp";
		',
		'
COMMIT;
		',
	),
)
?>