{{ block "body" }}

	<div id="wrapper">

		{{ extends "header" }}
			{% block "title" %}<h1>{{ @title + " - something" }}</h1>{{ /block }}
		{{ /extends }}

		{{ block "main" }}
			<div id="main"></div>
		{{ /block }}

		{{ extends "footer" }}{{ /extends }}

	</div>

{{ /block }}