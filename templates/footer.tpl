<footer>

	<h1>Footer</h1>

	{{ block "footer" }}
		Ok this is epic
	{{ /block }}

	<div id="bottom">
		{{ block "copyright" }}
			&copy; {{ @page.title }}
		{{ /block }}
	</div>

</footer>