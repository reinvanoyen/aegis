{{ extends "base" }}

	{{ block "main" prepend }}

		nice {{ "something " + 5 }}

		{{ loop 5 }}

			{{ raw "<strong>ok</strong>" }}

		{{ /loop }}

	{{ /block }}

{{ /extends }}