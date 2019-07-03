<?php
/**
 * Plugin Name: WooCommerce Fevertree Instalment Calculator
 * 
 * Description: 
 * Author: FeverTree Developer

 */
 if(isset($_POST['examplePHP'])){ //check if $_POST['examplePHP'] exists
        BalanceCheckFunc(); // echo the data
        die(); // stop execution of the script.
    }
$path  = "\json.cacheAllProducts.txt";
$dh = (__DIR__ .$path);
$now   = time();
if(time() - filectime($dh) > 60 * 60 * 24 * 7 ){
    unlink($dh);
    $myfile = fopen("json.cacheAllProducts.txt", "w");
    }

else if (trim(file_get_contents($dh)) == false){

    add_action( 'wp', 'getValue' );}

add_action( 'woocommerce_before_add_to_cart_quantity', 'func_option_valgt' );
function func_option_valgt() {


	
    global $product;

    if ( $product->is_type('variable') ) {
        $variations_data =[]; // Initializing

        // Loop through variations data
        foreach($product->get_available_variations() as $variation ) {
            // Set for each variation ID the corresponding price in the data array (to be used in jQuery)
            $variations_data[$variation['variation_id']] = $variation['display_price'];
        }
        ?>
        <script>
          jQuery(function($) {
            var jsonData = <?php echo json_encode($variations_data); ?>,
                inputVID = 'input.variation_id';

            $('input').change( function(){
                if( '' != $(inputVID).val() ) {
                    var vid      = $(inputVID).val(), // VARIATION ID
                        length   = $('#cfwc-title-field').val(), // LENGTH
                        diameter = $('#diameter').val(),  // DIAMETER
                        vprice   = ''; // Initilizing

                    // Loop through variation IDs / Prices pairs
                    $.each( jsonData, function( index, price ) {
                        if( index == $(inputVID).val() ) {
                            vprice = price; // The right variation price
                        }
                    });

          
	    var xhr = new XMLHttpRequest();
	    xhr.withCredentials = false;
	    xhr.crossOrigin = true;
	    xhr.addEventListener("readystatechange", function () {
      if (this.readyState === 4) {
       // debugger;
        console.log(this.responseText);
     	//alert(this.responseText);
     	var price = this.responseText;

     	var urlFile = 'https://cors-anywhere.herokuapp.com' +'<?php echo plugin_dir_path(__FILE__). 'calculator.php'; ?> '
     	//console.log(urlFile);
     	$.ajax({
                url: window.location, //window.location points to the current url. change is needed.
                type: 'POST',
                data: {
                  examplePHP: price
                },
                success: function( response){
                  console.log("Successful! My post data is: "+response);
                  document.getElementById('instalmentCalcOption').style.display = "none";
                  document.getElementById("instalmentCalc").innerHTML = 'Mobelli Account: R' + price +' /month x 24';
                },
                error: function(error){
                  console.log("error");
                }
          });

     	 }

   		 });

   
	    xhr.open("POST","https://www.ftapp.co.za/i/38fe2149-2afa-4111-bbc2-8a3612afd9e3/" +vprice);
	    xhr.setRequestHeader("Content-Type", "application/json");
	    xhr.setRequestHeader("Cache-Control", "no-cache");
	    xhr.setRequestHeader("dataType", "jsonp");
   		xhr.send();
 
                }

            });
        });
        </script>
        <?php
    }
else{
?>


	  <script>
          jQuery(function($) {
          	document.getElementById('instalmentCalcOption').style.display = "none";
          	document.getElementById("instalmentCalc").innerHTML = 'Mobelli Account: R' + <?php echo returnSinglePrice() ?> +' /month x 24';


 });

        </script>

        <?php
        

}

   
}

