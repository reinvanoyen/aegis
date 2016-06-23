<?php $this->setBlock( 'main', function() { ?>

	<h2>block main <?php echo htmlspecialchars($this->page->title); ?></h2>

	<?php $this->render($this->page->inc . '.tpl'); ?>

	<?php if( $this->page->title === 'Home' ): ?>

		<br />OK NICE

	<?php endif; ?>

<?php } ) ?><h1>Rendering <?php echo htmlspecialchars($this->page->title); ?></h1>

<?php $this->getBlock( 'main') ?>