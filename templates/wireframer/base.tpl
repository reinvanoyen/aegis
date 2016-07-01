<html>

	<head>

		<title>Wireframe</title>

		<style type="text/css">

			html
			{
				font-size: 62.5%;
			}

			body
			{
				font-family: sans-serif;
				line-height: 2;
				background-color: white;
			}

			button
			{
				display: inline-block;
				border: 0;
				border-radius: 3px;
				padding: 0 15px;
			}

			.img
			{
				width: 100%;
				padding-top: 50%;
				background-color: #eee;
				border: 2px solid #ccc;
			}

			.box
			{
				display: flex;
			}

			.box.vertical
			{
				flex-direction: column;
			}

			.box.horizontal
			{
				flex-direction: row;
				justify-content: space-between;
			}

			.nav
			{
				display: flex;
				list-style: none;
			}

			.nav.vertical
			{
				flex-direction: column;
			}

			.nav.horizontal
			{
				flex-direction: row;
				justify-content: space-between;
				margin: 0 -15px;
			}

			.nav.horizontal li
			{
				padding: 0 15px;
			}

		</style>

	</head>

	<body>
		{{ block "main" }}{{ /block }}
	</body>

</html>