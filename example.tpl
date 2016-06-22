<h1>{{ @page.title }}</h1>

<ul>
	{{ loop 5 }}
		<li>{{ @page.title + " " + @i }}</li>
	{{ /loop }}
</ul>

{{ @i }}

{{ if @page.title === "Blog" }}

	{{ include @page.template + ".tpl" }}

{{ /if }}