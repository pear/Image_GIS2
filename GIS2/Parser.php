<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: Image :: GIS :: Parser Base Class                              |
// +------------------------------------------------------------------------+
// | Copyright (c) 2002-2004 Jan Kneschke <jan@kneschke.de> and             |
// |                         Sebastian Bergmann <sb@sebastian-bergmann.de>. |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
//
// $Id$
//

require_once 'Cache/Lite.php';
require_once 'Image/GIS2/LineSet.php';

/**
 * Parser Base Class.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2004 Jan Kneschke <jan@kneschke.de> and Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Image
 * @package     Image_GIS2
 */
abstract class Image_GIS2_Parser {
    /**
    * Cache.
    *
    * @var Cache_Lite $cache
    */
    protected $cache = NULL;

    /**
    * Data Files.
    *
    * @var array $dataFiles
    */
    protected $dataFiles = array();

    /**
    * Set to TRUE to enable debugging.
    *
    * @var boolean $debug
    */
    protected $debug;

    /**
    * Line Set.
    *
    * @var array $lineSets
    */
    protected $lineSets = array();

    /**
    * Constructor.
    *
    * @param  boolean $cache
    * @param  boolean $debug
    * @access public
    */
    public function Image_GIS2_Parser($cache, $debug) {
        if ($cache) {
            $this->cache = new Cache_Lite;
        }

        $this->debug = $debug;
    }

    /**
    * Factory.
    *
    * @param  string  $parser
    * @param  boolean $cache
    * @param  boolean $debug
    * @return object
    * @access public
    */
    public static function factory($parser, $cache, $debug) {
        include_once 'Image/GIS2/Parser/' . $parser . '.php';

        $class  = 'Image_GIS2_Parser_' . $parser;
        $object = new $class($cache, $debug);

        return $object;
    }

    /**
    * Adds a datafile to the map.
    *
    * @param  string  $dataFile
    * @param  mixed   $color
    * @access public
    */
    public function addDataFile($dataFile, $color) {
        $this->dataFiles[$dataFile] = $color;
    }

    /**
    * Parses the data files of the map.
    *
    * @access public
    * @return array
    */
    public function parse() {
        foreach ($this->dataFiles as $dataFile => $color) {
            $cacheID = md5($dataFile . '_' . $color);
            $lineSet = false;

            if (is_object($this->cache) &&
                $lineSet = $this->cache->get($cacheID, 'Image_GIS')) {
                $lineSet = unserialize($lineSet);
            }

            if ($lineSet === false) {
                $lineSet = $this->parseFile($dataFile, $color);

                if (is_object($this->cache)) {
                    $this->cache->save(serialize($lineSet), $cacheID, 'Image_GIS');
                }
            }

            $this->lineSets[] = $lineSet;
        }

        return $this->lineSets;
    }

    /**
    * Parses a data file.
    *
    * @param  string  $dataFile
    * @param  mixed   $color
    * @return mixed
    * @access public
    * @abstract
    */
    public abstract function parseFile($dataFile, $color);
}
?>
