<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

class EnforceContentSecurity
{
    /**
     * Security header name
     *
     * @var string
     */
    protected $header = 'Content-Security-Policy';

    /**
     * Directive separator
     *
     * @var string
     */
    protected $directiveSeparator = ';';

    /**
     * Source separator
     *
     * @var string
     */
    protected $sourceSeparator = ' ';

    /**
     * Configuration for session
     *
     * @var array
     */
    protected $config = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $profiles = $this->getProfilesFromArguments(func_get_args());
        array_map([$this, 'loadProfileByKey'], $profiles);
        $this->addPolicy($response);

        return $response;
    }

    /**
     * Add content security policy header to response
     *
     * @param Response  $response
     *
     * @return  void
     */
    protected function addPolicy(Response &$response)
    {
        $this->loadDefaultProfiles();

        $currentDirectives = $this->decodeConfiguration(
            $response->headers->get($this->header)
        );

        $this->mergeProfileWithConfig($currentDirectives);

        $newDirectives = $this->encodeConfiguration();

        if ($newDirectives) {
            $response->header($this->header, $newDirectives);
        }
    }

    /**
     * Load default profiles
     *
     * @return void
     */
    protected function loadDefaultProfiles()
    {
        $defaultProfiles = $this->getArrayFromValue(
            config('security.content.default')
        );

        array_map([$this, 'loadProfileByKey'], $defaultProfiles);
    }

    /**
     * Load a specific profile
     *
     * @param  string $key
     *
     * @return void
     */
    protected function loadProfileByKey($key)
    {
        $profile = config('security.content.profiles.'.$key);
        if (is_array($profile)) {
            $this->mergeProfileWithConfig($profile);
        }
    }

    /**
     * Merge a given profile with current configuration
     *
     * @param  array $profile
     *
     * @return void
     */
    protected function mergeProfileWithConfig(array $profile)
    {
        foreach ($profile as $directive => $values) {
            if (!isset($this->config[$directive])) {
                $this->config[$directive] = [];
            }

            $values = $this->getArrayFromValue($values);

            $this->config[$directive] = array_merge($this->config[$directive], $values);
        }
    }

    /**
     * Decode a given string into configuration
     *
     * @param  string $string
     *
     * @return array
     */
    private function decodeConfiguration($string)
    {
        $config = [];
        $directives = explode($this->directiveSeparator, $string);
        foreach ($directives as $directive) {
            $parts = array_filter(explode($this->sourceSeparator, $directive));
            $key = trim(array_shift($parts));
            $config[$key] = $parts;
        }

        return $config;
    }

    /**
     * Encode the current configuration as string
     *
     * @return string
     */
    private function encodeConfiguration()
    {
        $value = [];
        ksort($this->config);
        foreach ($this->config as $directive => $values) {
            $values = array_unique($values);
            sort($values);
            array_unshift($values, $directive);
            $string = implode($this->sourceSeparator, $values);

            if ($string) {
                $value[] = $string;
            }
        }

        return implode($this->directiveSeparator . ' ', $value);
    }

    /**
     * Create array from value
     *
     * @param  mixed $value
     *
     * @return array
     */
    private function getArrayFromValue($value)
    {
        if (!is_array($value)) {
            $value = explode(',', $value);
        }

        return $value;
    }

    /**
     * Get profiles from handle method arguments
     *
     * @param  array $arguments
     *
     * @return array
     */
    private function getProfilesFromArguments(array $arguments)
    {
        $profiles = [];

        if (count($arguments) > 2) {
            unset($arguments[0]);
            unset($arguments[1]);
            $profiles = $arguments;
        }

        return $profiles;
    }
}
