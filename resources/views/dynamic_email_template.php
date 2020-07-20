<p>Hi <?php echo $data['name']; ?>, please click link below to verify your registration.</p>

<a rel="nofollow" target="_blank" href=<?php echo $data['token']; ?>><?php echo $data['token']; ?></a>
<p>Thank you.</p>