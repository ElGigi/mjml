<?php
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

    /**
     * Mjml constructor.
     *
     * @param string $command
     * @param string|null $directory
     */
    public function __construct(string $command, string $directory = null)
    {
        $this->command = $command;
        $this->directory = $directory;
    }

    /**
     * Create tmp file.
     *
     * @return string
     * @throws \ElGigi\MjMl\MjmlException
     */
    private function createTmpFile(): string
    {
        $tmpName = tempnam(sys_get_temp_dir(), 'mjml_');

        if ($tmpName === false) {
            throw new MjmlException(sprintf('Unable to create temporary file in tmp directory "%s"', sys_get_temp_dir()));
        }

        return $tmpName;
    }

    /**
     * Execute.
     *
     * @param string $input
     * @param string|null $error
     *
     * @return string
     * @throws \ElGigi\MjMl\MjmlException
     */
    private function execute(string $input = '', string &$error = null): string
    {
        file_put_contents($mjmlFile = $this->createTmpFile(), $input);

        $descriptorSpec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            sprintf('%s %s -s', escapeshellcmd($this->command), escapeshellarg($mjmlFile)),
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
                throw new MjmlException($error);
            }
        }

        return $output;
    }

    /**
     * MJML string to HTML.
     *
     * @param string $mjml
     *
     * @return string
     * @throws \ElGigi\MjMl\MjmlException
     */
    public function strToHtml(string $mjml): string
    {
        return $this->execute($mjml);
    }
}