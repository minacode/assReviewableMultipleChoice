<?php

include_once "./Modules/TestQuestionPool/classes/class.ilQuestionsPlugin.php";
	
/**
* Reviewable Multiple Choice Question Plugin
*
* @author Julius Felchow <julius.felchow@mailbox.tu-dresden.de>
* @author Max Friedrich <Max.Friedrich@mailbox.tu-dresden.de>
* @version $Id$
* 
*/
class ilassReviewableMultipleChoicePlugin extends ilQuestionsPlugin
{
		final function getPluginName()
		{
			return "assReviewableMultipleChoice";
		}
		
		final function getQuestionType()
		{
			return "assReviewableMultipleChoice";
		}
		
		final function getQuestionTypeTranslation()
		{
			return $this->txt($this->getQuestionType());
		}
}
?>