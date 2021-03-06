<?php

/** QBOtoWoo
 *
 * WooProduct.php - A Component of QBOtoWoo
 * A general class for manipulating product objects in our plugin
 * Pulled from the WooCommerce API Docs http://woocommerce.github.io/
 *
 * This is probably obsessive, but I like details
 **/
class WooProduct {

    private $id;	                 // integer	Unique identifier for the resource. READ-ONLY
    private $name;	                 // string	Product name.
    private $type = 'simple';        //	string	Product type. Options: simple, grouped, external and variable. Default is simple.
    private $description;            //	string	Product description.
    private $short_description;      //	string	Product short description.
    private $sku;                    //	string	Unique identifier.
    private $price;                  //	string	Current product price. READ-ONLY
    private $regular_price;          //	string	Product regular price.
    private $sale_price;             //	string	Product sale price.
    private $tax_status = 'none';    //	string	Tax status. Options: taxable, shipping and none. Default is taxable.
    private $tax_class;	             //  string	Tax class.
    private $manage_stock = false;	         // boolean	Stock management at product level. Default is false.
    private $stock_quantity;         //	integer	Stock quantity.
    private $in_stock = false;               //	boolean	Controls whether or not the product is listed as “in stock” or “out of stock” on the frontend. Default is true.
    private $backorders;             //	string	If managing stock, this controls if backorders are allowed. Options: no, notify and yes. Default is no.
    private $backorders_allowed;     //	boolean	Shows if backorders are allowed. READ-ONLY
    private $backordered;            //	boolean	Shows if the product is on backordered. READ-ONLY
    private $status;                //	string	Product status (post status). Options: draft, pending, private and publish. Default is publish.
    private $sold_individually;	     //  boolean	Allow one item to be bought in a single order. Default is false.
    private $weight;                 //	string	Product weight (kg).
    private $dimensions;             //	object	Product dimensions. See Product - Dimensions properties
    private $shipping_required;      //	boolean	Shows if the product need to be shipped. READ-ONLY
    private $shipping_taxable;       //	boolean	Shows whether or not the product shipping is taxable. READ-ONLY
    private $shipping_class;         //	string	Shipping class slug.
    private $shipping_class_id;      //	string	Shipping class ID. READ-ONLY
    
    private $date_created;    // permalink	string	Product URL. READ-ONLY
    
    private $date_created_gmt;//date-time   The date the product was created, as GMT. READ-ONLY
    
    private $date_modified;	  // date-time	The date the product was last modified, in the site’s timezone. READ-ONLY
    
    private $date_modified_gmt;// date-time	The date the product was last modified, as GMT. READ-ONLY

    private $slug;            // string     Product slug.
    
    private $featured;              //	boolean	Featured product. Default is false.
    
    private $catalog_visibility;    //	string	Catalog visibility. Options: visible, catalog, search and hidden. Default is visible.
    
    private $date_on_sale_from;     //	date-time	Start date of sale price, in the site’s timezone.
    
    private $date_on_sale_from_gmt; //	date-time	Start date of sale price, as GMT.
    
    private $date_on_sale_to;       //	date-time	End date of sale price, in the site’s timezone.
    
    private $date_on_sale_to_gmt;   //	date-time	End date of sale price, in the site’s timezone.
    
    private $price_html;            //	string	Price formatted in HTML. READ-ONLY
    
    private $on_sale;	            //  boolean	Shows if the product is on sale. READ-ONLY
    
    private $purchasable;	        //  boolean	Shows if the product can be bought. READ-ONLY
    
    private $total_sales;	        //  integer	Amount of sales. READ-ONLY
    
    private $virtual;	            //  boolean	If the product is virtual. Default is false.
    
    private $downloadable;          //	boolean	If the product is downloadable. Default is false.
    
    private $downloads;             //	array	List of downloadable files. See Product - Downloads properties
    
    private $download_limit;        //	integer	Number of times downloadable files can be downloaded after purchase. Default is -1.
    
    private $download_expiry;       //	integer	Number of days until access to downloadable files expires. Default is -1.

    private static $num_products_created = 0;

