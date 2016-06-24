<?php $this->renderHead( 'base.tpl'); ?><?php $this->setBlock( 'title', function() { ?>Welcome to my webpage<?php } ) ?><?php $this->setBlock( 'body', function() { ?>

		<div id="wrapper">

			<?php $this->renderHead( 'header.tpl'); ?><?php $this->prependBlock( 'title', function() { ?><?php echo htmlspecialchars($this->page->title); ?> - <?php } ) ?><?php $this->renderBody( 'header.tpl'); ?>

			<?php $this->setBlock( 'main', function() { ?>
				<div id="main">
					<?php $this->render($this->page->inc . '.tpl'); ?>
				</div>
			<?php } ) ?><?php $this->getBlock( 'main') ?>

			<?php $this->renderHead( 'footer.tpl'); ?><?php $this->appendBlock( 'copyright', function() { ?>

					<?php call_user_func( function() { ?><?php for( $i = 0; $i < 5; $i++ ): ?> - <a href="#"><?php echo htmlspecialchars($this->page->title); ?></a><?php endfor; ?><?php } ); ?>

				<?php } ) ?><?php $this->renderBody( 'footer.tpl'); ?>

		</div>

	<?php } ) ?><?php $this->renderBody( 'base.tpl'); ?>