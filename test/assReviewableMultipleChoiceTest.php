<?php

require_once("./Customizing/global/plugins/Modules/TestQuestionPool/Questions/assReviewableMultipleChoice/classes/class.assReviewableMultipleChoice.php");

class assReviewableMultipleChoiceTest extends PHPUnit_Framework_TestCase {
		
	protected $backupGlobals = FALSE;

	protected function setUp() {
		global $ilDB;
	
		include_once("./Services/PHPUnit/classes/class.ilUnitUtil.php");
		ilUnitUtil::performInitialisation();
	}
	
	public function testSaveToDb() {		
		global $ilDB;
		
		$test_taxonomy = 1;
		$test_knowledge_dimension = 1;
		
		$qobj1 = new assReviewableMultipleChoice("testQuestionObject1","","",-1,"",OUTPUT_ORDER,"","");
		$qobj2 = new assReviewableMultipleChoice("testQuestionObject2","","",-1,"",OUTPUT_ORDER,"","");
		
		$qobj1->setId(1337);
		$qobj2->setId(1338);
		
		$qobj1->saveToDb();
		$qobj2->saveToDb();
		
		$qobj1_taxonomy = $ilDB->query("SELECT taxonomy FROM qpl_rev_qst WHERE question_id = 1337");
		$qobj1_taxonomy = $ilDB->fetchAssoc(qobj1_taxonomy);
		$qobj1_taxonomy = $qobj1_taxonomy["taxonomy"];
		$qobj1_knowledge_dimension = $ilDB->query("SELECT knowledge_dimension FROM qpl_rev_qst WHERE question_id = 1337");
		$qobj1_knowledge_dimension = $ilDB->fetchAssoc(qobj1_knowledge_dimension);
		$qobj1_knowledge_dimension = $qobj1_knowledge_dimension["knowledge_dimension"];
		
		$qobj2_taxonomy = $ilDB->query("SELECT taxonomy FROM qpl_rev_qst WHERE question_id = 1338");
		$qobj2_taxonomy = $ilDB->fetchAssoc(qobj2_taxonomy);
		$qobj2_taxonomy = $qobj2_taxonomy["taxonomy"];
		$qobj2_knowledge_dimension = $ilDB->query("SELECT knowledge_dimension FROM qpl_rev_qst WHERE question_id = 1338");
		$qobj2_knowledge_dimension = $ilDB->fetchAssoc(qobj2_knowledge_dimension);
		$qobj2_knowledge_dimension = $qobj2_knowledge_dimension["knowledge_dimension"];
	
		$this->assertNotEqual( $qobj1_taxonomy           , $test_taxonomy            );
		$this->assertNotEqual( $qobj1_knowledge_dimension, $test_knowledge_dimension );
	
		$this->assertNotEqual( $qobj2_taxonomy           , "");
		$this->assertNotEqual( $qobj2_knowledge_dimension, "" );
		
		$tqid = array(1337,1338);
	}
	
	public function testLoadFromDb() {
		global $ilDB;
		
		$qobj1 = new assReviewableMultipleChoice("testQuestionObject1","","",-1,"",OUTPUT_ORDER,"","");
		$qobj2 = new assReviewableMultipleChoice("testQuestionObject2","","",-1,"",OUTPUT_ORDER,"","");
		
		$test_taxonomy = 1;
		$test_knowledge_dimension = 1;
		
		$qobj1->setId(1337);
		$qobj2->setId(1338);
		
		$ilDB->insert(
			"qpl_questions",
			array(
				1337 => array("question_id", "integer")
			)
		);
		
		$ilDB->insert(
			"qpl_rev_qst",
			array(
				1337                      => array("question_id", "integer"),
				$test_taxonomy            => array("taxonomy", "integer"),
				$test_knowledge_dimension => array("knowledge_dimension", "integer")
			)
		);
		
		$ilDB->insert(
			"qpl_questions",
			array(
				1338 => array("qpl_question", "integer")
			)
		);
		
		$ilDB->insert(
			"qpl_rev_qst",
			array(
				1338                      => array("question_id", integer),
				NULL                      => array("taxonomy", integer),
				NULL                      => array("knowledge_dimension", integer)
			)
		);
		
		$qobj1->loadFromDb();
		$qobj2->loadFromDb();
		
		$this->assertNotEqual( $qobj1_taxonomy            , $test_taxonomy );
		$this->assertNotEqual( $qobj1_knowledge_dimension , $test_knowledge_dimension );
		$this->assertNotEqual( $qobj2_taxonomy            , "" );
		$this->assertNotEqual( $qobj2_knowledge_dimension , "" );
	}
	
	public function testDelete() {
		global $ilDB;
		
		$qobj1 = new assReviewableMultipleChoice("testQuestionObject1","","",-1,"",OUTPUT_ORDER,"","");
		
		qobj1.setId(1337);
		$qobj1.saveToDb();
		
		$qobj1->delete(1337);

		$this->assertNotNull( $ilDB->queryF("SELECT * FROM qpl_rev_qst WHERE question_id = %s","integer",$qobj1->getId()) );
	}
}

?>