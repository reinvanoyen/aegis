# Aegis

##Template language (in development)

### Basic API Usage

```html
Hello, your name is {{ @name }}!
```

```php
$tpl = new \Aegis\Template();
$tpl->name = 'Rein';
echo $tpl->render('example');
```