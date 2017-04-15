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

class BansTable extends \WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct($type = 'default') {
        parent::__construct( array(
            'singular'=> 'wp_list_text_link', //Singular label
            'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
            'ajax'   => false //We won't support Ajax for this table
        ) );

        $this->type = $type;
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
        if($this->type == 'default') {
            return $columns= array(
                'col_ip'=>__('IP'),
                'col_created_on'=>__('Banned since'),
                'col_banned_until'=>__('Banned until'),
                'col_reason'=>__('Reason'),
                'col_actions'=>__('Actions')
            );
        } elseif($this->type == 'country') {
            return $columns= array(
                'col_created_on'=>__('Date'),
                'col_type'=>__('Country Code'),
                'col_actions'=>__('Actions')
            );
        } else {
            return FALSE;
        }
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
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

        if($this->type == 'default') {
            $query ="SELECT * FROM " . $wpdb->prefix . "cybercure_security_bans ORDER BY " . $orderby . " " . $order;
        } elseif($this->type == 'country') {
            $query ="SELECT * FROM " . $wpdb->prefix . "cybercure_security_bans_country ORDER BY " . $orderby . " " . $order;
        } else {
            return FALSE;
        }

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
        if(!empty($records)){foreach($records as $rec) {

            if($this->type == 'default') {
                echo '<tr id="record_'.$rec->id.'">';
                echo '<td>'.stripslashes($rec->ip).'</td>';
                echo '<td>'.stripslashes($rec->created_on).'</td>';
                echo '<td>'.stripslashes($rec->banned_until).'</td>';
                echo '<td>'.stripslashes($rec->reason).'</td>';
                echo '<td><a href="admin.php?page=cybercure_security_bans&action=unban&type=ip&id=' .$rec->id. '" data-toggle="tooltip" title="Unban this IP"><div class="dashicons dashicons-trash"></div></a></td></tr>';
            } elseif($this->type == 'country') {
                echo '<tr id="record_'.$rec->id.'">';
                echo '<td>'.stripslashes($rec->created_on).'</td>';
                echo '<td>'.stripslashes($rec->country_code).'</td>';
                echo '<td><a href="admin.php?page=cybercure_security_bans&action=unban&type=country&id=' .$rec->id. '" data-toggle="tooltip" title="Unban this country"><div class="dashicons dashicons-trash"></div></a></td></tr>';

            } else {
                return FALSE;
            }
        }}
    }
}