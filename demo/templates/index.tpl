{{ extends @include_primary ? @include_secondary }}

	{{ block "main" }}
		Pretty slick
	{{ /block }}

{{ /extends }}

{{ @title ? @subtitle }}