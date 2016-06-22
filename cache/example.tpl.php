<h1><?php echo htmlspecialchars($this->page->title); ?></h1>

<ul>
	<?php call_user_func( function() { ?><?php for( $i = 0; $i < $this->page->count; $i++ ): ?>
		<li><?php echo htmlspecialchars($this->page->title); ?></li>
	<?php endfor; ?><?php } ); ?>
</ul>

<?php if( $this->page->title === 'Blog' ): ?>

	<?php $this->render($this->page->template . '.tpl'); ?>

<?php endif; ?>