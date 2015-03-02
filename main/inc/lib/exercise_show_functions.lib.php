<?php
/* See license terms in /license.txt */
/**
* EVENTS LIBRARY
*
* This is the events library for Chamilo.
* Functions of this library are used to record informations when some kind
* of event occur. Each event has his own types of informations then each event
* use its own function.
*
* @package chamilo.library
* @todo convert queries to use Database API
*/
/**
 * Class
 * @package chamilo.library
 */
class ExerciseShowFunctions
{
<<<<<<< HEAD

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
	/**
	 * Shows the answer to a fill-in-the-blanks question, as HTML
	 * @param string    Answer text
	 * @param int       Exercise ID
	 * @param int       Question ID
	 * @return void
	 */
	static function display_fill_in_blanks_answer($feedback_type, $answer, $id, $questionId)
    {
        if (empty($id)) {
            echo '<tr><td>'. (Security::remove_XSS($answer)).'</td></tr>';
        } else {
		?>
			<tr>
                <td>
                    <?php
                    echo (Security::remove_XSS($answer));
                    ?>
                </td>

			<?php
			if (!api_is_allowed_to_edit(null,true) && $feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) { ?>
				<td>
                    <?php
                    $comm = get_comments($id,$questionId);
                    ?>
				</td>
			<?php } ?>
            </tr>
		<?php
        }
	}

	/**
	 * Shows the answer to a free-answer question, as HTML
	 * @param string    Answer text
	 * @param int       Exercise ID
	 * @param int       Question ID
	 * @return void
	 */
	static function display_free_answer($feedback_type, $answer, $exe_id, $questionId, $questionScore = null)
    {
<<<<<<< HEAD

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $comments = get_comments($exe_id, $questionId);

        if (!empty($answer)) {
            echo '<tr><td>';
            echo nl2br(Security::remove_XSS($answer));
            echo '</td></tr>';
        }

        if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) {
            if ($questionScore > 0 || !empty($comments)) {
            } else {
                echo '<tr>';
                echo Display::tag('td', Display::return_message(get_lang('notCorrectedYet')), array());
                echo '</tr>';
            }
        }
	}

	static function display_oral_expression_answer($feedback_type, $answer, $id, $questionId, $nano = null)
    {
<<<<<<< HEAD

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
		if (isset($nano)) {
			echo $nano->show_audio_file();
		}

		if (empty($id)) {
			echo '<tr>';
			echo Display::tag('td', nl2br(Security::remove_XSS($answer)), array('width'=>'55%'));
			echo '</tr>';
			if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) {
				echo '<tr>';
				echo Display::tag('td',get_lang('notCorrectedYet'), array('width'=>'45%'));
				echo '</tr>';
			} else {
				echo '<tr><td>&nbsp;</td></tr>';
			}
		} else {
			echo '<tr>';
			echo '<td>';
			if (!empty($answer)) {
				echo nl2br(Security::remove_XSS($answer));
			}
			echo '</td>';

			if (!api_is_allowed_to_edit(null,true) && $feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) {
				echo '<td>';
				$comm = get_comments($id,$questionId);
				echo '</td>';
			}
			echo '</tr>';
		}
	}

	/**
	 * Displays the answer to a hotspot question
	 *
	 * @param int $answerId
	 * @param string $answer
	 * @param string $studentChoice
	 * @param string $answerComment
	 */
	static function display_hotspot_answer($feedback_type, $answerId, $answer, $studentChoice, $answerComment)
    {

		$hotspot_colors = array(
            "", // $i starts from 1 on next loop (ugly fix)
            "#4271B5",
            "#FE8E16",
            "#45C7F0",
            "#BCD631",
            "#D63173",
            "#D7D7D7",
            "#90AFDD",
            "#AF8640",
            "#4F9242",
            "#F4EB24",
            "#ED2024",
            "#3B3B3B",
            "#F7BDE2");
		?>
		<table class="data_table">
		<tr>
			<td width="100px" valign="top" align="left">
				<div style="width:100%;">
					<div style="height:11px; width:11px; background-color:<?php echo $hotspot_colors[$answerId]; ?>; display:inline; float:left; margin-top:3px;"></div>
					<div style="float:left; padding-left:5px;">
					<?php echo $answerId; ?>
					</div>
					<div><?php echo '&nbsp;'.$answer ?></div>
				</div>
			</td>
			<td width="50px" style="padding-right:15px" valign="top" align="left">
				<?php
				$my_choice = ($studentChoice)?get_lang('Correct'):get_lang('Fault');
				echo $my_choice;
				?>
			</td>
			<?php if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) { ?>
			<td valign="top" align="left" >
				<?php
                if ($studentChoice) {
                    echo '<span style="font-weight: bold; color: #008000;">'.nl2br($answerComment).'</span>';
                }
				?>
			</td>
			<?php } else { ?>
				<td>&nbsp;</td>
			<?php } ?>
		</tr>
		<?php
	}

	/**
	 * Display the answers to a multiple choice question
	 *
	 * @param integer Answer type
	 * @param integer Student choice
	 * @param string  Textual answer
	 * @param string  Comment on answer
	 * @param string  Correct answer comment
	 * @param integer Exercise ID
	 * @param integer Question ID
	 * @param boolean Whether to show the answer comment or not
	 * @return void
	 */
