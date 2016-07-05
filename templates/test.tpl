{{ extends "base" }}

	{{ block "test" append }}

		<ul>
			{{ for @page in [ "Home", "About", "Contact" ] }}
				<li><a href="#" title="{{ @page }}">{{ @page }}</a></li>
			{{ /for }}
		</ul>

		{{ for 1 to 9 }}

			{{ if 5 === 5 }}

				inside if<br />

			{{ else }}

				inside else<br />

			{{ /if }}

		{{ /for }}

	{{ /block }}

{{ /extends }}