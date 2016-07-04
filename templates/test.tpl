{{ extends "base" }}

	{{ block "main" }}

		{{ "show me" }}<br />
		{{ include "include" }}<br />

		{{ r "<strong>ddd</strong>raw" }}

		Dit is dan weer een textnode

		{{ if 'main' === reverse( "niam" ) }}

			OK

		{{ /if }}

	{{ /block }}

{{ /extends }}