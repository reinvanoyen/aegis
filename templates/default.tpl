<h1>Rendering {{ @page.title }}</h1>

{{ block "main" }}

	<h2>block main {{ @page.title }}</h2>

	{{ include @page.inc + ".tpl" }}

	{{ if @page.title === "Home" }}

		<br />OK NICE

	{{ /if }}

{{ /block }}
