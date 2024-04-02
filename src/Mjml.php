<?php
/**
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2018 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace ElGigi\Mjml;

/**
 * Class Mjml.
 *
 * @package ElGigi\MjMl
 */
class Mjml
{
    /** @var string Command */
    private $command;
    /** @var string Directory */
    private $directory;
    /** @var bool Minify */
    private $minify;
    /** @var array Temporary files */
    private $tempFiles = [];

    /**
     * Mjml constructor.
     *
     * @param string $command
     * @param string|null $directory
     *
     * @throws MjmlException
     */
    public function __construct(string $command, string $directory = null)
    {
        $this->command = $command;
        $this->directory = $directory;
        $this->minify = true;

        if (!function_exists('proc_open') || !function_exists('proc_close')) {
            throw new MjmlException('Library MJML needs "proc_open" and "proc_close" functions');
        }
    }

    public function __destruct()
    {
        array_walk(
            $this->tempFiles,
            function ($tempName) {
                @unlink($tempName);
            }
        );
    }

    /**
     * Create tmp file.
     *
     * @return string
     * @throws MjmlException
     */
    private function createTmpFile(): string
    {
        $tmpName = tempnam(sys_get_temp_dir(), 'mjml_');

        if ($tmpName === false) {
            throw new MjmlException(sprintf('Unable to create temporary file in tmp directory "%s"',
                sys_get_temp_dir()));
        }

        $this->tempFiles[] = $tmpName;

        return $tmpName;
    }

    /**
     * Minify.
     *
     * @param bool $minify
     *
     * @return Mjml
     */
    public function minify(bool $minify = true): Mjml
    {
        $this->minify = $minify;

        return $this;
    }

    /**
     * Get options.
     *
     * @return string
     */
    private function getStrOptions(): string
    {
        $options = [];

        if ($this->minify) {
            $options[] = '--config.minify';
        }

        return implode(' ', $options);
    }

    /**
     * Execute.
     *
     * @param string $input
     * @param string|null $error
     *
     * @return string
     * @throws MjmlException
     */
    private function execute(string $input = '', string &$error = null): string
    {
        file_put_contents($mjmlFile = $this->createTmpFile(), $input);

        $descriptorSpec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            sprintf('%s %s -s %s', escapeshellcmd($this->command), escapeshellarg($mjmlFile), $this->getStrOptions()),
            $descriptorSpec,
            $pipes,
            $this->directory
        );

        if (!is_resource($process)) {
            throw new MjmlException(sprintf('Unable to execute command "%s"', $this->command));
        }

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        if (($status = proc_close($process)) !== 0) {
            if ($error) {
                throw new MjmlException($error, $status);
            }
        }

        // Remove html commented line
        $output = trim(preg_replace('#<!--\s+[^>]+-->#i', '', $output));

        return $output;
    }

    /**
     * MJML string to HTML.
     *
     * @param string $mjml
     *
     * @return string
     * @throws MjmlException
     */
    public function strToHtml(string $mjml): string
    {
        return $this->execute($mjml);
    }
}
