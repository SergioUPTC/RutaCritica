<?php

function calculate_route_node($node, $grafo){
	$result = array(
		'route'=>'',
		'acomulado'=>0
	);

	foreach ($node->associate as $key => $associate) {
		$nodeChild = $grafo[$associate];
		$calculateRoute = calculate_route_node($nodeChild, $grafo);

		if($result['route'] == '' && $result['acomulado'] == 0)
			$result = $calculateRoute;
		else if($calculateRoute['acomulado'] > $result['acomulado'])
			$result = $calculateRoute;
	}

	$result['route'] .= '-'.$node->idActivity;
	$result['acomulado'] += $node->duration;

	return $result;
}

/***************************************************************/

$return = array(
	'route'=>'',
	'acomulado'=>0
);

$grafo = json_decode($_POST['grafo']);
$starts = json_decode($_POST['starts']);
$ends = json_decode($_POST['ends']);

$grafoArray = [];
foreach ($grafo as $key => $item) {
	$grafoArray[$key] = $item;
}

foreach ($ends as $i => $end) {
	$calculateRoute = calculate_route_node($end, $grafoArray);

	if($return['route'] == '' && $return['acomulado'] == 0)
		$return = $calculateRoute;
	else if($calculateRoute['acomulado'] > $return['acomulado'])
		$return = $calculateRoute;
}

$routeArray = explode('-', $return['route']);
$return['route'] = '';
foreach ($routeArray as $key => $item) {
	if($key > 0){
		if($key != 1)
            $return['route'] .= ' - ';
        $return['route'] .= $grafoArray[$item]->name;
	}
}

echo json_encode($return);