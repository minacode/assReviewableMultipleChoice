<?php

//require_once("./Customizing/global/plugins/Modules/TestQuestionPool/Questions/assReviewableMultipleChoice/classes/class.assReviewableMultipleChoice.php");

class assReviewableMultipleChoiceTest extends PHPUnit_Framework_TestCase {
		
        protected $backupGlobals = FALSE;
 
        protected function setUp() {
		global $ilDB;
		
		include_once("./Services/PHPUnit/classes/class.ilUnitUtil.php");
		ilUnitUtil::performInitialisation();
	}
        
        public function testSaveReviewDataToDb() {
			global $ilDB;
			
			$ilDB->manipulate("INSERT INTO qpl_rev_qst (question_id,taxonomy,knowledge_dimension) VALUES (1337,'testtaxonomy','testknowledgedim')");
			$ilDB->manipulate("INSERT INTO qpl_rev_qst (question_id,taxonomy,knowledge_dimension) VALUES (NULL,NULL,NULL)");
			
			$qobj1 = new assReviewableMultipleChoice("testQuestionObject1","","",-1,"",OUTPUT_ORDER,"","");
			$qobj2 = new assReviewableMultipleChoice("testQuestionObject2","","",-1,"",OUTPUT_ORDER,"","");
			
			$qobj1->setId(1337);
			$qobj2->setId(1338);
			
			$qobj1->saveReviewDataToDb();
			$qobj2->saveReviewDataToDb();
			
			$qobj1_taxonomy = $ilDB->manipulate("SELECT taxonomy FROM qpl_rev_qst WHERE question_id = 1337");
			$qobj1_knowledge_dimension = $ilDB->manipulate("SELECT knowledge_dimension FROM qpl_rev_qst WHERE question_id = 1337");
			
			$qobj2_taxonomy = $ilDB->manipulate("SELECT taxonomy FROM qpl_rev_qst WHERE question_id = 1338");
			$qobj2_knowledge_dimension = $ilDB->manipulate("SELECT knowledge_dimension FROM qpl_rev_qst WHERE question_id = 1338");
			
			if(($qobj1_taxonomy != "testtaxonomy") && ($qobj1_knowledge_dimension != "testknowledgedim")) {
				$this->assertTrue(true,"");
			}
			else {
				$this->assertTrue(false,"saveReviewDataToDb() failed!");
			}
		
			
			
			if(($qobj2_taxonomy != "") && ($qobj2_knowledge_dimension != "")) {
				$this->assertTrue(true,"saveReviewDataToDb() failed!");
			}
			else {
				$this->assertTrue(false,"saveReviewDataToDb() failed!");
			}
			
			$tqid = array(1337,1338);
			
			$ilDB->manipulateF("DELETE * FROM qpl_rev_qst WHERE question_id= %s",array("integer"), $tqid);
			$ilDB->manipulate("DELETE FROM qpl_rev_qst WHERE ((question_id is null) + (taxonomy is null) + (knowledge_dimension is null)) = 3");
        }
		
		
		public function testLoadReviewDataFromDb() {
			global $ilDB;
			
			$qobj1 = new assReviewableMultipleChoice("testQuestionObject1","","",-1,"",OUTPUT_ORDER,"","");
			$qobj2 = new assReviewableMultipleChoice("testQuestionObject2","","",-1,"",OUTPUT_ORDER,"","");
			
			$qobj1->setTaxonomy("empty");
			$qobj1->setKnowledgeDimesion("empty");
			
			$qobj1->loadReviewDataFromDb();
			$qobj2->loadReviewDataFromDb();
			
			if(($qobj1_taxonomy != "testtaxonomy") && ($qobj1_knowledge_dimension != "testknowledgedim")) {
				$this->assertTrue(false,"");
			}
			else {
				$this->assertTrue(true,"saveReviewDataToDb() $ilDB::update() failed!");
			}
		
			
			if(($qobj2_taxonomy != "") && ($qobj2_knowledge_dimension != "")) {
				$this->assertTrue(false,"");
			}
			else {
				$this->assertTrue(true,"saveReviewDataToDb() $ilDB::insert() failed!");
			}
			
			
		}
	
		
		public function testDelete() {
			global $ilDB;

			$qobj1->delete(1337);
			$dbRes = $ilDB->queryF("SELECT * FROM qpl_rev_qst WHERE question_id = %s","integer",$qobj1->getId());
			
			if ($result != NULL) {
				$this->assertTrue(true,"");
			}
			else {
				$this->assertTrue(false,"Deletion was not successful!");
			}
		}
}


?>