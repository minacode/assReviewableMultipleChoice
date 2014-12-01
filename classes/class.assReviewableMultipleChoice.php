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
 
 require_once('./Modules/TestQuestionPool/classes/class.assMultipleChoice.php';);

class assReviewableMultipleChoice extends assMultipleChoice {

	protected $taxonomy;
	protected $knowledge_dimension;

	function _construct(
		$title = "", 
		$comment = "", 
		$author = "", 
		$owner = -1, 
		$question = "", 
		$output_type = OUTPUT_ORDER
		$taxonomy = "",
		$knowledge_dimension = ""
	) {
		parent::_construct($title, $comment, $author, $owner, $question, $output_type);
		$this->taxonomy = $taxonomy;
		$this->knowledge_dimension = $knowledge_dimension;
	}
	
	function getAdditionalReviewDataTable() {
		return "qpl_qst_rev_mc";
	}
	
	function saveAdditionalReviewDataToDb($original_id = "") {
		// ...
	}
	
	function saveToDb($original_id = "") {
		parent::saveToDb($original_id);
		$this->saveAdditionalReviewDataToDb($original_id);
	}
	
	function loadFromDb($question_id) {
		parent::_loadFromDb($question_id);
	}
	
	public function duplicate ($for_test=true, $title="", $author="", $owner="", $testObjId=null) {
		parent::_duplicate($for_test, $title, $author, $owner, $testObjId);
	}
	
	function copyObject ($target_questionpool_id, $title="") {
		parent::copyObject($target_questionpool_id, $title);
	}
	
	function createNewOriginalFromThisDuplicate($targetParentId, $targetQuestionTitle="") {
		//...
	}
	
	function toJSON() {
		$result = json_decode( parent::toJson() );
		
		$result['taxonomy'] = $this->getTaxonomy();
		$result['knowlegde_dimension'] = $this->getKnowlegdgeDimension();
		
		return json_encode($result);
	}

}
