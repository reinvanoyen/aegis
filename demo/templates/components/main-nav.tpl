<ul class="main-nav" style="display: flex; list-style: none; padding: 0;">

	{{ for @item in ['Home', 'Events', 'About', 'Contact'] }}

		<li class="main-nav__item">
			{{ component "components/main-nav-item" }}
				{{ slot "title" }}{{ @item }}{{ /slot }}
			{{ /component }}
		</li>

	{{ /for }}

</ul>