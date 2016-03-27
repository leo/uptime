<?php

if(doAutoLogin($con_pdo)){
	echo "true";
}else{
	echo "false";
}