<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2012 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared_Trend
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.7, 2012-05-19
 */


/**
 * PHPExcel_Best_Fit
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared_Trend
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Best_Fit
{
	/**
	 * Indicator flag for a calculation error
	 *
	 * @var	boolean
	 **/
	protected $_error				= False;

	/**
	 * Algorithm type to use for best-fit
	 *
	 * @var	string
	 **/
	protected $_bestFitType			= 'undetermined';

	/**
	 * Number of entries in the sets of x- and y-value arrays
	 *
	 * @var	int
	 **/
	protected $_valueCount			= 0;

	/**
	 * X-value dataseries of values
	 *
	 * @var	float[]
	 **/
	protected $_xValues				= array();

	/**
	 * Y-value dataseries of values
	 *
	 * @var	float[]
	 **/
	protected $_yValues				= array();

	/**
	 * Flag indicating whether values should be adjusted to Y=0
	 *
	 * @var	boolean
	 **/
	protected $_adjustToZero		= False;

	/**
	 * Y-value series of best-fit values
	 *
	 * @var	float[]
	 **/
	protected $_yBestFitValues		= array();

	protected $_goodnessOfFit 		= 1;

	protected $_stdevOfResiduals	= 0;

	protected $_covariance			= 0;

	protected $_correlation			= 0;

	protected $_SSRegression		= 0;

	protected $_SSResiduals			= 0;

	protected $_DFResiduals			= 0;

	protected $_F					= 0;

	protected $_slope				= 0;

	protected $_slopeSE				= 0;

	protected $_intersect			= 0;

	protected $_intersectSE			= 0;

	protected $_Xoffset				= 0;

	protected $_Yoffset				= 0;


	public function getError() {
		return $this->_error;
	}	//	function getBestFitType()


	public fun