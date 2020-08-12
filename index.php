<?php
require './vendor/autoload.php';
$q = new \App\Question();
foreach(json_decode(file_get_contents('questions.json')) as $question) {
    $q->insert([
        'statement' => $question->statement . '| unique',
        'options' => serialize($question->options),
        'answer' => $question->answer,
        'level' => $question->level,
        'category' => $question->category
    ]);
}
$questions = $q->getFilteredQuestions();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Leo Quizzer</title>
		<link rel="stylesheet" href="style.min.css" />
	</head>
	<body>
		<div id="container">
			<div id="wrapper">
				<div class="quizzer-wrapper">
					<section class="quizzer-head">
						<h3>Leo Quizzer</h3>
						<div class="flex">
							<div class="left">
                                Total Questions: <span id="totalQ"><?php echo count($questions) ?></span>
							</div>
							<div class="right timer">
                                Time Left: <span id="seconds">10 sec</span>
							</div>
						</div>
						<div class="result" style="display: none;"></div>
					</section>
					<section class="quizzer-body">
						<ol id="quizzer-questions">
                            <?php foreach( $questions as $k => $q ): ?>
                            <li class="quizzer-q quizzer-q-<?php echo $k ?>">
                                <span class="statement"><?php echo $q['statement'] ?></span>
                                <div class="q-opts-wrapper">
                                    <?php foreach($q['options'] as $i => $opt) : ?>
                                        <label for="q-<?php echo $k ?>-opt-<?php echo $i ?>">
                                            <input type="radio" name="q-<?php echo $k ?>" id="q-<?php echo $k ?>-opt-<?php echo $i ?>"><?php echo $opt ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>
						<button id="q-submit">Submit</button>
					</section>
				</div>
			</div>
		</div>
	</body>
</html>
