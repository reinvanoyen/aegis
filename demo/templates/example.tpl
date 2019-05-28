{{ extends "base" }}

	{{ block "title" prepend }}Items - {{ /block }}

	{{ block "main" }}

		{{ for 1 to 6 as @index }}

			{{ component "components/card-grid" }}

				{{ slot "content" }}

					{{ for 1 to @index as @newIndex }}

						{{ component "components/card" }}

							{{ slot "photo" }}
								{{ component "components/photo" }}
									{{ slot "src" }}image-{{ @newIndex }}-{{ @index }}.jpg{{ /slot }}
								{{ /component }}
							{{ /slot }}

							{{ slot "title" }}
								Card title #{{ @newIndex }}
							{{ /slot }}

							{{ slot "subtitle" }}
								Dit is de subtitle van {{ @newIndex }}
							{{ /slot }}

						{{ /component }}

					{{ /for }}

				{{ /slot }}

			{{ /component }}

		{{ /for }}

	{{ /block }}

{{ /extends }}