function returnSinglePrice()
{
	$sale_price = get_post_meta( get_the_ID(), '_price', true);

				$finalTotal = get_post_meta( get_the_ID(), '_regular_price', true);
				$url 			= "https://www.ftapp.co.za/i/38fe2149-2afa-4111-bbc2-8a3612afd9e3/$finalTotal"; // json source
				$cache 			= __DIR__."/json.cache.txt"; // make this file in same dir
		
				$handle = fopen($cache, 'wb') or die('no fopen');	
			// read json source
				$ch = curl_init($url) or die("curl issue");
				$curl_options = array(
					CURLOPT_RETURNTRANSFER	=> true,
					CURLOPT_HEADER 		=> false,
					CURLOPT_FOLLOWLOCATION	=> false,
					CURLOPT_ENCODING	=> "",
					CURLOPT_AUTOREFERER 	=> true,
					CURLOPT_CONNECTTIMEOUT 	=> 7,
					CURLOPT_TIMEOUT 	=> 7,
					CURLOPT_MAXREDIRS 	=> 3,
					CURLOPT_SSL_VERIFYHOST	=> false,
					CURLOPT_USERAGENT	=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
				);
				curl_setopt_array($ch, $curl_options);
				$curlcontent = curl_exec( $ch );
				curl_close( $ch );
				
						
						//$json_cache = $curlcontent;
						//fwrite($handle, $json_cache);
						//fclose($handle);
				
				//echo "<pre>".print_r("API",1)."</pre>";
						 $produtslist[] = array(

								'ID'=> get_the_ID(),
								'price'=>$curlcontent
						);

						   $items = json_encode($produtslist);
		       

						fwrite($handle,print_r(($items), TRUE));
		 				fclose($handle); 

		 				echo $curlcontent;

}
add_action( 'woocommerce_single_product_summary', function($price) {                              
 ?>
 <style type="text/css">
a:hover, a:visited, a:link, a:active
{
text-decoration: none;
}
 </style>

 <div nowrap id="instalmentCalcOption" data-fontsize="15" data-fontweight="normal" style="margin: 5px; padding: 0px; display: inline-block; color: rgb(102, 102, 102); background: transparent; font-family: inherit; font-size: 14px; font-weight: normal;">Choose an option to see instalment /24 Months</div>
 <a target="_blank" href="http://mobelli.co.za/" title="Apply for Mobelli Account" alt="Sign up with Mobelli" style="text-decoration:none;"> 
    <div id="instalmentCalc" data-fontsize="15" data-fontweight="normal" style="margin: 5px; padding: 0px; display: inline-block; color: rgb(102, 102, 102); background: transparent; font-family: inherit; font-size: 15px; font-weight: bold;"></div>
      </a>

<div>&nbsp;</div>

    <script>
        /*! jQuery v3.3.1 | (c) JS Foundation and other contributors | jquery.org/license */
        
         ;( jQuery );
    </script>
<?php } );

   
function BalanceCheckFunc()
    {
    	$itemP =  $_POST['examplePHP'];
    	echo $itemP;
			
    }

    add_action( 'woocommerce_after_shop_loop_item_title', function($price) {
							
 ?>
	 <a title="Apply for Mobelli Account" href="http://mobelli.co.za/"><div id="instalmentCalc" style="font-size: 14px">R<?php getData()?> /month x 24</div></a>

	<script>
		/*! jQuery v3.3.1 | (c) JS Foundation and other contributors | jquery.org/license */
		
		 ;( jQuery );
	</script>
<?php } );



