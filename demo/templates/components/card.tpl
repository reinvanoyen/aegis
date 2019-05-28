<div style="border: 5px solid red;">

	<h1>Hello</h1>

	<div class="card__photo">
		{{ slot "photo" }}
			{{ component "components/photo" }}{{ /component }}
		{{ /slot }}
	</div>

	<div class="card__badge">
		{{ slot "badge" }}{{ /slot }}
	</div>

	<div class="card__main">

		<div class="card__header">
			<h3 class="card__title">
				{{ slot "title" }}Default title{{ /slot }}
			</h3>
			<h4 class="card__subtitle">
				{{ slot "subtitle" }}{{ /slot }}
			</h4>
		</div>

		<div class="card__content">
			{{ slot "content" }}{{ /slot }}
		</div>
		<div class="card__footer">
			{{ slot "footer" }}
				{{ component "components/button-group" }}{{ /component }}
			{{ /slot }}
		</div>

	</div>

</div>