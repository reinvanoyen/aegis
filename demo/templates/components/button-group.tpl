<ul class="button-group" style="display: flex; list-style: none; padding-left: 0;">
	{{ slot "content" }}

		<li class="button-group__item">
			{{ component "components/button" }}{{ /component }}
		</li>

		<li class="button-group__item">
			{{ component "components/button" }}{{ /component }}
		</li>

	{{ /slot }}
</ul>