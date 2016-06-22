<h1>{{ @page.title }}</h1>

<ul>
	{{ loop @page.count }}
		<li>{{ @page.title }}</li>
	{{ /loop }}
</ul>

{{ include @page.template + ".tpl" }}

{{ block "main_" + @page.template + "_block" }}

	START BLOCK<br />

	{{ loop @page.count }}

		{{ @page.template }}<br />

	{{ /loop }}

	END BLOCK<br />

{{ /block }}