{{ extends "base.tpl" }}

	{{ block "title" }}Welcome to my webpage{{ /block }}

	{{ block "body" }}

		<div id="wrapper">

			{{ extends "header.tpl" }}

				{{ block "title" }}{{ @page.title }}{{ /block }}

			{{ /extends }}

			{{ block "main" }}
				<div id="main">
					{{ include @page.inc + ".tpl" }}
				</div>
			{{ /block }}

			{{ extends "footer.tpl" }}{{ /extends }}

		</div>

	{{ /block }}

{{ /extends }}