{{ page "index" }}

	{{ box }}
		{{ h1 "Website name" }}
		{{ nav }}
			{{ "Home" }}
			{{ "Over ons" }}
			{{ "Diensten" }}
			{{ "Contact" }}
		{{ /nav }}
	{{ /box }}

	{{ box }}

		{{ h2 "Onze diensten" }}

		{{ grid 3 }}

			{{ card }}
				{{ h3 "About us" }}
				{{ img }}
				{{ p 150 }}
			{{ /card }}

			{{ card }}
				{{ h3 "About us" }}
				{{ img }}
				{{ p 150 }}
			{{ /card }}

			{{ card }}
				{{ h3 "About us" }}
				{{ img }}
				{{ p 150 }}
			{{ /card }}

		{{ /grid }}

	{{ /box }}

	{{ box }}

		{{ h2 "About us" }}

		{{ grid 2 }}
			{{ img }}
			{{ box }}
				{{ h3 "Everything you need to know about us" }}
				{{ p 500 }}
				{{ p 200 }}
			{{ /box }}
		{{ /grid }}

		<br /><br />

		{{ grid 3 }}
			{{ box }}
				{{ h3 "Everything you need to know about us" }}
				{{ p 300 }}
				{{ p 150 }}
				{{ button "Read more" }}
			{{ /box }}
			{{ img }}
			{{ img }}
		{{ /grid }}

	{{ /box }}

	<br /><br />

	{{ box }}
		&copy; Copyright 2016
	{{ /box }}

{{ /page }}