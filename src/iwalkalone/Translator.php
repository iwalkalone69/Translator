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
     * Selected domain
     */
    protected $selected_domain = 'global';

    /**
     * Creates an instance with specified language or detects from browser
     */
    public function __construct(array $available_languages, $default_language, $directory = '.', $language = null, $extra_domains = [])
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
        foreach ($extra_domains as $domain) {
            $result = bindtextdomain($domain, $directory);
            $result = bind_textdomain_codeset($domain, "UTF-8");
        }
    }

    /**
     * Gets available languages
     */
    public function getAvailableLanguages()
    {
        return $this->available_languages;
    }

    /**
     * Sets available languages
     */
    public function setAvailableLanguages(array $available_languages)
    {
        $this->available_languages = $available_languages;
    }

    /**
     * Gets the default language
     */
    public function getDefaultLanguage()
    {
        return $this->default_language;
    }

    /**
     * Sets the default language if specified or detected are not available
     */
    public function setDefaultLanguage($default_language)
    {
        $this->default_language = $default_language;
    }

    /**
     * Gets directory where translations are
     */
    public function getDirectory()
    {
        return $this->directory;
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
    public function setLanguage($language)
    {
        $this->selected_language = $language;
        $result = putenv('LANGUAGE='.$this->selected_language.'.utf8');
        $result = setlocale(LC_ALL, $this->selected_language.'.utf8');
        $result = setlocale(LC_MESSAGES, $this->selected_language.'.utf8');
    }

    /**
     * Gets current selected domain
     */
    public function getDomain()
    {
        return $this->selected_domain;
    }

    /**
     * Selects a new domain
     */
    public function setDomain($domain)
    {
        $this->selected_domain = $domain;
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
    public function translate($str, $placeholders = null, $domain = null)
    {
        if ($domain) {
            $this->setDomain($domain);
        }
        $message = dgettext($this->getDomain(), $str);
        if ($placeholders && is_array($placeholders)) {
            foreach ($placeholders as $key => $value) {
                $message = str_replace('%'.$key.'%', $value, $message);
            }
        }

        return $message;
    }
}
