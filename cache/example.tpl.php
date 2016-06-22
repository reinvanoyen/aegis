<h1><?php echo htmlspecialchars($page->title); ?></h1>

<ul>
	<?php for( $i = 0; $i < 5; $i++ ): ?>
		<li><?php echo htmlspecialchars($page->title . ' ' . $i); ?></li>
	<?php endfor; ?>
</ul>

<?php echo htmlspecialchars($i); ?>

<?php if( $page->title === 'Blog' ): ?>

	<?php $this->render($page->template . '.tpl'); ?>

<?php endif; ?>