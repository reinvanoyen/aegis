{{ page "index" }}

	{{ box h }}

		{{ h2 "Title of the website" }}

	{{ /box }}

	{{ box v }}

		{{ h1 "Dit is een titel" }}

		{{ button "Home" }}
		{{ button "Over ons" }}
		{{ button "Diensten" }}
		{{ button "Nieuws" }}
		{{ button "Contact" links to "contact" }}

	{{ /box }}

	{{ box }}

		{{ p "Copyright Rein Van Oyen" }}

	{{ /box }}

{{ /page }}