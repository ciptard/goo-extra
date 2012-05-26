<?php

function avatar_profile($username)
{
	return '<img src="http://www.gravatar.com/avatar/' .md5($username). '.jpg?d=identicon&amp;f=y" alt="avatar" />';
}

?>