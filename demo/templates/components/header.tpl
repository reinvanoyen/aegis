<header class="header">

	<h1 class="header__title">{{ slot "title" }}Mijn website{{ /slot }}</h1>

	<div class="header__navigation">
		{{ component "components/main-nav" }}{{ /component }}
	</div>

</header>