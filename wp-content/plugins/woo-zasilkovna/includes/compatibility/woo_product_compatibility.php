<?php
/**
 * @package    Comaptibility
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */



if( !class_exists( 'Woo_Product_Compatibility' ) ){

    class Woo_Product_Compatibility{


        /**
         * Return product id
         *
         */
        static public function get_id( $_product ){

            $version = woo_check_wc_version();

            if( $version === false ){

                $id = $_product->id;
                    
            }else{

                $id = $_product->get_id();
                            
            }

            return $id;

        }

        /**
         * Return product tax status
         *
         */
        static public function get_tax_status( $_product ){

            $version = woo_check_wc_version();

            if( $version === false ){

                $tax_status = $_product->tax_status;
                    
            }else{

                $tax_status = $_product->get_tax_status();
                            
            }

            return $tax_status;

        }

        /**
         * Return product tax calss
         *
         */
        static public function get_tax_class( $_product ){

            $version = woo_check_wc_version();

            if( $version === false ){

                $tax_class = $_product->get_tax_class();
                    
            }else{

                $tax_class = $_product->get_tax_class();
                            
            }

            return $tax_class;

        }


        


    }

}