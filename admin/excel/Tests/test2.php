<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2010 PHPExcel
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
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel_IOFactory */
require_once '../Classes/PHPExcel/IOFactory.php';


#echo date('H:i:s') . " Load from Excel2007 file\n";
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
#$objPHPExcel = $objReader->load("Membership.xls");
$objPHPExcel = PHPExcel_IOFactory::load("Membership.xls");

$membershipAry = array();

$headerRow = array(
	'A'	=>	'Name',
	'B'	=>	'CAPID',
	'C'	=>	'Grade',
	'D'	=>	'Grade Date',
	'E'	=>	'Gender',
	'F'	=>	'Join Date',
	'G'	=>	'Expiration',
	'H'	=>	'Home Phone',
	'I'	=>	'Cell Phone',
	'J'	=>	'Street Address',
	'K'	=>	'Email Tag',
	'L'	=>	'Email',
	'M'	=>	'City',
	'N'	=>	'FBI Status',
	'O'	=>	'DOB tag',
	'P'	=>	'Date of Birth',
	'Q'	=>	'Under 18'
);


#echo date('H:i:s') . " Iterate worksheets\n";
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
#	echo '- ' . $worksheet->getTitle() . "\r\n";

	foreach ($worksheet->getRowIterator() as $row) {
	#	echo '<hr>    - Row number: ' . $row->getRowIndex() . "<br>\r\n";
		$dataRow=array();

		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false); // Loop only non-null cells

		foreach ($cellIterator as $cell) {

			$coordinate=trim($cell->getCoordinate());
			$rowCoord = ltrim($coordinate, "\x41..\x7A") + 0;   # Remove column "letter" index, A-Z,a-z
			$colCoord = rtrim($coordinate, "\x30..\x39");       # Remove numeric row index, 0-9 	
#echo "<pre> ==== $num";
#print_r(get_class_methods($cell));
#echo strlen(serialize($cell));
#echo "</pre>"; 


			if ((!is_null($cell)) AND (strlen($cell->getCalculatedValue()) > 0) ) {
				$cellValue = trim($cell->getCalculatedValue());
				$cv = trim($cell->getValue());
		#		echo  '        - Cell: ' . $rowCoord . ":" . $colCoord . ' - ' . $cellValue .  "<br>\r\n";

				if ($rowCoord==1) {
				$headerRow[$colCoord] = $cellValue;
				} else {
				$dataRow[$headerRow[$colCoord]] = $cellValue;	

				}





			}
		}  # End of cellIterator loop

		if (count($dataRow) > 3) {
		$membershipAry[$rowCoord] = $dataRow;
		}
	}
}


echo "<pre>";
#print_r($headerRow);
print_r($membershipAry);

echo "</pre>";

// Echo memory peak usage
#echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";

// Echo done
#echo date('H:i:s') . " Done writing files.\r\n";

?>
