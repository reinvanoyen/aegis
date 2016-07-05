{{ module "pages" }}

	{{ header "Pages" }}
		{{ link "create" }}
	{{ /header }}

	{{ index }}
		{{ string "title" }}
		{{ link "delete" }}
	{{ /index }}

	{{ action "create" }}
		{{ stack horizontal }}
			{{ string "title" }}
			{{ string "slug" }}
		{{ /stack }}
		{{ string "short_introduction" multiline }}
		{{ richtext "body" }}
	{{ /action }}

	{{ action "edit" }}
		{{ stack horizontal }}
			{{ string "title" }}
			{{ string "slug" }}
		{{ /stack }}
		{{ string "short_introduction" multiline }}
		{{ richtext "body" }}
	{{ /action }}

	{{ action "delete" }}{{ /action }}

{{ /module }}

{{ module "projects" }}

	{{ header "Projects" }}
		{{ link "create" }}
	{{ /header }}

	{{ stack horizontal }}

		{{ index }}
			{{ string "title" }}
		{{ /index }}

		{{ index }}
			{{ string "title" }}
		{{ /index }}

	{{ /stack }}

{{ /module }}