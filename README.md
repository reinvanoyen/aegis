# Aegis

##Template language (in development)

### Installation

```ssh
composer require reinvanoyen/aegis
```

### Basic API example

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