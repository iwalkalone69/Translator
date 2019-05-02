<?php

include(__DIR__.'/../../src/iwalkalone/Translator.php');

class translatorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testTranslationDefault()
    {
        $translator = new iwalkalone\Translator(['en_GB', 'en_US', 'ca_ES', 'es_ES'], 'es_ES', __DIR__.'/../../I18N');
        $str = 'HELLO_USERNAME';
        $translated = '¡Hola User!';
        $this->assertEquals($translated, $translator->translate($str, [
            'username' => 'User',
        ]));
    }

    public function testTranslationCustomLang()
    {
        $translator = new iwalkalone\Translator(['en_GB', 'en_US', 'ca_ES', 'es_ES'], 'es_ES', __DIR__.'/../../I18N', 'en_GB');
        $str = 'HELLO_USERNAME';
        $translated = 'Hello User!';
        $this->assertEquals($translated, $translator->translate($str, [
            'username' => 'User',
        ]));
        $translator = new iwalkalone\Translator(['en_GB', 'en_US', 'ca_ES', 'es_ES'], 'es_ES', __DIR__.'/../../I18N', 'ca_ES');
        $str = 'HELLO_USERNAME';
        $translated = 'Hola User!';
        $this->assertEquals($translated, $translator->translate($str, [
            'username' => 'User',
        ]));
    }

    public function testTranslationNotFound()
    {
        $translator = new iwalkalone\Translator(['en_GB', 'en_US', 'ca_ES', 'es_ES'], 'es_ES', __DIR__.'/../../I18N');
        $str = 'asdfasdfasldkfjañslkdfjñalskdfjñaldksjf';
        $this->assertEquals($str, $translator->translate($str, []));
    }

    public function testTranslationMultipleLanguages()
    {
        $translator = new iwalkalone\Translator(['en_GB', 'en_US', 'ca_ES', 'es_ES'], 'es_ES', __DIR__.'/../../I18N', 'en_GB');
        $str = 'HELLO_USERNAME';
        $translated = 'Hello User!';
        $this->assertEquals($translated, $translator->translate($str, [
            'username' => 'User',
        ]));
        $translator->setLanguage('es_ES');
        $translated = '¡Hola User!';
        $this->assertEquals($translated, $translator->translate($str, [
            'username' => 'User',
        ]));
        $translator->setLanguage('ca_ES');
        $translated = 'Hola User!';
        $this->assertEquals($translated, $translator->translate($str, [
            'username' => 'User',
        ]));
    }
}
