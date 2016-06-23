{{ extends "base.tpl" }}

{{ block @page.block }}

	rendering in {{ @page.block }}

	{{ loop 5 }}
		{{ @page.title }}
	{{ /loop }}

{{ /block }}