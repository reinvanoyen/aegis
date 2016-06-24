{{ extends @page.basetpl + ".tpl" }}

	{{ block "title" }}Welcome to my webpage{{ /block }}

	{{ block "body" }}

		<div id="wrapper">
			
			{{ extends "header.tpl" }}
			
				{{ block "title" append }}: {{ @page.title }}{{ /block }}
			
				{{ block "nav" }}
			
					{{ for @p in @pages }}
			
						<li><a href="#" title="{{ @p.title }}">{{ @p.title }}</a></li>
			
					{{ /for }}
			
				{{ /block }}
			
			{{ /extends }}
			
			{{ block "main" }}

				<div id="main">
					
					{{ include @page.inc + ".tpl" }}
					
				</div>

			{{ /block }}

			{{ extends "footer.tpl" }}
			
				{{ block "copyright" append }}
			
					{{ for @p in @pages }}
			
						- <a href="#" title="{{ @p.title }}">{{ @p.title }}</a>
			
					{{ /for }}

				{{ /block }}

			{{ /extends }}

		</div>

	{{ /block }}

{{ /extends }}