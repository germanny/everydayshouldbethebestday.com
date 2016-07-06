
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie10 lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Germanny Ltd. - Dashboard - JenGermann.com</title>

<!-- MISC -->
<!-- fav and touch icons -->
<link rel="shortcut icon" href="http://jengermann.com/favicon.ico">
<link rel="apple-touch-icon" href="http://jengermann.com/wp-content/themes/v8/assets/images/ico/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="http://jengermann.com/wp-content/themes/v8/assets/images/ico/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="http://jengermann.com/wp-content/themes/v8/assets/images/ico/apple-touch-icon-114x114.png">

<!-- Typkit Code -->
<script type="text/javascript" src="http://use.typekit.com/tqt8dfj.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<!-- JS -->


<!-- This site is optimized with the Yoast WordPress SEO plugin v1.4.18 - http://yoast.com/wordpress/seo/ -->
<meta name="description" content="Coding Test :)"/>
<link rel="canonical" href="http://jengermann.com/" />
<meta property="og:locale" content="en_US"/>
<meta property="og:type" content="article"/>
<meta property="og:title" content="Home - JenGermann.com"/>
<meta property="og:description" content="Coding Test :)"/>
<meta property="og:url" content="http://jengermann.com/"/>
<meta property="og:site_name" content="JenGermann.com"/>
<meta property="article:publisher" content="https://www.facebook.com/germanny"/>
<!-- / Yoast WordPress SEO plugin. -->

<link rel='stylesheet' id='base-styles-css'  href='assets/style.css' type='text/css' media='all' />

</head>

<body id="page-home" class="home page page-id-6 page-template-default">

<p id="accessibility" class="screen-reader-text">Skip to: <a href="#menu">Navigation</a> | <a href="#content">Content</a> <a href="#siteinfo">Footer</a></p>

<a name="top" id="top"></a>
<div id="page">

  <header id="branding">
    <div class="container">
      <div id="logo"><a href="#home">JenGermann Design</a></div>
    </div>
  </header>

  <section id="content" class="module">

    <?php include 'functions/fn-dashboard.php'; ?>

    <h1>Germanny Ltd.</h1>
    <h2>Sales Staff and Products Dashboard</h2>

    <article>

    <?php
      $staff          = file_post_contents_curl( 'https://gist.githubusercontent.com/weeirishman/44334494953b2499a104efac5776b399/raw/065c96cbabb86b9a06b035d76a469d4210e8eab5/staff.json' );
      $product_sales  = file_post_contents_curl( 'https://gist.githubusercontent.com/weeirishman/40a41f2fd4b9b56804b49bd2dcae995f/raw/cd54cfa7636fa083ce0c23d9e9ac51ec2ae856d9/product.json' );
      $product_avail  = array_map( 'str_getcsv', file( 'https://gist.githubusercontent.com/weeirishman/5d99b7a96ad7106948e253cb61d921b6/raw/31743ec6c51d021bf4c16aaa179c2c9a372cdd56/availability.csv' ) );

      $this_monday  = strtotime( 'Monday this week 12:59am' );
      $last_monday  = strtotime( 'last Monday 1am', $this_monday );
      $next_monday  = strtotime( 'next Monday 12:59am', $this_monday );

      $last_week    = date( 'D, M j, Y h:i A', $last_monday ) . ' to ' . date( 'D, M j, Y h:i A', $this_monday );
      $this_week    = date( 'D, M j, Y h:i A', $this_monday ) . ' to ' . date( 'D, M j, Y h:i A', $next_monday );

      if ( $staff['success'] )
      {
        $staff_feed   = json_decode( $staff['data'] );
        asort( $staff_feed->staff );

        setlocale( LC_MONETARY, 'en_US' );

        echo '<table>';
        echo '<caption>Sales Staff</caption>';
        echo '<thead>';
          echo '<tr>';
            echo '<th>Name</th>';
            echo '<th>total sales</th>';
            echo '<th>active selling hours</th>';
            echo '<th>effective hourly sales rate</th>';
          echo '<tr>';
        echo '</thead>';
        echo '<tfoot>';
          echo '<tr>';
            echo '<td colspan="4">' . $last_week . '</td>';
          echo '<tr>';
        echo '</tfoot>';

        foreach( $staff_feed->staff as $v )
        {
          $hours = $v->hours;
          $sales = $v->sales;
          echo '<tr>';
          echo '<th>' . $v->name . '</th>';
          echo '<td>' . number_format( $sales ) . '</td>';
          echo '<td>' . $hours . '</td>';
          echo '<td>' . money_format( '%n', $sales / $hours ) . '</td>';
          echo '</tr>';
        }

        echo '</table>';
      }

      if ( $product_sales['success'] )
      {
        $i                    = 0;
        $product_sales_feed   = json_decode( $product_sales['data'] );
        $products_sold        = $product_sales_feed->products_sold;

        foreach ( $products_sold as $key => $row )
        {
          $units_sold[$key] = $row->units_sold;
        }

        array_multisort( $units_sold, SORT_DESC, $products_sold );

        echo '<table>';
        echo '<caption>Top Product Sales</caption>';
        echo '<thead>';
          echo '<tr>';
            echo '<th>&nbsp;</th>';
            echo '<th>ID</th>';
            echo '<th>Name</th>';
            echo '<th>Units Sold</th>';
          echo '<tr>';
        echo '</thead>';
        echo '<tfoot>';
          echo '<tr>';
            echo '<td colspan="4">' . $last_week . '</td>';
          echo '<tr>';
        echo '</tfoot>';

        foreach( $products_sold as $p )
        {
          if ( $i++ < 5 )
          {
            echo '<tr>';
            echo '<th>' . $i . '</th>';
            echo '<th>' . $p->id . '</th>';
            echo '<td>' . $p->name . '</td>';
            echo '<td>' . $p->units_sold . '</td>';
            echo '</tr>';
          }
        }

        echo '</table>';
      }


      if ( $product_avail )
      {
        $i = 0;

        foreach ( $product_avail as $key => $row )
        {
          $units[$key] = $row[2];
        }

        array_multisort( $units, SORT_DESC, $product_avail );

        echo '<table>';
        echo '<caption>5 products with the most unit availablity for sale during ' . $this_week . '</caption>';
        echo '<thead>';
          echo '<tr>';
            echo '<th>&nbsp;</th>';
            echo '<th>ID</th>';
            echo '<th>Name</th>';
            echo '<th>Units Available</th>';
          echo '<tr>';
        echo '</thead>';
        echo '<tfoot>';
          echo '<tr>';
            echo '<td colspan="4">' . $this_week . '</td>';
          echo '<tr>';
        echo '</tfoot>';

        foreach( $product_avail as $p )
        {
          $i++;
          if ( $i > 1 && $i < 7 )
          {
            echo '<tr>';
            echo '<th>' . $i . '</th>';
            echo '<th>' . $p[0] . '</th>';
            echo '<td>' . $p[1] . '</td>';
            echo '<td>' . $p[2] . '</td>';
            echo '</tr>';
          }
        }

        echo '</table>';
      }

    ?>
    </article>
  </section>

<!-- Footer -->
  <footer id="siteinfo" role="contentinfo">
    <div class="copy">&#169;2016 <a class="url fn" href="http://jengermann.com/">JenGermann.com</a></div>
    <div class="quote">Elegant solutions. Rewarding experiences.</div>
  </footer>
</div>

</body>
</html>

