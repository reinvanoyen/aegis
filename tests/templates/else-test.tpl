{{ if @condition }}ERROR{{ else}}1{{ /if }}
{{ if not @condition and @condition }}ERROR{{ else}}2{{ /if }}
{{ if @condition }}ERROR{{ elseif @condition and false }}ERROR{{ elseif @condition and @condition }}ERROR{{else }}3{{ /if }}
{{if @condition }}ERROR{{ elseif @condition and false }}ERROR{{elseif @condition and @condition }}ERROR{{else }}4{{ /if }}