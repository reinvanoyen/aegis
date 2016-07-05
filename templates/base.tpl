{{ block "main" }}

	{{ block "header" }}
		<h1>{{ @title + " - something" }}</h1>
	{{ /block }}

{{ /block }}