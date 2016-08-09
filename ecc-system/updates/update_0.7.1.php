<?php
$updateConfig = array(
	'db' => array(
	
		'DROP INDEX IDX_MDATA_;',
		'DROP INDEX IDX_MDATA_name;',
		'DROP INDEX IDX_MDATA_eccident;',
		'DROP INDEX IDX_MDATA_crc32;',
		'DROP INDEX IDX_MDATA_rating;',
		'DROP INDEX IDX_MDATA_identCrcName;',
	
		'
CREATE INDEX [IDX_MDATA_] ON [mdata](
[eccident]  ASC,
[crc32]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_name] ON [mdata](
[name]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_eccident] ON [mdata](
[eccident]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_crc32] ON [mdata](
[crc32]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_rating] ON [mdata](
[rating]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_identCrcName] ON [mdata](
[eccident]  ASC,
[crc32]  ASC,
[name]  ASC
);
		',
		'VACUUM',
	),
)
?>