    // Barebones constructor for creating a product
    public function __construct
    (
        $in_sku,
        $in_product_name,
        $in_regular_price,
        $in_prod_short_desc,
        $in_manage_stock,
        $in_stock_quantity
    )
    {
        $this->sku = $in_sku;
        $this->name = $in_product_name;
        $this->regular_price = $in_regular_price;
        $this->short_description = $in_prod_short_desc;
        $this->manage_stock = $in_manage_stock;
        $this->stock_quantity = $in_stock_quantity;

        // We'll use this number to report to the user
        self::$num_products_created++;
        echo "You created " . self::$num_products_created
            . " Products<br/>\n";
    }

    public function setID($in_id){
        $this->id = $in_id;
    }

    public function fetchProductData($woo_connection, $id){
        $woo_connection->get('/product/' . $id);
    }

    public function batchCreate($woo_connection, $prod_array){
        print_r($woo_connection->post('products/batch', $prod_array));

    }





    function createProduct($name, $type = 'simple', $regular_price, $description, $short_description,
                           $sku, $taxable = false, $managing_stock = false, $stock_quantity = null, $in_stock = false,
                           $woo_connection){
        $data = [
            'product' => [
                'name' => $name,
                'type' => $type,
                'regular_price' => $regular_price,
                'description' => $description,
                'short_description' => $short_description,
                'sku' => $sku,
                'taxable' => $taxable,
                'managing_stock' => $managing_stock,
                'stock_quantity' => $stock_quantity,
                'in_stock' => $in_stock
            ]
        ];

        print_r($woo_connection->post('products', $data));
        
    } // End of createProduct()
    
  
        
} // End Of WooProducts
/** todo - make variables out of these docs

external_url	string	Product external URL. Only for external products.
button_text	string	Product external button text. Only for external products.

reviews_allowed	boolean	Allow reviews. Default is true.
average_rating	string	Reviews average rating. READ-ONLY
rating_count	integer	Amount of reviews that the product have. READ-ONLY
related_ids	array	List of related products IDs. READ-ONLY
upsell_ids	array	List of up-sell products IDs.
cross_sell_ids	array	List of cross-sell products IDs.
parent_id	integer	Product parent ID.
purchase_note	string	Optional note to send the customer after purchase.
categories	array	List of categories. See Product - Categories properties
tags	array	List of tags. See Product - Tags properties
images	object	List of images. See Product - Images properties
attributes	array	List of attributes. See Product - Attributes properties
default_attributes	array	Defaults variation attributes. See Product - Default attributes properties
variations	array	List of variations IDs. READ-ONLY
grouped_products	array	List of grouped products ID. READ-ONLY
menu_order	integer	Menu order, used to custom sort products.
meta_data	array	Meta data. See Product - Meta data properties
Product - Downloads properties

Attribute	Type	Description
id	string	File MD5 hash. READ-ONLY
name	string	File name.
file	string	File URL.
Product - Dimensions properties

Attribute	Type	Description
length	string	Product length (cm).
width	string	Product width (cm).
height	string	Product height (cm).
Product - Categories properties

Attribute	Type	Description
id	integer	Category ID.
name	string	Category name. READ-ONLY
slug	string	Category slug. READ-ONLY
Product - Tags properties

Attribute	Type	Description
id	integer	Tag ID.
name	string	Tag name. READ-ONLY
slug	string	Tag slug. READ-ONLY
Product - Images properties

Attribute	Type	Description
id	integer	Image ID.
date_created	date-time	The date the image was created, in the site’s timezone. READ-ONLY
date_created_gmt	date-time	The date the image was created, as GMT. READ-ONLY
date_modified	date-time	The date the image was last modified, in the site’s timezone. READ-ONLY
date_modified_gmt	date-time	The date the image was last modified, as GMT. READ-ONLY
src	string	Image URL.
name	string	Image name.
alt	string	Image alternative text.
position	integer	Image position. 0 means that the image is featured.
Product - Attributes properties

Attribute	Type	Description
id	integer	Attribute ID.
name	string	Attribute name.
position	integer	Attribute position.
visible	boolean	Define if the attribute is visible on the “Additional information” tab in the product’s page. Default is false.
variation	boolean	Define if the attribute can be used as variation. Default is false.
options	array	List of available term names of the attribute.
Product - Default attributes properties

Attribute	Type	Description
id	integer	Attribute ID.
name	string	Attribute name.
option	string	Selected attribute term name.
Product - Meta data properties

Attribute	Type	Description
id	integer	Meta ID. READ-ONLY
key	string	Meta key.
value	string	Meta value.
*/

?>