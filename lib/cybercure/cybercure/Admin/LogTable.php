<?php namespace Cybercure\Cybercure\Admin;
/*======================================================================*\
|| #################################################################### ||
|| #                This file is part of Cybercure                    # ||
|| #                          for  #RISK[Solutions]Maurice            # ||
|| # ---------------------------------------------------------------- # ||
|| #         Copyright © 2017 cybercure.ngrok.io. All Rights Reserved.# ||
|| #                                                                  # ||
|| # ----------     Cybercure IS AN OPENSOURCE SOFTWARE    ---------- # ||
|| # -------------------- https://cybercure.ngrok.io -------- ------- # ||
|| #################################################################### ||
\*======================================================================*/

class LogTable extends \WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct( array(
            'singular'=> 'wp_list_text_link', //Singular label
            'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
            'ajax'   => false //We won't support Ajax for this table
        ) );
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav( $which ) {
        if ( $which == "top" ){
            //The code that goes before the table is here
        }
        if ( $which == "bottom" ){
            //The code that goes after the table is there
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns= array(
            'col_created_on'=>__('Date'),
            'col_type'=>__('Type'),
            'col_ip'=>__('IP'),
            'col_os'=>__('Operating System'),
            'col_browser'=>__('Browser'),
            'col_location'=>__('Location'),
            'col_actions'=>__('Actions')
        );
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'col_created_on'=> array('created_on', false),
            'col_type'=> array('type', false),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        # Should we order the columns
        if(isset($_GET["order"]) && ($_GET["order"] == 'asc' || $_GET["order"] == 'desc')) {
            $order =  strtoupper($_GET["order"]);
        } else {
            $order = 'ASC';
        }

        $orderby = 'created_on';
        if(isset($_GET["orderby"])) {
            # Make sure that the selected column is allowed to be sorted
            $sortable_columns = $this->get_sortable_columns();
            if(isset($sortable_columns['col_' . $_GET["orderby"]])) {
                $orderby = $_GET["orderby"];
            }
        }

        $query ="SELECT * FROM " . $wpdb->prefix . "cybercure_security_detected_attacks ORDER BY " . $orderby . " " . $order;
        $totalitems = $wpdb->query($query);


        //How many to display per page?
        $perpage = 20;

        # Which page is this?
        if(isset($_GET["paged"]) && is_numeric($_GET["paged"])) {
            $paged =  $_GET["paged"];
        }

        # Set the default page number to 1
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ) {
            $paged = 1;
        }

        //How many pages do we have in total?
        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }

        $this->_column_headers = array(
            $this->get_columns(),		// columns
            array(),			// hidden
            $this->get_sortable_columns(),	// sortable
        );

        /* -- Register the pagination -- */
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;

        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);

    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        # Get the records registered in the prepare_items method
        $records = $this->items;

        //Loop for each record
        if(!empty($records)){foreach($records as $rec){
            $custom_data = unserialize($rec->custom_data);

            echo '<tr id="record_'.$rec->id.'">';
            echo '<td>'.stripslashes($rec->created_on).'</td>';
            echo '<td>'.stripslashes($rec->type).'</td>';
            echo '<td>'.stripslashes($rec->ip).'</td>';
            echo '<td>'.stripslashes($custom_data['personal']['OS']['name']).'</td>';
            echo '<td>'.stripslashes($custom_data['personal']['browser']['name']).'</td>';
            echo '<td>'.stripslashes($custom_data['personal']['location']['name']).'</td>';
            echo '<td>
            <a href="" data-post="action=detailed_log&type=' .$rec->type. '&id=' .$rec->id. '&detail=full&ip=' .$rec->ip. '" data-type="modal" data-toggle="tooltip" title="Show details"><div class="dashicons dashicons-list-view"></div></a>
            <a href="admin.php?page=cybercure_security_detected_attacks&type=' .$rec->type. '&delete=' .$rec->id. '" data-toggle="tooltip" title="Delete entry"><div class="dashicons dashicons-trash"></div></a>
            </td></tr>';
        }}
    }
}