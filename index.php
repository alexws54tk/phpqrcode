<?php
/*
 * PHP QR Code encoder
 *
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

	echo "<h1>PHP QR Code</h1><hr/>";

	//set it to writable location, a place for temp generated PNG files
	$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

	//html PNG location prefix
	$PNG_WEB_DIR = 'temp/';

	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."qrlib.php");

	//ofcourse we need rights to create temp dir
	if (!file_exists($PNG_TEMP_DIR))
		mkdir($PNG_TEMP_DIR);


	$filename = $PNG_TEMP_DIR.'test.png';

	//processing form input
	//remember to sanitize user input in real-life solution !!!
	$errorCorrectionLevel = ((isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))?$_REQUEST['level']:'L');

	$matrixPointSize = ((isset($_REQUEST['size']))?min(max((int)$_REQUEST['size'], 1), 10):4);


	$data = "PHP QR Code :)";
	$data = ((isset($_REQUEST['data'])&&trim(htmlspecialchars($_REQUEST['data'])) != '')?trim(htmlspecialchars($_REQUEST['data'])):'PHP QR Code :)');
	QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize);

	//display generated file
	echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" alt="PNG1 QR Code image" />';
	echo '<img src="imgpng.php?data='.$data.'&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="PNG2 QR Code image" />';
	echo '<img src="imgsvg.php?data='.$data.'&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="SVG QR Code image" /><hr/>';

	//config form
	echo '<form action="index.php" method="post">
		Data:&nbsp;<input type="text" name="data" value="'.$data.'" placeholder="PHP QR Code :)">&nbsp;
		ECC:&nbsp;<select name="level">
			<option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option>
			<option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option>
			<option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option>
			<option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option>
		</select>&nbsp;
		Size:&nbsp;<select name="size">';

	for($i=1;$i<=10;$i++)
		echo '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';

	echo '</select>&nbsp;
		<input type="submit" value="GENERATE"></form><hr/>';
	echo '<h2>Examples of Data formats</h2>'."\n";
	echo '<table>
	<tr><th>Type of QR Code</th><th>Sample text</th><th>Sample image</th></tr>
	<tr><th>Plain text:</th><td> PHP QR Code :) </td><td><img src="imgpng.php?data=PHP QR Code :)&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="PNG QR Code sample text" /></td></tr>
	<tr><th>URL:</th><td> http://www.nspu.ru/ </td><td><img src="imgpng.php?data=http://www.nspu.ru/&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="PNG QR Code sample url" /></td></tr>
	<tr><th>SMS:</th><td> smsto:79998765432:Text of your message; </td><td><img src="imgpng.php?data=smsto:79232271844:Hello&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="PNG QR Code sample sms" /></td></tr>
	<tr><th>Geolocation:</th><td> geo:54.9812,82.8124,400 </td><td><img src="imgpng.php?data=geo:54.9812,82.8124,400&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="PNG QR Code sample geo" /></td></tr>
	<tr><th>Wi-Fi:</th><td> wifi:s:Network_Name;t:wpa;p:Password;;</td><td><img src="imgpng.php?data=wifi:s:ST_NSPU;t:wpa;p:VmfK53pSit;;&amp;level='.$errorCorrectionLevel.'&amp;size='.$matrixPointSize.'" alt="PNG QR Code sample wi-fi" /></td></tr>
	</table>
	<hr />';

	// benchmark
	QRtools::timeBenchmark();
	// QRtools::buildCache();
?>
