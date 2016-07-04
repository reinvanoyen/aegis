<ul>
	{{ for @page in [ 1, 2, 3 ] }}
		<li>{{ @page }}</li>
	{{ /for }}
</ul>

{{ for 5 in 9 }}
	ok
{{ /for }}