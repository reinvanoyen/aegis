<html>
<head>
	<title>
		{{ block "title" }}Jacobs Woonconcept{{ /block }}
	</title>
</head>
<body>
	{{ component "components/header" }}{{ /component }}
	{{ block "main" }}{{ /block }}
	{{ component "components/footer" }}{{ /component }}
</body>
</html>