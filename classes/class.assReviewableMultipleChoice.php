<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class for multiple choice tests.
 *
 * assReviewableMultipleChoice is a class that implements reviewable multiple choice questions.
 *
 * @extends assMultipleChoiceQuestion
 * 
 * @author		Julius Felchow <julius.felchow@mailbox.tu-dresden.de>
 * @author		Max Friedrich <max.friedrich@mailbox.tu-dresden.de>
 * 
 * @ingroup		ModulesTestQuestionPool
 */
 
require_once('./Modules/TestQuestionPool/classes/class.assMultipleChoice.php');

class assReviewableMultipleChoice extends assMultipleChoice {

	protected $taxonomy;
	protected $knowledge_dimension;

	function _construct(
		$title = "", 
		$comment = "", 
		$author = "", 
		$owner = -1, 
		$question = "", 
		$output_type = OUTPUT_ORDER,
		$taxonomy = "",
		$knowledge_dimension = ""
	) {
		parent::_construct($title, $comment, $author, $owner, $question, $output_type);
		$this->taxonomy = $taxonomy;
		$this->knowledge_dimension = $knowledge_dimension;
	}
	
	public function getQuestionType() {
		return "assReviewableMultipleChoice";
	}
	
	public function getTaxonomy() {
		return $this->taxonomy;
	}
	
	public function setTaxonomy($a_taxonomy) {
		$this->taxonomy = $a_taxonomy;
	}
	
	public function getKnowledgeDimension() {
		return $this->knowledge_dimension;
	}
	
	public function setKnowledgeDimension($a_knowledge_dimension) {
		$this->knowledge_dimension = $a_knowledge_dimension;
	}
	
	function getReviewDataTable() {
		return "qpl_qst_rev_mc";
	}
	
	private function saveReviewDataToDb($original_id = "") {
		global $ilDB;
		
		$result = $ilDB->queryF(
			"SELECT * 
			FROM qpl_rev_qst 
			WHERE question_id = %s",
			array("integer"),
			array( $this->getId() ) 
		);
		
		if ($result->numRows() <= 0) {
			$affectedRows = $ilDB->insert(
				"qpl_rev_qst",
				array(
					"question_id"         => array( "text"    , $this->getId()                 ),
					"taxonomy"            => array( "text"    , $this->getTaxonomy()           ),
					"knowledge_dimension" => array( "text"    , $this->getKnowledgeDimension() )
				)
			);
		} else {
			$affectedRows = $ilDB->update(
				"qpl_rev_qst", 
				array(
					"taxonomy"            => array( "text"    , $this->getTaxonomy()           ),
					"knowledge_dimension" => array( "text"    , $this->getKnowledgeDimension() )
				),
				array(
					"question_id"         => array( "integer" , $this->getId()                 )
				)
			);
		}
	}
	
	public function saveToDb($original_id = "") {
		parent::saveToDb($original_id);
		$this->saveReviewDataToDb($original_id);
	}
	
	private function loadReviewDataFromDb($question_id = "") {
		global $ilDB;
		
		$result = $ilDB->queryF(
			"SELECT taxonomy, knowledge_dimension FROM qpl_rev_qst WHERE question_id = %s",
			array("integer"),
			array($question_id)
		);
		
		if($result->numRows() == 1) {
			$data = $ilDB->fetchAssoc($result);
			$this->setTaxonomy( $data['taxonomy'] );
			$this->setKnowledgeDimension( $data['knowledge_dimension'] );
		}
	}
	
	public function loadFromDb($question_id) {
		parent::loadFromDb($question_id);
		$this->loadReviewDataFromDb($original_id);
	}
	
	function toJSON() {
		$result = json_decode( parent::toJson() );
		
		$result['taxonomy'] = $this->getTaxonomy();
		$result['knowlegde_dimension'] = $this->getKnowlegdgeDimension();
		
		return json_encode($result);
	}

}
