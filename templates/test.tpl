{{ extends "base" }}

	{{ block "main" prepend }}

		{{ slugify( 'reverse me' ) }}<br />
		{{ sum( 1, 5, 6, 8 ) }}<br />

	{{ /block }}

{{ /extends }}