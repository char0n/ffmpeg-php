<?php
/**
 * OutputProvider interface of all output providers
 *
 * @author char0n (Vladimír Gorej, vladimir.gorej@gmail.com)
 * @package FFmpegPHP
 * @subpackage provider
 * @license New BSD
 * @version 2.6
 */

namespace Char0n\FFMpegPHP\OutputProviders;

interface OutputProviderInterface
{

    /**
     * Setting movie file path
     *
     * @param string $movieFile
     */
    public function setMovieFile($movieFile);
    
    /**
     * Getting parsable output
     *
     * @return string
     */
    public function getOutput();
}
