<?php
/**
 * Elgg Poll plugin
 * @package Elggpoll
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @Original author John Mellberg
 * website http://www.syslogicinc.com
 * @Modified By Team Webgalli to work with ElggV1.5
 * www.webgalli.com or www.m4medicine.com
 */


if (isset($vars['entity'])) {

	//set img src
	$img_src = $vars['url'] . "mod/polls/graphics/poll.gif";

	$question = $vars['entity']->question;

	//get the array of possible responses
	$responses = polls_get_choice_array($vars['entity']);

	//get the array of user responses to the poll
	$user_responses = $vars['entity']->getAnnotations('vote', false);

	//create new array to store response and count
	$poll_results = array();
	$total_count = 0;
	foreach($responses as $response){
		$result = array(
			"response" => $response,
			"count" => polls_get_response_count($response, $user_responses)
		);
		
		$total_count += $result["count"];
		$poll_results[] = $result;
	}


	//populate array
	foreach($poll_results as $response) {
		//get count per response
		$response_count = polls_get_response_count($response, $user_responses);
			
		//calculate %
		$response_percentage = 0;
		if ($response["count"] && $total_count) {
			$response_percentage = round(100 / ($total_count / $response["count"]));
		}
		
		?>
<div class="progress_indicator">
	<label><?php echo $response["response"] . " (" . $response["count"] . ")"; ?> </label><br />
	<div class="progressBarContainer" align="left">
		<div class="polls-filled-bar" style="width: <?php echo $response_percentage; ?>%"></div>
	</div>
</div>
<br />
		<?php
	}
	?>

<p>
	<?php echo elgg_echo('polls:totalvotes') . " " . $total_count; ?>
</p>

<?php

} else {
	register_error(elgg_echo("polls:blank"));
	forward("polls/all");
}
