<?php
require './vendor/autoload.php';
session_start();
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

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['oldQuestions'] = $questions;
}
$errors = [];
// on submit
if(@$_POST) {
    $oldQuestions = $_SESSION['oldQuestions'];
    $correctOnes = [];
    for($i=0; $i < count($oldQuestions); $i++) {
        if($_POST['q-' . $i] === $oldQuestions[$i]['options'][(int) $oldQuestions[$i]['answer']]) {
            $correctOnes[] = $i;
        }
    }
    $result = round(count($correctOnes) / count($oldQuestions), 2) * 100;
}

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
							<div class="left">Total Questions: <span id="totalQ"><?php echo count($questions) ?></span></div>
							<div class="right timer">Time Left: <span id="seconds"></span></div>
						</div>
                        <?php if(@$_POST): ?>
						<div class="result">
                            <span>You secured: <?php echo $result ?>%</span>
                        </div>
                        <?php endif; ?>
					</section>
                    <?php if(!@$_POST): ?>
					<section class="quizzer-body">
                        <form method="POST">
                            <?php if(count($errors) > 0) : ?>
                                <ul class="errors">
                                    <?php foreach($errors as $err): ?>
                                    <li><?php echo $err; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <ol id="quizzer-questions">
                                <?php foreach( $questions as $k => $q ): ?>
                                <li class="quizzer-q quizzer-q-<?php echo $k ?>">
                                    <span class="statement"><?php echo $q['statement'] ?></span>
                                    <div class="q-opts-wrapper">
                                        <?php foreach($q['options'] as $i => $opt) : ?>
                                            <label for="q-<?php echo $k ?>-opt-<?php echo $i ?>">
                                                <input type="radio" name="q-<?php echo $k ?>" id="q-<?php echo $k ?>-opt-<?php echo $i ?>" value="<?php echo $opt ?>" required><?php echo $opt ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ol>
                            <button id="q-submit">Submit</button>
                        </form>
					</section>
                    <?php else: ?>
                    <a href="./">Reattempt</a>
                    <?php endif; ?>
				</div>
			</div>
		</div>
	</body>
</html>
