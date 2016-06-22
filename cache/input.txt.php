<?php if( $page->title === 'Blog' ): ?>

	<?php echo htmlspecialchars('Pagina: ' . $page->title); ?><br />

	<?php echo htmlspecialchars('<h1>' . $page->title . '</h1>'); ?><br />

	<?php echo '<h1>' . $page->title . '</h1>'; ?>

	<h1><?php echo htmlspecialchars($page->title); ?></h1>
	<h2><?php echo htmlspecialchars($page->subtitle); ?></h2>

<?php endif; ?>

<?php if( $page->title === 'Home' ): ?>

	De pagina is HOME!<br />
	<?php echo htmlspecialchars($page->subtitle); ?>

<?php endif; ?>