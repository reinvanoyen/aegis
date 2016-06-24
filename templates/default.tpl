{{ extends @page.base }}

	{{ block "title" }}Welcome to my webpage{{ /block }}

	{{ block "body" }}

		<div id="wrapper">
			
			{{ extends "header" }}
			
				{{ block "title" append }}: {{ @page.title }}{{ /block }}
			
				{{ block "nav" }}
			
					{{ for @p in @pages }}
			
						<li><a href="#" title="{{ @p.title }}">{{ @p.title }}</a></li>
			
					{{ /for }}
			
				{{ /block }}
			
			{{ /extends }}
			
			{{ block "main" }}
				<div id="main">
					{{ include @page.include }}
				</div>
			{{ /block }}

			{{ extends "footer" }}
			
				{{ block "copyright" append }}
			
					{{ for @page in @pages }}
			
						- <a href="#" title="{{ @p.title }}">{{ @p.title }}</a>
			
					{{ /for }}

				{{ /block }}

			{{ /extends }}

		</div>

	{{ /block }}

{{ /extends }}