<<<<<<< HEAD
	static function display_unique_or_multiple_answer($feedback_type, $answerType, $studentChoice, $answer, $answerComment, $answerCorrect, $id, $questionId, $ans)
    {
        $imageType = (in_array($answerType, array(UNIQUE_ANSWER,UNIQUE_ANSWER_IMAGE, UNIQUE_ANSWER_NO_OPTION))) ? 'radio' : 'checkbox';
        $image = $imageType.($studentChoice ? '_on' : '_off');
        $image .= '.gif';

        $imageAnswer = $imageType.($answerCorrect ? '_on' : '_off');
        $imageAnswer .= '.gif';

=======
	static function display_unique_or_multiple_answer(
        $feedback_type,
        $answerType,
        $studentChoice,
        $answer,
        $answerComment,
        $answerCorrect,
        $id,
        $questionId,
        $ans,
        $in_results_disabled
    ) {
        $hide_expected_answer = false;
        if ($feedback_type == 0 && $in_results_disabled == 2) {
            $hide_expected_answer = true;
        }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
		?>
		<tr>
		<td width="5%">
            <?php Display::display_icon($image); ?>
		</td>
		<td width="5%">
            <?php Display::display_icon($imageAnswer); ?>
		</td>
		<td width="40%">
			<?php
			echo $answer;
			?>
		</td>

		<?php if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) { ?>
		<td width="20%">
			<?php
            if ($studentChoice) {
				if ($answerCorrect) {
                    $color = 'green';
<<<<<<< HEAD
					//echo '<span style="font-weight: bold; color: #008000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
				} else {
                    $color = 'black';
                    //echo '<span style="font-weight: bold; color: #FF0000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
				}
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br(Text::make_clickable($answerComment)).'</span>';

			} else {
				if ($answerCorrect) {
					//echo '<span style="font-weight: bold; color: #000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
				} else {
                    //echo '<span style="font-weight: normal; color: #000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
=======
					//echo '<span style="font-weight: bold; color: #008000;">'.nl2br($answerComment).'</span>';
				} else {
                    $color = 'black';
                    //echo '<span style="font-weight: bold; color: #FF0000;">'.nl2br($answerComment).'</span>';
				}
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br($answerComment).'</span>';

			} else {
				if ($answerCorrect) {
					//echo '<span style="font-weight: bold; color: #000;">'.nl2br($answerComment).'</span>';
				} else {
                    //echo '<span style="font-weight: normal; color: #000;">'.nl2br($answerComment).'</span>';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
				}
			}
			?>
		</td>
			<?php
		    if ($ans==1) {
		        $comm = get_comments($id,$questionId);
			}
		    ?>
		 <?php } else { ?>
			<td>&nbsp;</td>
		<?php } ?>
		</tr>
		<?php
	}

    /** @todo check if this function is used */
    static function display_unique_image_answer($answerType, $studentChoice, $answer, $answerComment, $answerCorrect, $id, $questionId, $ans)
    {
        global $feedback_type;

        $imageType = (in_array($answerType, array(UNIQUE_ANSWER,UNIQUE_ANSWER_IMAGE, UNIQUE_ANSWER_NO_OPTION))) ? 'radio' : 'checkbox';
        $image = $imageType.($studentChoice ? '_on' : '_off');
        $image .= '.gif';

        $imageAnswer = $imageType.($answerCorrect ? '_on' : '_off');
        $imageAnswer .= '.gif';

        ?>
    <tr>
        <tr>
		<td width="5%">
            <?php Display::display_icon($image); ?>
		</td>
		<td width="5%">
            <?php Display::display_icon($imageAnswer); ?>
		</td>
        <td width="40%">
            <?php
            echo $answer;
            ?>
        </td>

        <?php if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) { ?>
        <td width="20%">
            <?php
            if ($studentChoice) {
                if ($answerCorrect) {
                    $color = 'green';
                    //echo '<span style="font-weight: bold; color: #008000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
                } else {
                    $color = 'black';
                    //echo '<span style="font-weight: bold; color: #FF0000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
                }
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br(Text::make_clickable($answerComment)).'</span>';

            } else {
                if ($answerCorrect) {
                    //echo '<span style="font-weight: bold; color: #000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
                } else {
                    //echo '<span style="font-weight: normal; color: #000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
                }
            }
            ?>
        </td>
        <?php
        if ($ans==1) {
            $comm = get_comments($id,$questionId);
        }
        ?>
        <?php } else { ?>
        <td>&nbsp;</td>
        <?php } ?>
    </tr>
        <?php
    }

    /**
     * Display the answers to a multiple choice question
     *
     * @param integer Answer type
     * @param integer Student choice
     * @param string  Textual answer
     * @param string  Comment on answer
     * @param string  Correct answer comment
     * @param integer Exercise ID
     * @param integer Question ID
     * @param boolean Whether to show the answer comment or not
     * @return void
     */
