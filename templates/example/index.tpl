{{ extends "base" }}

	{{ block "title" }}OK sweet{{ /block }}

	{{ block "nav" }}

		<ul>
			{{ for @item in [ 'Home', 'Over ons', 'Contact ons' ] }}
				<li>{{ @item }}</li>
			{{ /for }}
		</ul>

	{{ /block }}

	{{ block "content" }}

		<div id="content">
			<p>
				Hello
			</p>
		</div>

	{{ /block }}

{{ /extends }}
