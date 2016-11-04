# Aegis

[![Build Status](https://travis-ci.org/reinvanoyen/aegis.svg?branch=master)](https://travis-ci.org/reinvanoyen/aegis)

##Template language (in development)

### Introduction

**Aegis is a flexible, dynamic templating language.** It aims to 
be truly extendable. It comes with a default set of functionalities to help you with 
the basics of creating web layouts (template inheritance, printing data, iteration helpers, etc).
However, if you decide to implement your own nodes and use it as a language with a 
completely different purpose, you're encouraged to do so.
Actually, that's what Aegis is all about!

### Installation

```ssh
composer require reinvanoyen/aegis
```

### Default runtime documentation

* Printing data
* [Block](#Block)
* Extends
* If, else and elseif
* For
* Raw
* Include

#### Printing data

```html
<ul>
    <li>Some raw text</li>
    <li>{{ @variable }}</li>
    <li>{{ 5 }}</li>
    <li>{{ 'some string' }}</li>
    <li>{{ "some string" }}</li>
    <li>{{ "some string " + @variable }}</li>
    <li>{{ @variable + @variable }}</li>
</ul>
```

#### Block

The `block` tag defines a container of sorts. Each time a block tag is used, 
the contents of that container are outputted. The content of a block can 
be modified at any time.

```html
<h1>{{ block "title" }}Aegis{{ /block }}</h1>
<h2>{{ block "baseline" }}The template language{{ /block }}</h2>
```

In the above example two block tags, each with a different name, were used. The above would output:

```html
<h1>Aegis</h1>
<h2>The template language</h2>
```

Of course this is nothing spectacular, it get more interesting as we 
modify the contents of these blocks by using the options `append` or `prepend` like in the example below.

```html
<h1>{{ block "title" }}Aegis{{ /block }}</h1>
<h2>{{ block "baseline" }}template language{{ /block }}</h2>
<p>
{{ block "title" append }}...{{ /block }}
{{ block "baseline" prepend }}The {{ /block }}
</p>
```

The above example modifies the contents of the "title" and "baseline" block by appending and prepending content. The output would be:

```html
<h1>Aegis...</h1>
<h2>The template language</h2>
<p>Aegis... The template language</p>
```

Remember that every use of the block tag also outputs the content, even if the tag modifies the content. All modifications to the 
content of a block are executed first, so the output of a block will always be the output after all the modifications have executed.
This can be confusing at first, but once you get the hang of it, it can provide you with great flexibility.

The contents of a block can be completely overwritten by simply using the block with new content, like so:

```html
<h1>{{ block "title" }}That other template language{{ /block }}</h1>
<h2>{{ block "baseline" }}template language{{ /block }}</h2>
<p>
{{ block "title" }}Aegis{{ /block }}, 
{{ block "baseline" prepend }}The {{ /block }}
</p>
```

This would output:
```html
<h1>Aegis</h1>
<h2>The template language</h2>
<p>Aegis, The template language</p>
```

Emptying a block is possible by using the block tag with no content at all:
```html
{{ block "title" }}Aegis{{ /block }}
{{ block "title" }}{{ /block }}
```

The above example would simply output nothing at all.

#### Extends

The `extends` tag brings in a template from another file, provides 
functionality to manipulate the blocks provided by that template and outputs the results. In comparison to many template engines
an Aegis template is not limited to one extends tag. This makes it possible to use 
the extends tag as some sort of mixin, like in the below example.

header.tpl
```html
<header>
    <h1>{{ block "title" }}{{ /block }}</h1>
</header>
```

footer.tpl
```html
<footer>
    &copy; 2016 Aegis
</footer>
```

layout.tpl
```html
{{ extends "header" }}
    {{ block "title" }}Aegis, the template language{{ /block }}
{{ /extends }}

{{ extends "footer" }}{{ /extends }}
```

#### If, else and elseif

The `if` tag allows for conditional rendering within templates.

```html
{{ if @show }}Show this content{{ /if }}
```

```html
{{ if @show }}
    Show this
{{ else }}
    Nothing to show
{{ /if }}
```

```html
{{ if @title equals "Aegis" }}
    {{ @title }}
{{ elseif @number equals 5 or @number equals 10 }}
    The title is not Aegis, but the number is {{ @number }}
{{ else }}
    The title is not Aegis nor is the number 5 or 10
{{ /if }}
```

#### For

```html
{{ for @contributor in ['Rein Van Oyen'] }}
    {{ @contributor }}
{{ /for }}
```

```html
{{ for @contributor in @contributors }}
    {{ @contributor }}
{{ /for }}
```

```html
<ul>
    {{ for 0 to 10 }}
        <li>Aegis</li>
    {{ /for }}
</ul>
```

```html
<ul>
    {{ for 0 to 10 as @index }}
        <li>Aegis #{{ @index }}</li>
    {{ /for }}
</ul>
```

### Creating your own runtime

Coming soon...

### Contributing

Feel free to contribute to Aegis, any help is greatly appreciated. Please keep your coding style compatible with [PSR-2](http://www.php-fig.org/psr/psr-2/).

### License
Aegis is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).