<<<<<<< HEAD
    static function display_multiple_answer_true_false($feedback_type, $answerType, $studentChoice, $answer, $answerComment, $answerCorrect, $id, $questionId, $ans) {
=======
    static function display_multiple_answer_true_false(
        $feedback_type,
        $answerType,
        $studentChoice,
        $answer,
        $answerComment,
        $answerCorrect,
        $id,
        $questionId,
        $ans,
        $in_results_disabled
    ) {
        $hide_expected_answer = false;
        if ($feedback_type == 0 && $in_results_disabled == 2) {
            $hide_expected_answer = true;
        }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        ?>
        <tr>
        <td width="5%">
        <?php

        $question 	 = new MultipleAnswerTrueFalse();
        $course_id   = api_get_course_int_id();
        $new_options = Question::readQuestionOption($questionId, $course_id);

        //Your choice
        if (isset($new_options[$studentChoice])) {
            echo get_lang($new_options[$studentChoice]['name']);
        } else {
        	echo '-';
        }

        ?>
        </td>
        <td width="5%">
        <?php

		//Expected choice
<<<<<<< HEAD
        if (isset($new_options[$answerCorrect])) {
            echo get_lang($new_options[$answerCorrect]['name']);
=======
        if (!$hide_expected_answer) {
            if (isset($new_options[$answerCorrect])) {
                echo get_lang($new_options[$answerCorrect]['name']);
            } else {
                echo '-';
            }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        } else {
            echo '-';
        }
        ?>
        </td>
        <td width="40%">
			<?php echo $answer; ?>
        </td>

        <?php if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) { ?>
        <td width="20%">
            <?php
            $color = "black";
            if (isset($new_options[$studentChoice])) {
                if ($studentChoice == $answerCorrect) {
                    $color = "green";
                }
<<<<<<< HEAD
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br(Text::make_clickable($answerComment)).'</span>';
=======
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br($answerComment).'</span>';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            }
            ?>
        </td>
            <?php
            if ($ans==1) {
                $comm = get_comments($id, $questionId);
            }
            ?>
         <?php } else { ?>
            <td>&nbsp;</td>
        <?php } ?>
        </tr>
        <?php
    }

     /**
     * Display the answers to a multiple choice question
     *
     * @param integer Answer type
     * @param integer Student choice
     * @param string  Textual answer
     * @param string  Comment on answer
     * @param string  Correct answer comment
     * @param integer Exercise ID
     * @param integer Question ID
     * @param boolean Whether to show the answer comment or not
     * @return void
     */
<<<<<<< HEAD
    static function display_multiple_answer_combination_true_false($feedback_type, $answerType, $studentChoice, $answer, $answerComment, $answerCorrect, $id, $questionId, $ans) {
=======
    static function display_multiple_answer_combination_true_false(
        $feedback_type,
        $answerType,
        $studentChoice,
        $answer,
        $answerComment,
        $answerCorrect,
        $id,
        $questionId,
        $ans,
        $in_results_disabled
    ) {
        $hide_expected_answer = false;
        if ($feedback_type == 0 && $in_results_disabled == 2) {
            $hide_expected_answer = true;
        }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        ?>
        <tr>
        <td width="5%">
        <?php
		//Your choice
        $question = new MultipleAnswerCombinationTrueFalse();
        if (isset($question->options[$studentChoice])) {
            echo $question->options[$studentChoice];
        } else {
            echo $question->options[2];
        }
        ?>
        </td>
        <td width="5%">
        <?php
		//Expected choice
        if (isset($question->options[$answerCorrect])) {
            echo $question->options[$answerCorrect];
        } else {
            echo $question->options[2];
        }
        ?>
        </td>
        <td width="40%">
            <?php
            //my answer
            echo $answer;
            ?>
        </td>

        <?php if ($feedback_type != EXERCISE_FEEDBACK_TYPE_EXAM) { ?>
        <td width="20%">
            <?php
            //@todo replace this harcoded value
            if ($studentChoice) {
                 $color = "black";
                if ($studentChoice == $answerCorrect) {
                    $color = "green";
                }
<<<<<<< HEAD
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br(Text::make_clickable($answerComment)).'</span>';
            }
            if ($studentChoice == 2 || $studentChoice == '') {
            	//echo '<span style="font-weight: bold; color: #000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
            } else {
				if ($studentChoice == $answerCorrect) {
	            	//echo '<span style="font-weight: bold; color: #008000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
				} else {
                    //echo '<span style="font-weight: bold; color: #FF0000;">'.nl2br(Text::make_clickable($answerComment)).'</span>';
=======
                echo '<span style="font-weight: bold; color: '.$color.';">'.nl2br($answerComment).'</span>';
            }
            if ($studentChoice == 2 || $studentChoice == '') {
            	//echo '<span style="font-weight: bold; color: #000;">'.nl2br($answerComment).'</span>';
            } else {
				if ($studentChoice == $answerCorrect) {
	            	//echo '<span style="font-weight: bold; color: #008000;">'.nl2br($answerComment).'</span>';
				} else {
                    //echo '<span style="font-weight: bold; color: #FF0000;">'.nl2br($answerComment).'</span>';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
				}
            }
            ?>
        </td>
            <?php
            if ($ans==1) {
                $comm = get_comments($id,$questionId);
            }
            ?>
         <?php } else { ?>
            <td>&nbsp;</td>
        <?php } ?>
        </tr>
        <?php
    }
}
