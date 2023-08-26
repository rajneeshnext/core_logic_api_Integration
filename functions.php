<?php
//Code to be added to functions.php in wordpress theme. 
// You can use the function directly anywhere if its non-wordpress website.
add_shortcode( 'corelogic_properties', 'corelogic_properties_func' );
function corelogic_properties_func( ) {
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<style>
  .owl-item .impress-carousel-property{    margin: 0 10px;
    text-align: left;}
    .impress-carousel .owl-controls span, .impress-carousel .owl-dots {
    text-transform: uppercase;
    font-size: 12px;
    display: none;
}
.impress-carousel span.impress-street, .impress-carousel .impress-address span{
	display: revert !important;
}
.impress-carousel .owl-nav.owl-controls button.owl-next, .impress-carousel .owl-nav.owl-controls button.owl-prev {
    padding-left: 14px !important;
    background: #fff !important;
    padding-right: 14px !important;
    padding-top: 3px !important;
    padding-bottom: 2px !important;
}
.cusstom.impress-carousel span.impress-price{right: 15px; top: 2px;}
.sale_rent{position: absolute;
    left: 0;
    top: 0px;
    background: #fff;
    padding-left: 15px;
    padding-right: 15px;
    font-weight: bold;}
</style>
<script>
      window.addEventListener("DOMContentLoaded", function(event) {
         jQuery(".impress-listing-carousel-3").owlCarousel({
            items: 3,
            autoplay: true,
            nav: true,
            navText: ["<i class=\"fas fa-caret-left\"></i><span>Prev</span>", "<i class=\"fas fa-caret-right\"></i><span>Next</span>"],
            loop: true,
            lazyLoad: true,
            addClassActive: true,
            itemsScaleUp: true,
            navContainerClass: "owl-controls owl-nav",
            responsiveClass:true,
            responsive:{
               0:{
                  items: 1,
                  nav: true,
                  margin: 0
               },
               450:{
                  items: 2,
                  loop: true
               },
               800:{
                  items: 3,
                  loop: true
               }
            }
         });
      });
</script>
<?php 
$url = "https://api-prod.corelogic.com/trestle/oidc/connect/token";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Content-Type: application/x-www-form-urlencoded",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = "client_id=xxxxxxxx&client_secret=xxxxxxxxx&grant_type=client_credentials&scope=api";
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl);
curl_close($curl);
$data = json_decode($resp);
$token = $data->access_token;

$url = 'https://api-prod.corelogic.com/trestle/odata/Property?$filter=StandardStatus%20eq%20%27Active%27&$top=50&$expand=Media';
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Authorization: Bearer $token",
);


curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POST, false);
$resp = curl_exec($curl);
curl_close($curl);
$data_property = json_decode($resp);
$data_property_array = $data_property->value;

/*
$url = 'https://api-prod.corelogic.com/trestle/odata/Media?$filter=StandardStatus%20eq%20%27Active%27&$top=50'; 
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Authorization: Bearer $token",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POST, false);
$resp = curl_exec($curl);
curl_close($curl);
$data_media = json_decode($resp);
$data_mediaV = $data_media->value; */ 
?>

<div class="cusstom impress-carousel impress-listing-carousel-3 impress-carousel-shortcode owl-carousel owl-theme">
   <?php
      $i=0;
      foreach($data_property_array as $data_property_a){
          $price = $data_property_a->ListPrice;
          $address = $data_property_a->UnparsedAddress;
          $loc = $data_property_a->City.", ".$data_property_a->StateOrProvince.", ".$data_property_a->ZipCode;
          $bed = $data_property_a->BedroomsTotal;
          $baths = $data_property_a->BathroomsTotalInteger;
          $sizeAcre = $data_property_a->LotSizeAcres;
          $sizeLiving = $data_property_a->LivingArea;  
    ?>     
   <div class="impress-carousel-property">
              <a href="javascript:void(0);" class="impress-carousel-photo" target="_self">
                  <img class="owl-lazy lazyOwl" data-src="<?php echo $data_property_a->Media[0]->MediaURL;?>" alt="<?php echo $address;?>" title="<?php echo $address;?>" />
                  <span class="impress-price">$<?php echo $price;?></span>
<span class="sale_rent"> <?php if($data_property_a->PropertyType == "ResidentialLease"){echo "Rent";}else{echo "Sale";} ?></span>
              </a>
              <a href="javascript:void(0);" target="_self">
                  <p class="impress-address">
                      <span class="impress-street"><?php echo $address;?></span>
                      <span class="impress-cityname"><?php echo $loc;?></span>
                  </p>
              </a>
              <p class="impress-beds-baths-sqft">
                  <span class="impress-acres">MLS# <?php echo $data_property_a->ListingId;?></span>
                  <span class="impress-beds"><?php echo $bed;?> Beds</span> 
                  <span class="impress-baths"><?php echo $baths;?> Baths</span> 
                  <span class="impress-sqft"><?php echo $sizeLiving;?> SqFt</span> 
                  <span class="impress-acres"><?php echo $sizeAcre;?> Acres</span> 
              </p>                     
   </div>
   <?php  
      $i++;    
      }
    ?>
</div>
<?php
}
?>
