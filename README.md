# Translator

A translator library using PHP gettext extension.

Example code to autodetect language using headers sent by client:

<pre>
<code>$available_languages = [
  'en_GB',
  'en_US',
  'ca_ES',
  'es_ES',
];
$default_language = 'ca_ES';
$path_to_translations = './locale';
$translator = new \iwalkalone\Translator($available_languages, $default_language, $path_to_translations);
$str = 'Hello!';
$translated = $translator->translate($str);</code>
</pre>

It also accepts placeholders. In next example, %username% is replaced for Mark after getting the translation.

<pre>
<code>$str = 'Hello %username%!';
$translated = $translator->translate($str, [
  'username' => 'Mark',
]);</code>
</pre>

You can disable language autodetection, specifying one:

<pre>
<code>$translator = new \iwalkalone\Translator($available_languages, $default_language, $path_to_translations, 'en_GB');</code>
</pre>
