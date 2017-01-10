<?php class Webre_Catalog_Model_Product_Observer
{
    public function catalog_product_save_before($observer) {
         
         $count=0;
         $product = $observer->getEvent()->getProduct();
        
         $product_id = $product->getId();
        
        
         $options = $product->getOptions();
                

                 /*   foreach ($options as $option) {
                        
                        
                    }*/
                    if (!empty($product_id)) {
                            //echo $product_id;
                            return;
                        }
                
                

                            //for($i=1;$i<=5;$i++){
                        $option1 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Sets',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 1,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => '1',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => '2',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                );
                                $option2 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Printed Slides',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 2,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => 'Yes',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => 'No',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                    );
                                $option3 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Drilled Hole',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 3,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => 'Yes',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => 'No',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                    );
                                $option4 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Rounded Corners',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 4,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => 'Yes',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => 'No',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                    );
                                $option5 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Foiling',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 5,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => 'Yes',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => 'No',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                    );
                                $option6 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Metallic foil color',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 6,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => 'Orange',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => 'Silver',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    ),
        array(
            'is_delete'     => 0,
            'title'         => 'Charcoal',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                    );
                                $option7 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'previous_group'    => '',
    'title'             => 'Embossing',
    'type'              => 'drop_down',
    'price_type'        => 'fixed',
    'price'             => '',
    'sort_order'        => 7,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => 'Yes',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        ),
        array(
            'is_delete'     => 0,
            'title'         => 'No',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
    )),
                                    );
                                $option8 = array(                                     
            'title' => 'Image1',
            'type' => 'file',
            'is_require' => false,
            'price' => '',
            'price_type' => 'fixed',
            'sku' => '',
            'file_extension' => '',
            'image_size_x' => '',
            'image_size_y' => '',
            'sort_order'        => 8

                                    );
                                 $option9 = array(                                     
            'title' => 'Image2',
            'type' => 'file',
            'is_require' => false,
            'price' => '',
            'price_type' => 'fixed',
            'sku' => '',
            'file_extension' => '',
            'image_size_x' => '',
            'image_size_y' => '',
            'sort_order'        => 9

                                    );
                                 $option10 = array(
                                        'is_delete'         => '',
    'is_require'        => false,
    'title'             => 'Digital PDF Proof',
    'type'              => 'checkbox',
    'sort_order'        => 10,
    /** array of values for this option **/
    'values'            => array(
        array(
            'is_delete'     => 0,
            'title'         => '50',
            'price_type'    => 'fixed',
            'price'         => '',
            'sku'           => '',
            'option_type_id'=> -1,
        )
        ),
                                    );

                                 $options =array($option1,$option2,$option3,$option4,$option5,$option6,$option7,$option8,$option9,$option10);
                                 foreach ($options as $option) {
                                    
                                    $product->setProductOptions($option);
                                    $product->setCanSaveCustomOptions(true);
                                    $product->getOptionInstance()->addOption($option);
                                    
                                    $product->setHasOptions(true);
                             }      


            
    }
}