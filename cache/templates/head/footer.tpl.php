<?php $this->setBlock( 'footer', function() { ?>
		Ok this is epic
	<?php } ) ?><?php $this->setBlock( 'copyright', function() { ?>
			&copy; <?php echo htmlspecialchars($this->page->title); ?>
		<?php } ) ?>