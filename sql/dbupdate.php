<#1>
<?php

if (!$ilDB->tableExists("qpl_rev_qst")) {
	$fields = array(
	    'question_id'         => array( 
			'type'  => 'integer' ,
			'length' => 8
	    ),
	    'taxonomy'            => array(
			'type' => 'text' ,
			'length' => 20
		),
	    'knowledge_dimension' => array( 
			'type' => 'text',
			'length' => 20   
	    )
	);
	
	$ilDB->createTable("qpl_rev_qst", $fields);
}

?>

<#2>
<?php

$res = $ilDB->queryF("SELECT * FROM qpl_qst_type WHERE type_tag = %s", array('text'), array('assReviewableMultipleChoice'));

if ($res->numRows() == 0) {
    $res = $ilDB->query("SELECT MAX(question_type_id) maxid FROM qpl_qst_type");
    $data = $ilDB->fetchAssoc($res);
    $max = $data["maxid"] + 1;

    $affectedRows = $ilDB->manipulateF(
		"INSERT INTO qpl_qst_type (question_type_id, type_tag, plugin) VALUES (%s, %s, %s)",
		array("integer", "text", "integer"),
		array($max, 'assReviewableMultipleChoice', 1)
    );
}

?>
