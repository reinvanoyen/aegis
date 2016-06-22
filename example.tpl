<h1>{{ @page.title }}</h1>

<ul>
	{{ loop @page.count }}
		<li>{{ @page.title }}</li>
	{{ /loop }}
</ul>

{{ if @page.title === "Blog" }}

	{{ include @page.template + ".tpl" }}

{{ /if }}