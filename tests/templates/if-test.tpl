{{ if @condition}}1{{ /if }}
{{if not @condition or @condition }}2{{ /if }}
{{ if @condition equals @condition}}3{{/if }}
{{if @condition and @condition }}4{{ /if}}
{{ if @condition and @condition and @condition }}5{{ /if }}
{{if @condition and true }}6{{ /if}}
{{ if @condition and true }}{{ if true or @condition }}7{{ /if }}{{ /if }}
{{ if not @condition }}{{ else }}8{{ /if }}
{{if not @condition}}{{ else }}9{{ /if}}
{{ if false and true or true }}10{{ /if }}