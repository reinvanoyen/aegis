{{ extends "base.tpl" }}

	{{ block "title" }}Welcome to my webpage{{ /block }}

	{{ block "body" }}

		<div id="wrapper">

			{{ extends "header.tpl" }}

				{{ block "title" prepend }}{{ @page.title }} - {{ /block }}

			{{ /extends }}

			{{ block "main" }}
				<div id="main">
					{{ include @page.inc + ".tpl" }}
				</div>
			{{ /block }}

			{{ extends "footer.tpl" }}

				{{ block "copyright" append }}

					{{ loop 5 }} - <a href="#">{{ @page.title }}</a>{{ /loop }}

				{{ /block }}

			{{ /extends }}

		</div>

	{{ /block }}

{{ /extends }}