<?php $this->setBlock( 'footer', function() { ?>
		Ok this is epic
	<?php } ) ?><?php $this->setBlock( 'copyright', function() { ?>
		<div id="bottom">
			&copy; <?php echo htmlspecialchars($this->page->title); ?>
		</div>
	<?php } ) ?>