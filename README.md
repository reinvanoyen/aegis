# Aegis

[![Build Status](https://travis-ci.org/reinvanoyen/aegis.svg?branch=master)](https://travis-ci.org/reinvanoyen/aegis)

##Template language (in development)

### Introduction

**Aegis is a flexible, dynamic templating language.** It aims to 
be truly extendable. It comes with a default set of functionalities to help you with 
the basics of creating web layouts (template inheritance, printing data, iteration helpers, etc).
However, if you decide to implement your own nodes and use it as a language with a 
completely different purpose, you're encouraged to do so.
Actually, that's what this template language is all about!

### Installation

```ssh
composer require reinvanoyen/aegis
```

### Default runtime documentation

* Extends
* Block
* For
* Raw
* Include

### Default runtime API example

header.tpl
```html
<header>
    <h1>{{ block "title" }}{{ /block }}</h1>
    <ul>
        {{ for @item in [ 'Home', 'About', 'Contact' ] }}
            <li>{{ @item }}</li>
        {{ /for }}
    </ul>
</header>
```

footer.tpl
```html
<footer>
    &copy; {{ @year }} {{ @title }}
</footer>
```

view.tpl
```
{{ extends "header" }}
    {{ block "title" }}{{ @title }}{{ /block }}
{{ /extends }}

<h2>{{ @page }}</h2>
<p>{{ @content }}</p>

{{ extends "footer" }}{{ /extends }}
```

index.php
```php
$tpl = new \Aegis\Template();

// Assign some data
$tpl->title = 'Example website';
$tpl->page = 'Welcome to this page';
$tpl->content = 'This is the first paragraph';
$tpl->year = date('Y');

echo $tpl->render('view');
```

### Creating your own runtime

Coming soon

### Contributing

Feel free to contribute to Aegis, any help is greatly appreciated.

### License
Aegis is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).