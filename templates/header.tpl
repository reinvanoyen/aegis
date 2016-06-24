<header>

	<h1>{{ block "title" }}Website name{{ /block }}</h1>

	{{ block "nav" }}

		<ul>
			{{ loop 5 }}<li>Dit is default</li>{{ /loop }}
		</ul>

	{{ /block }}

</header>