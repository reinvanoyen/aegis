{{ extends "base" }}

	{{ block "main" append }}

		Does it work?<br />

		{{ loop 5 }}{{ raw "<strong>bold</strong><br />" }}{{ /loop }}

	{{ /block }}

{{ /extends }}