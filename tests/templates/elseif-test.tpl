{{if false }}ERROR{{ elseif false}}ERROR{{ elseif true }}1{{ /if }}
{{ if @condition }}ERROR{{ elseif @condition and false }}ERROR{{ elseif not @condition}}2{{ /if }}
{{if false }}ERROR{{elseif false and false }}ERROR{{ elseif true}}3{{/if }}