<footer>

	<h1>Footer</h1>

	{{ block "footer" }}
		Ok this is epic
	{{ /block }}

	{{ block "copyright" }}
		<div id="bottom">
			&copy; {{ @page.title }}
		</div>
	{{ /block }}

</footer>