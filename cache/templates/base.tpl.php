<?php $this->setBlock( 'id', function() { ?>

	Something

<?php } ) ?><h1><?php echo htmlspecialchars($this->page->title); ?></h1>

<?php $this->getBlock('id') ?>