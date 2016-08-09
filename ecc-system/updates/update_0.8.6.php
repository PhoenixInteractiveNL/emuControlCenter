<?php
$updateConfig = array(
	'db' => array(
		'
CREATE INDEX [IDX_MDATA_PUBLISHER] ON [mdata](
[publisher]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_CREATOR] ON [mdata](
[creator]  ASC
);
		',
		'
CREATE INDEX [IDX_MDATA_YEAR] ON [mdata](
[year]  ASC
);
		',
	),
)
?>