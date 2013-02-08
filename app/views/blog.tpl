<h1>My Blog</h1>

<?php foreach($entries as $entry) : ?>

<h3><?php echo htmlentities($entry["title"]) ?></h3>
<p><?php echo htmlentities($entry["body"]) ?></p>
<p><?php echo \Rum::link("comments (".MyBlog\Models\Comments::count(array('entry_id'=>$entry["entry_id"])).")", 'comments', array('id'=>$entry["entry_id"])) ?></p>

<hr />

<?php endforeach; ?>

<?php echo \Rum::link('Add a new entry', 'new_entry') ?>
