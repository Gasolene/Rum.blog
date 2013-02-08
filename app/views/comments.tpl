<h1>Comments</h1>

<?php foreach($comments as $comment) : ?>

<h3><?php echo htmlentities($comment["author"]) ?></h3>
<p><?php echo htmlentities($comment["body"]) ?></p>

<hr />

<?php endforeach; ?>

<?php $this->form->render() ?>

<hr />

<?php echo \Rum::link('Return to blog', 'blog') ?>