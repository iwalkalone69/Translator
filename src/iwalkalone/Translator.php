<?php

namespace iwalkalone;

/**
 * Class that implements translations using gettext
 *
 * @author iwalkalone
 */
class Translator
{
    /**
     * Default language if it is not specified or detected
     */
    protected $default_language = null;

    /**
     * Current available languages
     */
    protected $available_languages = [];

    /**
     * Selected language
     */
    protected $selected_language = null;

    /**
     * Creates an instance with specified language or detects from browser
     */
    public function __construct(array $available_languages, $default_language, $directory = '.', $language = null)
    {
        $this->setAvailableLanguages($available_languages);
	$this->setDefaultLanguage($default_language);
	$this->setDirectory($directory);
        if (!function_exists('gettext')) {
            throw new \Exception('FATAL: GetText is not available!');
        }
        if (!function_exists('locale_lookup')) {
            throw new \Exception('FATAL: intl PHP extension is not loaded!');
        }
        if ($language && in_array($language, $this->available_languages)) {
            $this->setLanguage($language);
        } else {
            $this->detectLanguage();
        }
        $result = bindtextdomain("global", $directory);
        $result = bind_textdomain_codeset("global", "UTF-8");
        $result = textdomain("global");
    }

    /**
     * Sets available languages
     */
    public function setAvailableLanguages(array $available_languages)
    {
        $this->available_languages = $available_languages;
    }

    /**
     * Sets the default language if specified or detected are not available
     */
    public function setDefaultLanguage($default_language)
    {
	$this->default_language = $default_language;
    }
    
    /**
     * Sets directory where translations are
     */
    public function setDirectory($directory)
    {
	$this->directory = $directory;
    }

    /**
     * Reteurns the selected language
     */
    public function getLanguage()
    {
        return $this->selected_language;
    }

    /**
     * Selects a new language
     */
    protected function setLanguage($language)
    {
        $this->selected_language = $language;
        $result = putenv('LANGUAGE='.$this->selected_language.'.utf8');
        $result = setlocale(LC_ALL, $this->selected_language.'.utf8');
    }

    /**
     * It detects language from browser headers
     */
    protected function detectLanguage()
    {
        if (!isset($_SERVER) || !isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->setLanguage($this->default_language);
        } else {
            $accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $language = locale_lookup($this->available_languages, $accept_language, true, $this->default_language);
            $this->setLanguage($language);
        }
    }

    /**
     * It translates a string with selected language and replaces keys for values using $placeholders
     */
    public function translate($str, $placeholders = null)
    {
        $message = gettext($str);
        foreach ($placeholders as $key => $value) {
            $message = str_replace('%'.$key.'%', $value, $message);
        }

        return $message;
    }
}