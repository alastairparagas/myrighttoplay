<?php require('format/header.php'); ?>

<div class='group'>
	<div class='grid-6 offset-3'>
		<h1 class='bold'>Contact Us</h1>
		<p>Want to drop us some fan-mail love or having a problem with the website? Do not hesitate to drop us a message below!</p>
		<form method='POST' action='ajax/contact.php' id='contact_form'>
		<label>Full Name<input type='text' name='full_name' /></label>
		<label>Subject<input type='text' name='subject' /></label>
		<label>Message<textarea name='message'></textarea></label>
		<label><input type='submit' value='Send Message' /></label>
		</form>
	</div>
</div>

<?php require('format/footer.php'); ?>