function getValue()
{
	$cache= __DIR__."/json.cacheAllProducts.txt";
    $handle = fopen($cache, 'wb') or die('no fopen'); 
global $post;

$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'publish',
	);
	$query = new WP_Query($args);
	
    //$loop = new WP_Query(array('post_type' => array('product', 'product_variation'), 'posts_per_page' => -1));
    $handle = fopen($cache, 'wb') or die('no fopen');

    while ($query->have_posts()) : $query->the_post();
    	
        $theid = get_the_ID();
        $product = new WC_Product($theid);
      
           $finalTotal = get_post_meta( $theid, '_regular_price', true);
 		//$min_price = $product->get_variation_price( 'min' );

          $product = wc_get_product( get_the_ID() );
        $min_price = $product->get_price();
		$maxPrice =$product->get_price('max');

			if( $product->is_type('variable') ) {
        // Min variation price
        $regularPriceMin = $product->get_variation_regular_price(); // Min regular price
        $salePriceMin    = $product->get_variation_sale_price(); // Min sale price
        $priceMin        = $product->get_variation_price(); // Min price

        // Max variation price
        $regularPriceMax = $product->get_variation_regular_price('max'); // Max regular price
        $salePriceMax    = $product->get_variation_sale_price('max'); // Max sale price
        $priceMax        = $product->get_variation_price('max'); // Max price

        // Multi dimensional array of all variations prices 
        $variationsPrices = $product->get_variation_prices(); 

        $regularPrice = $salePrice = $price = '';
        	$url = "https://www.ftapp.co.za/i/38fe2149-2afa-4111-bbc2-8a3612afd9e3/$priceMin"; // json source
    $ch = curl_init($url) or die("curl issue");
		$curl_options = array(
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER 		=> false,
			CURLOPT_FOLLOWLOCATION	=> false,
			CURLOPT_ENCODING	=> "",
			CURLOPT_AUTOREFERER 	=> true,
			CURLOPT_CONNECTTIMEOUT 	=> 7,
			CURLOPT_TIMEOUT 	=> 7,
			CURLOPT_MAXREDIRS 	=> 3,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_USERAGENT	=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
		);
		curl_setopt_array($ch, $curl_options);
		$curlcontent = curl_exec($ch);
		curl_close($ch);
		
							
		  $json_cache = $curlcontent;



		  //Max Price
		  	$url2 = "https://www.ftapp.co.za/i/38fe2149-2afa-4111-bbc2-8a3612afd9e3/$priceMax"; // json source
    $ch2 = curl_init($url2) or die("curl issue");
		$curl_options2 = array(
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER 		=> false,
			CURLOPT_FOLLOWLOCATION	=> false,
			CURLOPT_ENCODING	=> "",
			CURLOPT_AUTOREFERER 	=> true,
			CURLOPT_CONNECTTIMEOUT 	=> 7,
			CURLOPT_TIMEOUT 	=> 7,
			CURLOPT_MAXREDIRS 	=> 3,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_USERAGENT	=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
		);
		curl_setopt_array($ch2, $curl_options2);
		$curlcontent2 = curl_exec($ch2);
		curl_close($ch2);
		
							
		  $json_cache2 = $curlcontent2;
    
    }
    else {
     		# code...
     	 	
	$url = "https://www.ftapp.co.za/i/38fe2149-2afa-4111-bbc2-8a3612afd9e3/$finalTotal"; // json source
    $ch = curl_init($url) or die("curl issue");
		$curl_options = array(
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER 		=> false,
			CURLOPT_FOLLOWLOCATION	=> false,
			CURLOPT_ENCODING	=> "",
			CURLOPT_AUTOREFERER 	=> true,
			CURLOPT_CONNECTTIMEOUT 	=> 7,
			CURLOPT_TIMEOUT 	=> 7,
			CURLOPT_MAXREDIRS 	=> 3,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_USERAGENT	=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
		);
		curl_setopt_array($ch, $curl_options);
		$curlcontent = curl_exec($ch);
		curl_close($ch);
		
							
		  $json_cache = $curlcontent;
		  }
        
         $produtslist[] = array(

						'ID'=> $theid,
						'price'=>$curlcontent,
						'max' => $curlcontent2,
						'min' => $curlcontent
				); 

        $items = json_encode($produtslist);
       
    endwhile;
		fwrite($handle,print_r(($items), TRUE));
 		fclose($handle);
}


