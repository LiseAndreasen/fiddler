<?php

// constants

// maximum looking distance, 500 seems to be enough
$maxdistance = 500;

// number of interesting gap pairs
$gapPairs = 5;

// diffs cells
$angleCell = 0;
$maxGap = 1;
$minGap = 2;

// functions

// sorting criteria for gaps
function gapDiff($gaps1, $gaps2) {
	global $maxGap, $minGap;
	if($gaps1[$maxGap] <= $gaps2[$maxGap]
	&& $gaps1[$minGap] <= $gaps2[$minGap]) {
		return -1;
	}
	if($gaps1[$maxGap] >= $gaps2[$maxGap]
	&& $gaps1[$minGap] >= $gaps2[$minGap]) {
		return 1;
	}
	// i'm not quite sure about the rest of the cases
	return 0;
}

function printDiffs($diffs) {
	global $angleCell, $maxGap, $minGap, $gapPairs;
	$iMax = min($gapPairs, sizeof($diffs));
	for($i=0;$i<$iMax;$i++) {
		printf("Angle: %8.5f, max gap: %.5f, min gap: %.5f\n",
			$diffs[$i][$angleCell], $diffs[$i][$maxGap], $diffs[$i][$minGap]);
	}
}

/////////////

// look at all integer coordinate trees within distance
for($x=1;$x<=$maxdistance;$x++) {
	for($y=1;$y<=$maxdistance;$y++) {
		$distance = pow(($x*$x+$y*$y), 0.5);
		if($distance <= $maxdistance) {
			$angle = rad2deg(atan(($y / (float) $x)));
			if($angle < 45) {
				// spread the bins a bit by moving comma of key
				$angleBins[$angle*10000] = $angle;
			}
		}
	}
}

sort($angleBins);

// look at all pairs of gaps
for($i=1;$i<sizeof($angleBins)-1;$i++) {
	$preGap = $angleBins[$i] - $angleBins[$i-1];
	$postGap = $angleBins[$i+1] - $angleBins[$i];
	$diffs[] = array($angleBins[$i],
		max($preGap, $postGap), min($preGap, $postGap));
}

usort($diffs, "gapDiff");
$diffs = array_reverse($diffs);
printDiffs($diffs);

?>
