<h1><?php echo htmlspecialchars($this->page->title); ?></h1>

<?php require $this->getBlock( 'side' ) ?>

<?php require $this->getBlock( 'main' ) ?>