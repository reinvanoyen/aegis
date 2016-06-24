{{ extends "base.tpl" }}

	{{ block "main" }}

		<h2>block main {{ @page.title }}</h2>

		{{ include @page.inc + ".tpl" }}

		{{ if @page.title === "Home" }}

			<br />OK NICE

		{{ /if }}

	{{ /block }}

{{ /extends }}