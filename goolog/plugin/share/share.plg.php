<?php

function share_afterMain()
{
	return <<<EOT
<div style="position: fixed; left: 0; top: 20%;">
	<span  class='st_facebook_large' ></span><br /><span  class='st_reddit_large' ></span><br /><span  class='st_twitter_large' ></span><br />
	<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'05443c2b-5041-42b1-9816-ae574ce66e21'});</script>
</div>
EOT;
}

?>