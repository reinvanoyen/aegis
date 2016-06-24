<?php $this->setBlock( 'title', function() { ?>Website name<?php } ) ?><?php $this->setBlock( 'nav', function() { ?>

		<ul>
			<?php call_user_func( function() { ?><?php for( $i = 0; $i < 5; $i++ ): ?><li>Dit is default</li><?php endfor; ?><?php } ); ?>
		</ul>

	<?php } ) ?>