<!-- CONNECTION / CLASSES / GLOBAL FUNCTIONS -->
<?php

/* CLASSES */

class sqlCONNECTION
{
    private $link;

    //constructor connnects to db
    /* CHANGE INFO TO APN DB (ONLY NEED TO LOGIN CREDENTIALS HERE)*/
    function __construct()
    {
        $servername = '127.0.0.1:3307';
        $username = 'root';
        $database = 'apnbackup';
        $this->link = mysql_connect($servername, $username);
        if (!$this->link) 
        {
            die('Could not connect: ' . mysql_error());
        }
        if (!mysql_select_db($database, $this->link))
        {
            die('Unable to connect to DB: ' . mysql_error());
        }
    }

    //simplified query function
    function newQuery($sqlString)
    {
        $q_uery = mysql_query($sqlString, $this->link);
        if (!$q_uery)
        {
            die("Could not run query from DB: " . mysql_error());
        }
        return $q_uery;
    }

    //returns category and subcategory as an array given a style as a parameter
    function getCategoryInfo($style)
    {
        $q_uery = mysql_query("SELECT style_no, styletype, sub_type FROM chastestdb.style WHERE style_no = '$style'", $this->link);
        if (!$q_uery)
        {
            die("Could not run query from DB: " . mysql_error());
        }
        $row = mysql_fetch_assoc($q_uery);
        $temp = Array();
        $temp['category'] = $row["styletype"];
        $temp['subCategory'] = $row["sub_type"];
        if (!strcmp($temp['category'],'0') || $temp['category'] === NULL )
        {
            $q_uery = mysql_query("SELECT Category FROM chastestdb.category_sub", $this->link);
            if (!$q_uery)
            {
                die("Could not run query from DB: " . mysql_error());
            }
            $temp['category'] = mysql_fetch_assoc($q_uery)["Category"];
        }
        if (!strcmp($temp['subCategory'],'0') || $temp['subCategory'] === NULL)
        {
            $test = $temp['category'];
            $q_uery = mysql_query("SELECT Category, subCat1 FROM chastestdb.category_sub WHERE Category = '$test'", $this->link);
            if (!$q_uery)
            {
                die("Could not run query from DB: " . mysql_error());
            }
            $temp['subCategory'] = mysql_fetch_assoc($q_uery)["subCat1"];
        }
        return $temp;
    }

    //destructor disconnects from db
    function __destruct()
    {
        mysql_close($this->link);
    }
}

/* SEASON class designed to be a class of arrays to hold information based on seasons */

class seasons
{
    //array for holding sales order info
    public $seasonalArraysOrder = [
        "winterArr" => array(),
        "springArr" => array(),
        "summerArr" => array(),
        "fallArr" => array()
    ];

    //array for holding style info
    public $seasonalArraysStyle = [
        "winterArr" => array(),
        "springArr" => array(),
        "summerArr" => array(),
        "fallArr" => array()
    ];

    //categorizes the different sales order based on seasons
    function categorizeOrder($date,$keyOrder)
    {
        if ($date >= 3 && $date <=5)
        {
            array_push($this->seasonalArraysOrder["springArr"], $keyOrder);
        }
        else if ($date >=6 && $date <=8)
        {
            array_push($this->seasonalArraysOrder["summerArr"], $keyOrder);
        }
        else if ($date >=9 && $date <=11)
        {
            array_push($this->seasonalArraysOrder["fallArr"], $keyOrder);
        }
        else
        {
            array_push($this->seasonalArraysOrder["winterArr"], $keyOrder);
        }
    } 
}

/* GLOBAL FUNCTIONS */

//concatenates and returns the optimal mysql string query given the conditions passed along from the filter page
//if check is if condition is not given: default value
function stringRunner($store, $division, $orderType, $status, $salesRep)
{
    $query_part1 = "SELECT order_no, order_date";
    $query_part2 = " FROM chastestdb.xorder";
    $boolVar = true;

    if ($store != 0 )
    {
        $query_part1 .= ", store_id";
        $query_part2 .= " WHERE store_id = '$store'";
        $boolVar = false;
    }
    if ($division != 0)
    {
        $query_part1 .= ", company_id";
        if ($boolVar)
        {
            $query_part2 .= " WHERE company_id = '$division'";
            $boolVar = false;
        }
        else
        {
            $query_part2 .= " AND company_id = '$division'";
        }
    }
    if (strcmp($orderType,"temp"))
    {
        $query_part1 .= ", business_type";
        if ($boolVar)
        {
            $query_part2 .= " WHERE business_type = '$orderType'";
            $boolVar = false;
        }
        else
        {
            $query_part2 .= " AND business_type = '$orderType'";
        }
    }
    if ($status != 6)
    {
        $query_part1 .= ", `status`";
        if ($boolVar)
        {
            $query_part2 .= " WHERE `status` = '$status'";
            $boolVar = false;
        }
        else
        {
            $query_part2 .= " AND `status` = '$status'";
        }
    }
    if ($salesRep != 2 && $salesRep != 0)
    {
        {
            $query_part1 .= ", salesman_idx";
            if ($boolVar)
            {
                $query_part2 .= " WHERE salesman_idx = '$salesRep'";
                $boolVar = false;
            }
            else
            {
                $query_part2 .= " AND salesman_idx = '$salesRep'";
            }
        }
    }
    $strQuery = $query_part1 . $query_part2;
    return $strQuery;
}

//calculates the approximate amount of days between any two dates
function calcDays($date1, $date2)
{
    $year_part = (substr($date2, 0, 4) - substr($date1, 0, 4) ) * 12 * 30 ;
    $month_part = (substr($date2, 5, 2) - substr($date1, 5, 2) ) * 30 ;
    $day_part = substr($date2, 8,2 ) - substr($date1, 8, 2);
    return $year_part + $month_part + $day_part;
}

?>