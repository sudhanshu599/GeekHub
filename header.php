<link href="http://localhost/GeekHub/assets/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://bootswatch.com/4/solar/bootstrap.min.css">
</head>
<body>
<ul class="ulclass">
<li><a href="http://localhost/GeekHub/">Home</a></li>
<?php
    $baseUrl="http://localhost/GeekHub/page/"; 
            try {

                $stmt = $db->query('SELECT page_title,page_slug FROM pages ORDER BY page_id ASC');
                while($rowlink = $stmt->fetch()){
                    
                    echo '<li><a href="'.$baseUrl.''.$rowlink['page_slug'].'">'.$rowlink['page_title'].'</a></li>';
                }

            } catch(PDOException $e) {
                echo $e->getMessage();
            }
    ?>
</ul>
<script id="dsq-count-scr" src="//localhost-yervjmnvbu.disqus.com/count.js" async></script>