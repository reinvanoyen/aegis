{{ if @condition }}test 1{{ /if }}
{{ if not @condition or @condition }}test 2{{ /if }}
{{ if @condition equals @condition }}test 3{{ /if }}
{{ if @condition and @condition }}test 4{{ /if }}
{{ if @condition and @condition and @condition }}test 5{{ /if }}
{{ if @condition and true }}test 6{{ /if }}
{{ if @condition and true }}{{ if true or @condition }}test 7{{ /if }}{{ /if }}
{{ if not @condition }}{{ else }}test 8{{ /if }}