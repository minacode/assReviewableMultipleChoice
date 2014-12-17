<?php
require_once "./Services/Form/classes/class.ilPropertyFormGUI.php";
require_once "./Modules/TestQuestionPool/classes/class.assQuestionGUI.php";
require_once "./Modules/Test/classes/inc.AssessmentConstants.php";
require_once "./Modules/TestQuestionPool/classes/class.assMultipleChoiceGUI.php";

/**
 * Example GUI class for question type plugins
 *
 * @author	Julius Felchow <julius.felchow@mailbox.tu-dresden.de>
 * @version	$Id$
 *
 * @ingroup ModulesTestQuestionPool
 *
 * @ilctrl_iscalledby assReviewableMultipleChoiceGUI: ilObjQuestionPoolGUI, ilObjTestGUI, ilQuestionEditGUI, ilTestExpressPageObjectGUI
 */

  
 
class assReviewableMultipleChoiceGUI extends assMultipleChoiceGUI{

	/**
	 * @var ilassExampleQuestionPlugin	The plugin object
	 */
	var $plugin = null;


	/**
	 * @var assExampleQuestion	The question object
	 */
	var $object = null;
	
	/**
	* Constructor
	*
	* @param integer $id The database id of a question object
	* @access public
	*/

	public function __construct($id = -1) {
		parent::__construct();
		include_once "./Services/Component/classes/class.ilPlugin.php";
		$this->plugin = ilPlugin::getPluginObject(IL_COMP_MODULE, "TestQuestionPool", "qst", "assReviewableMultipleChoice");
		$this->plugin->includeClass("class.assReviewableMultipleChoice.php");
		$this->object = new assReviewableMultipleChoice();
		if ($id >= 0)
		{
			$this->object->loadFromDb($id);
		}
	}

	/**
	 * Evaluates a posted edit form and writes the form data in the question object
	 *
	 * @param bool $always
	 *
	 * @return integer A positive value, if one of the required fields wasn't set, else 0
	 */
	public function writePostData($always = false) {
		$hasErrors = (!$always) ? $this->editQuestion(true) : false;
		if (!$hasErrors)
		{
			parent::writePostData($always);
			//? $this->writeReviewData();
			return 0;
		}
		return 1;
	}

	/**
	 * Creates an output of the edit form for the question
	 *
	 * @param bool $checkonly
	 *
	 * @return bool
	 */
	public function editQuestion($checkonly = FALSE) {
		// copy from assMultipleChoice
		$save = $this->isSaveCommand();
		$this->getQuestionTemplate();

		include_once("./Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
		$form->setFormAction($this->ctrl->getFormAction($this));
		$form->setTitle($this->outQuestionType());
		$isSingleline = ($this->object->lastChange == 0 && !array_key_exists('types', $_POST)) ? (($this->object->getMultilineAnswerSetting()) ? false : true) : $this->object->isSingleline;
		if ($checkonly) $isSingleline = ($_POST['types'] == 0) ? true : false;
		if ($isSingleline)
		{
			$form->setMultipart(TRUE);
		}
		else
		{
			$form->setMultipart(FALSE);
		}
		$form->setTableWidth("100%");
		$form->setId("revmc");

		// title, author, description, question, working time (assessment mode)
		$this->addBasicQuestionFormProperties( $form );
		$this->populateQuestionSpecificFormPart( $form );
		$this->populateAnswerSpecificFormPart( $form );
		$this->populateTaxonomyFormSection( $form );
		$this->addQuestionFormCommandButtons( $form );

		// begin reviewable part
		$this->populateTaxonomyFormPart( $form );
		// end reviewable part

		$errors = false;

		if ($save)
		{
			$form->setValuesByPost();
			$errors = !$form->checkInput();
			$form->setValuesByPost(); // again, because checkInput now performs the whole stripSlashes handling and we need this if we don't want to have duplication of backslashes
			if ($errors) $checkonly = false;
		}

		if (!$checkonly) $this->tpl->setVariable("QUESTION_DATA", $form->getHTML());
		return $errors;
	}

	
	private function populateTaxonomyFormPart($form){
		global $lng;
		/*$head_cog = new ilSelectInputGUI();
		$head_cog->setTitle($lng->txt("qpl_qst_revmc_cognitive_process"));
		$head_cog->setValue(0);
		$head_cog->setOptions(array(0 => "",
						 1 => $lng->txt("qpl_qst_revmc_Remember"),
						 2 => $lng->txt("qpl_qst_revmc_Understand"),
						 3 => $lng->txt("qpl_qst_revmc_Apply"),
						 4 => $lng->txt("qpl_qst_revmc_Analyze"),
						 5 => $lng->txt("qpl_qst_revmc_Evaluate"),
						 6 => $lng->txt("qpl_qst_revmc_Create"),
						));
		$form->addItem($head_cog);
		
		$head_kno = new ilSelectInputGUI();
		$head_kno->setTitle($lng->txt("qpl_qst_revmc_knowledge_dimension"));
		$head_kno->setValue(0);
		$head_kno->setOptions(array(0 => "",
						 1 => $lng->txt("qpl_qst_revmc_Conceptual"),
						 2 => $lng->txt("qpl_qst_revmc_Factual"),
						 3 => $lng->txt("qpl_qst_revmc_Procedural"),
						 4 => $lng->txt("qpl_qst_revmc_Metacognitive"),
						));
		$form->addItem($head_kno);
		
		include_once "./Modules/TestQuestionPool/classes/class.assQuestionGUI.php";
		$preview = assQuestionGUI::_getQuestionGUI("", $_GET["q_id"]);
		
		$form->addItem($preview);*/
		
		global $ilPluginAdmin;
		if($ilPluginAdmin->isActive(IL_COMP_SERVICE, "Repository", "robj", "Review")){
			include_once ilPlugin::getPluginObject(IL_COMP_SERVICE, 'Repository', 'robj', 'Review')->getDirectory() .
				 "/classes/class.ilObjReview.php";
			$head_cog = new ilSelectInputGUI();
			$head_cog->setTitle($lng->txt("qpl_qst_revmc_cognitive_process"));
			$head_cog->setValue(0);
			$head_cog->setOptions(ilObjReview::taxonomy());
			$form->addItem($head_cog);
			
			$head_kno = new ilSelectInputGUI();
			$head_kno->setTitle($lng->txt("qpl_qst_revmc_knowledge_dimension"));
			$head_kno->setValue(0);
			$head_kno->setOptions(ilObjReview::knowledgeDimension());
			$form->addItem($head_kno);
		}
	}
}

?>
