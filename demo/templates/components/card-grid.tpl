<div style="display: grid; grid-gap: 25px; grid-template-columns: repeat(6, 1fr);">
	{{ slot "content" }}
		{{ for @card in [ 'Een item', 'Nog een item', 'Nog eentje' ] }}

			{{ component "components/card" }}{{ /component }}

		{{ /for }}
	{{ /slot }}
</div>