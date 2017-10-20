{{ set @greeting "Welcome, " }}
{{ set @first_name "Rein" }}
{{ set @last_name "Van Oyen" }}

{{ block "greeting" }}
	{{ set @full @greeting + @first_name }}
{{ /block }}

{{ include "include01" }}