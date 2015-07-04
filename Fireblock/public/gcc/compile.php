<?php
session_start(); 
$cc = "avr-gcc -g";
$cc2 = "avr-objcopy";
$file = $_SESSION['file'];
$fileinput = "loadCfile.c";
$fileinput2 = "loadCfile.o";
$fileinput3 = "loadCfile.elf";
$fileoutput = "loadCfile.hex";
$command1 = $cc." -mmcu=atmega2560 -c ".$fileinput." 2>&1";
$command2 = $cc." -mmcu=atmega2560 -o ".$fileinput3." ".$fileinput2." 2>&1";
$command3 = $cc2." -j .text -j .data -O ihex ".$fileinput3." ".$fileoutput." 2>&1";

$output;
$output2;
$output3;

$files = fopen($fileinput,"w");
fwrite($files,$file);
fclose($files);

exec($command1,$output,$return);
if(!$return){
	exec($command2,$output2,$return2);
	if(!$return2){
		exec($command3,$output3,$return3);
		if($return3){
			print_r($output3);
		}
		else{
			echo "<script>alert('lol');</script>";
			$_SESSION['filename'] = $fileoutput;
			header('Location:../gcc/download.php'); 
		}
	}else{
		print_r($output2);
		
	}
}else{
	echo "<pre>".print_r($output)."</pre>";

}
