<?php
$updateConfig = array(
	'db' => array(

"
CREATE TRIGGER fdataDeleteTrigger DELETE ON fdata BEGIN DELETE from fdata_audit where fDataId = old.id; END;
",

	),
)
?>