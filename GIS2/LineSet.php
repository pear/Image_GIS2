<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: Image :: GIS :: Line Set                                       |
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

/**
 * A Set of Lines.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2004 Jan Kneschke <jan@kneschke.de> and Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Image
 * @package     Image_GIS2
 */
class Image_GIS2_LineSet {
    /**
    * @var array $color
    */
    public $color = 'black';

    /**
    * @var array $lines
    */
    public $lines = array();

    /**
    * @var array $min
    */
    public $min = false;

    /**
    * @var array $max
    */
    public $max = false;

    /**
    * Constructor.
    *
    * @param  string $color
    * @access public
    */
    public function Image_GIS2_LineSet($color = 'black') {
        $this->color = $color;
    }

    /**
    * Adds a line to the line set.
    *
    * @param  float $x1
    * @param  float $y1
    * @param  float $x2
    * @param  float $y2
    * @access public
    */
    public function addLine($x1, $y1, $x2, $y2) {
        $this->lines[] = array($x1, $y1, $x2, $y2);

        if ($this->min == false) {
            $this->min['x'] = min($x1, $x2);
            $this->min['y'] = min($y1, $y2);
            $this->max['x'] = max($x1, $x2);
            $this->max['y'] = max($y1, $y2);
        } else {
            $this->min['x'] = min($this->min['x'], $x1, $x2);
            $this->min['y'] = min($this->min['y'], $y1, $y2);
            $this->max['x'] = max($this->max['x'], $x1, $x2);
            $this->max['y'] = max($this->max['y'], $y1, $y2);
        }
    }
}
?>