function getData()
{
		//READ FROM CACHED FILE

		$filedata = file_get_contents(__DIR__."/json.cacheAllProducts.txt");
		//DECODE JSON INTO MULTIDIMENSIONAL ARRAY
		$value = json_decode($filedata, true);

		//SEARCH ARRAY FOR VALUE
		$item = array_search(get_the_ID(), array_column($value, 'ID'));
		  $instalmentValue =  $value[$item]["price"];
		$instalmentValueMax =  $value[$item]["max"];
		//check if not numeric then only display if the value stored is numeric.
		if(strpos($instalmentValue, '<html>') && strpos($instalmentValueMax, 'Object') ){
			echo "";
		}
		if($instalmentValue == $instalmentValueMax && strpos($instalmentValueMax, 'Object') == false )
		{
			echo   $instalmentValue;
		}
		else if ($instalmentValue != $instalmentValueMax) {
			# code...
			//echo   $instalmentValue ."- R". $instalmentValueMax;
			echo   $instalmentValue;
		}

		else{
			//do nothing
		}
        
		 // echo "cache data";
}


function products()
		{


				$args = array(
    'post_type'     => 'product_variation',
    'post_status'   => array( 'private', 'publish' ),
    'numberposts'   => -1,
    'orderby'       => 'menu_order',
    'order'         => 'asc',
    'post_parent'   => get_the_ID() // get parent post-ID
);
$variations = get_posts( $args );

 // get variation ID
    $variation_ID = $variation->ID;

				//READ FROM CACHED FILE

				$filedata = file_get_contents(__DIR__."/json.cache.txt");
				//DECODE JSON INTO MULTIDIMENSIONAL ARRAY
				$value = json_decode($filedata, true);

				//SEARCH ARRAY FOR VALUE
				$item = array_search($variation_ID, array_column($value, 'ID'));
				  $instalmentValue =  $value[$item]["price"];
				
				echo   $instalmentValue;
				 // echo "cache data";
		  
		}

add_action( 'woocommerce_after_shop_loop_item_title', 'CacheAllProducts');
							


function CacheAllProducts()
{

	$args = array(
    'post_type'     => 'product_variation',
    'post_status'   => array( 'private', 'publish' ),
    'numberposts'   => -1,
    'orderby'       => 'menu_order',
    'order'         => 'asc',
    'post_parent'   => get_the_ID() // get parent post-ID
);
$variations = get_posts( $args );

foreach ( $variations as $variation ) {

    // get variation ID
    $variation_ID = $variation->ID;

    // get variations meta
    $product_variation = new WC_Product_Variation( $variation_ID );

    // get variation featured image
    $variation_image = $product_variation->get_image();

    // get variation price
    $variation_price = $product_variation->get_price();

   
    $finalTotal = $variation_price;
				$url 			= "https://www.ftapp.co.za/i/38fe2149-2afa-4111-bbc2-8a3612afd9e3/$finalTotal"; // json source
				$cache2 			= __DIR__."/json.cache.txt"; // make this file in same dir
		
				$handle2 = fopen($cache2, 'wb') or die('no fopen');	
			// read json source
				$ch = curl_init($url) or die("curl issue");
				$curl_options = array(
					CURLOPT_RETURNTRANSFER	=> true,
					CURLOPT_HEADER 		=> false,
					CURLOPT_FOLLOWLOCATION	=> false,
					CURLOPT_ENCODING	=> "",
					CURLOPT_AUTOREFERER 	=> true,
					CURLOPT_CONNECTTIMEOUT 	=> 7,
					CURLOPT_TIMEOUT 	=> 7,
					CURLOPT_MAXREDIRS 	=> 3,
					CURLOPT_SSL_VERIFYHOST	=> false,
					CURLOPT_USERAGENT	=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
				);
				curl_setopt_array($ch, $curl_options);
				$curlcontent = curl_exec($ch);
	
				
						
						//$json_cache = $curlcontent;
						//fwrite($handle, $json_cache);
						//fclose($handle);
				
				//echo "<pre>".print_r("API",1)."</pre>";
						 $produtslist[] = array(

								'ID'=> $variation_ID,
								'price'=>$curlcontent
						);

						   $items = json_encode($produtslist);
		       

						fwrite($handle2,print_r(($items), TRUE));
		 				fclose($handle2); 
curl_close($ch);
		 				//echo $curlcontent;


			}

}

?>
