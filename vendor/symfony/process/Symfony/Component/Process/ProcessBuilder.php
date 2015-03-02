<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Process;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;

/**
 * Process builder.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 */
class ProcessBuilder
{
    private $arguments;
    private $cwd;
    private $env = array();
    private $stdin;
    private $timeout = 60;
    private $options = array();
    private $inheritEnv = true;
    private $prefix = array();

<<<<<<< HEAD
    /**
     * Constructor
     *
     * @param string[] $arguments An array of arguments
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function __construct(array $arguments = array())
    {
        $this->arguments = $arguments;
    }

<<<<<<< HEAD
    /**
     * Creates a process builder instance.
     *
     * @param string[] $arguments An array of arguments
     *
     * @return ProcessBuilder
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public static function create(array $arguments = array())
    {
        return new static($arguments);
    }

    /**
     * Adds an unescaped argument to the command string.
     *
     * @param string $argument A command argument
     *
     * @return ProcessBuilder
     */
    public function add($argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Adds an unescaped prefix to the command string.
     *
     * The prefix is preserved when resetting arguments.
     *
     * @param string|array $prefix A command prefix or an array of command prefixes
     *
     * @return ProcessBuilder
     */
    public function setPrefix($prefix)
    {
        $this->prefix = is_array($prefix) ? $prefix : array($prefix);

        return $this;
    }

    /**
<<<<<<< HEAD
     * Sets the arguments of the process.
     *
     * Arguments must not be escaped.
     * Previous arguments are removed.
     *
     * @param string[] $arguments
=======
     * @param array $arguments
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     *
     * @return ProcessBuilder
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

<<<<<<< HEAD
    /**
     * Sets the working directory.
     *
     * @param null|string $cwd The working directory
     *
     * @return ProcessBuilder
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function setWorkingDirectory($cwd)
    {
        $this->cwd = $cwd;

        return $this;
    }

<<<<<<< HEAD
    /**
     * Sets whether environment variables will be inherited or not.
     *
     * @param bool $inheritEnv
     *
     * @return ProcessBuilder
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function inheritEnvironmentVariables($inheritEnv = true)
    {
        $this->inheritEnv = $inheritEnv;

        return $this;
    }

<<<<<<< HEAD
    /**
     * Sets an environment variable
     *
     * Setting a variable overrides its previous value. Use `null` to unset a
     * defined environment variable.
     *
     * @param string      $name  The variable name
     * @param null|string $value The variable value
     *
     * @return ProcessBuilder
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function setEnv($name, $value)
    {
        $this->env[$name] = $value;

        return $this;
    }

    public function addEnvironmentVariables(array $variables)
    {
        $this->env = array_replace($this->env, $variables);

        return $this;
    }

<<<<<<< HEAD
    /**
     * Sets the input of the process.
     *
     * @param string $stdin The input as a string
     *
     * @return ProcessBuilder
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function setInput($stdin)
    {
        $this->stdin = $stdin;

        return $this;
    }

    /**
     * Sets the process timeout.
     *
     * To disable the timeout, set this value to null.
     *
     * @param float|null
     *
     * @return ProcessBuilder
     *
     * @throws InvalidArgumentException
     */
    public function setTimeout($timeout)
    {
        if (null === $timeout) {
            $this->timeout = null;

            return $this;
        }

        $timeout = (float) $timeout;

        if ($timeout < 0) {
            throw new InvalidArgumentException('The timeout value must be a valid positive integer or float number.');
        }

        $this->timeout = $timeout;

        return $this;
    }

<<<<<<< HEAD
    /**
     * Adds a proc_open option.
     *
     * @param string $name  The option name
     * @param string $value The option value
     *
     * @return ProcessBuilder
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

<<<<<<< HEAD
    /**
     * Creates a Process instance and returns it.
     *
     * @return Process
     *
     * @throws LogicException In case no arguments have been provided
     */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function getProcess()
    {
        if (0 === count($this->prefix) && 0 === count($this->arguments)) {
            throw new LogicException('You must add() command arguments before calling getProcess().');
        }

        $options = $this->options;

        $arguments = array_merge($this->prefix, $this->arguments);
        $script = implode(' ', array_map(array(__NAMESPACE__.'\\ProcessUtils', 'escapeArgument'), $arguments));

        if ($this->inheritEnv) {
            // include $_ENV for BC purposes
            $env = array_replace($_ENV, $_SERVER, $this->env);
        } else {
            $env = $this->env;
        }

        return new Process($script, $this->cwd, $env, $this->stdin, $this->timeout, $options);
    }
}
