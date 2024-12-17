<?php 
	session_start();
	// unset($_SESSION['root']);
	error_reporting(0);
	class node{
		public $data = 10;
		public $left ;
		public $right ;
		function createNode($data){
			$this->data = $data;
			$this->left = null;
			$this->right = null;
		}
	}
	if(isset($_POST['insertData'])){
		$data = $_POST['data'];
		for ($i=1; $i < $data; $i++) { 
			$node = new node();
			$node->createNode($i);
			if (isset($_SESSION['root'])) {
				$root = $_SESSION['root'];
				insertIntoTree($root, $node);
			}else{
				$_SESSION['root'] = $node;
				$root = $_SESSION['root'];
			}
			AVL_Tree($_SESSION['root'],$_SESSION['root']);
		}
	}
	// isComplite($_SESSION['root']);
	// while(isComplite($_SESSION['root']) < 0){
		// echo 'entered';
		AVL_Tree($_SESSION['root'],$_SESSION['root']);
	// }
	if(isset($_POST['resetData'])){
		$_SESSION['root'] = null;
	}
	function insertIntoTree($root, $node){
		if ($root->data > $node->data) {
			if($root->left != null){
				insertIntoTree($root->left, $node);
			}else{
				$root->left = $node;
			}
		}else{
			if ($root->right != null) {
				insertIntoTree($root->right, $node);
			}else{
				$root->right = $node;
			}
		}
	}
//*************************************************************
	function isComplite($root){
		if($root->left == null && $root->right == null){
			return 1;
		}else{
			if($root->left != null){
				$leftHeight = AVL_Tree($root->left, $root);
			}else{
				$leftHeight = 0;
			}
			if($root->right != null){
				$rightHeight = AVL_Tree($root->right, $root);
			}else{
				$rightHeight = 0;
			}
			$balenceFactor = $leftHeight - $rightHeight;
			if (-2 < $balenceFactor && $balenceFactor < 2) {
				if($leftHeight > $rightHeight){
					return $leftHeight + 1;
				}else{
					return $rightHeight + 1;
				}
			}else{			
				echo '* balenceFactor of '.$root.' is '.$balenceFactor.' *';
				AVL_Tree($_SESSION['root'],$_SESSION['root']);
				isComplite($_SESSION['root']);
			}
		}
	}
//**************************************************************
	function AVL_Tree($root, $previous){
		if($root->left == null && $root->right == null){
			return 1;
		}else{
			if($root->left != null){
				$leftHeight = AVL_Tree($root->left, $root);
			}else{
				$leftHeight = 0;
			}
			if($root->right != null){
				$rightHeight = AVL_Tree($root->right, $root);
			}else{
				$rightHeight = 0;
			}
			$balenceFactor = $leftHeight - $rightHeight;
			if (-2 < $balenceFactor && $balenceFactor < 2) {
				if($leftHeight > $rightHeight){
					return $leftHeight + 1;
				}else{
					return $rightHeight + 1;
				}
			}else{
				if($balenceFactor > 1){
					$newRoot = rearangeTree($root->left, $balenceFactor);
					$root->left = null;
					$newRoot->right = $root;
				}else{
					$newRoot = rearangeTree($root->right, $balenceFactor);
					$root->right = null;
					$newRoot->left = $root;
				}
				if($root != $previous){
					if ($previous->left == $root) {
						$previous->left = $newRoot;
					}else{
						$previous->right = $newRoot;
					}
				}else{
					$_SESSION['root'] = $newRoot;
					AVL_Tree($_SESSION['root'],$_SESSION['root']);
				}
				if($leftHeight > $rightHeight){
					return $leftHeight;
				}else{
					return $rightHeight;
				}
			}
		}
	}
//**************************************************************
	function rearangeTree($root, $balenceFactor){
		$newRoot = $root;
		if ($balenceFactor > 1) {
			// echo 'previous = '.data.'root = '.$root->data.'balenceFactor'.$balenceFactor;
			if($root->right != null){
				$newRoot = rearangeTree($root->right, $balenceFactor);
				$root->right = $newRoot->left;
				$newRoot->left = $root;
			}
		}else{
			// echo 'previous = '.data.'root = '.$root->data.'balenceFactor'.$balenceFactor;
			if ($root->left != null) {
				$newRoot = rearangeTree($root->left, $balenceFactor);
				$root->left = $newRoot->right;
				$newRoot->right = $root;
			}
		}
		return $newRoot;
	}
//***********	Graphical Reparsntation	of AVL Tree		************************************
	for ($i=0; $i < 7; $i++) { 
		$t = 1;
		for ($k=0; $k < $i; $k++) { 
			$t = $t*2;
		}
		for ($j=0; $j < $t; $j++) {
			$_SESSION['newRoot'][$i][$j] = '';
		}
	}
	function travers($root,$lavel,$i){
		// echo $root->data.'--->';
		$_SESSION['newRoot'][$lavel][$i] = $root->data;
		$i = ($i * 2);
		if(isset($root->left)){
			travers($root->left,$lavel+1,$i);
		}
		if(isset($root->right)){
			travers($root->right, $lavel+1, $i+1);
		}
	}
	travers($_SESSION['root'],0,0);
	$newRoot = $_SESSION['newRoot'];
	$_SESSION['newRoot'] = null;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AVL Tree</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<center>
		<form method="post">
			<input type="text" name="data" />
			<input type="submit" name="insertData" value="insertData" />
			<input type="submit" name="resetData" value="resetData" />
		</form>
		<div class="tree">
			<?php 
				for ($i=0; $i < count($newRoot); $i++) { 
					echo '<div id="lavel" class="row">';
					$t = count($newRoot[$i]);
					// echo $t;
					for ($j=0; $j < $t; $j++) { 
						echo '<div class="col">'.$newRoot[$i][$j].'</div>';
					}
					echo '</div>';
				}
			?>
			</div>
		</div>
	</center>
</body>
</html>