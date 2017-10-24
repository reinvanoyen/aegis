{{ block "test" }}

	{{ let @title be "This is a title" }}

	{{ @subtitle + ': ' + pi }}

{{ /block }}