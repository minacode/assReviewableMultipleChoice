<?php

if (!$ilDB->tableExists("qpl_reviewable_questions") {
	$fields = array(
	    'question_id'         => array( 'type' => 'integer' ),
	    'taxonomy'            => array( 'type' => 'text'    ),
	    'knowledge_dimension' => array( 'type' => 'text'    )
	);
	
	$ilDB->createTable("qpl_reviewable_questions", $fields);
}

?>
