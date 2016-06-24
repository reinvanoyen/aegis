<?php $this->renderHead( $this->page->base); ?><?php $this->setBlock( 'title', function() { ?>Welcome to my webpage<?php } ) ?><?php $this->setBlock( 'body', function() { ?>

		<div id="wrapper">
			
			<?php $this->renderHead( 'header'); ?><?php $this->appendBlock( 'title', function() { ?>: <?php echo htmlspecialchars($this->page->title); ?><?php } ) ?><?php $this->setBlock( 'nav', function() { ?>
			
					<?php foreach($this->pages as $this->p): ?>
			
						<li><a href="#" title="<?php echo htmlspecialchars($this->p->title); ?>"><?php echo htmlspecialchars($this->p->title); ?></a></li>
			
					<?php endforeach; ?>
			
				<?php } ) ?><?php $this->renderBody( 'header'); ?>
			
			<?php $this->setBlock( 'main', function() { ?>
				<div id="main">
					<?php $this->render($this->page->include); ?>
				</div>
			<?php } ) ?><?php $this->getBlock( 'main') ?>

			<?php $this->renderHead( 'footer'); ?><?php $this->appendBlock( 'copyright', function() { ?>
			
					<?php foreach($this->pages as $this->page): ?>
			
						- <a href="#" title="<?php echo htmlspecialchars($this->p->title); ?>"><?php echo htmlspecialchars($this->p->title); ?></a>
			
					<?php endforeach; ?>

				<?php } ) ?><?php $this->renderBody( 'footer'); ?>

		</div>

	<?php } ) ?><?php $this->renderBody( $this->page->base); ?>