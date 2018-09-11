<?php

function days($d)
{
	$y = $d % 10;
	$x = $d / 10 % 10;
	if ($x && $x == 1) return '����';
	if ($y == 1) return '����';
	if (in_array($y, array('2', '3', '4'))) return '���';
	return '����';
}

function buildTree(array $elements, $parentId = 0)
{
	$branch = array();

	foreach ($elements as $element) {
		if ($element['rp_pid'] == $parentId) {
			$children = buildTree($elements, $element['rp_id']);
				
			if ($children) {
				$element['children'] = $children;
			}
				
			$branch[$element['rp_id']] = $element;
		}
	}

	return $branch;
}
