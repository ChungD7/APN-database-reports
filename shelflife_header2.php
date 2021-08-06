<!-- TWO FUNCTIONS: ONE THAT RETURNs THE BEST SHELF LIFE STYLE FOR THAT YEAR AND THE OTHER PER SEASONS FOR THAT YEAR -->

<?php
function routineYearly($year, $store, $division, $orderType, $status, $salesRep, $shelf_date)
{
    $connection = new sqlCONNECTION;
    $holderArr = array();
    $order_noArr = array();
    $style_noArr = array();

    if ($shelf_date)
    {
        $q_uery = $connection->newQuery("SELECT style_no, inout_date FROM chastestdb.warehouses_inventory_inout");
        while ($row =  mysql_fetch_assoc($q_uery)) 
        {
            
            $key = $row["style_no"];
            $val = $row["inout_date"];
            if(array_key_exists($key, $holderArr))
            {
                if(calcDays($val, $holderArr[$key] > 0))
                {
                    $holderArr[$key] = $val;
                }
            }
            else
            {
                $holderArr[$key] = $val;
            }
        }
    }
    else
    {
        $q_uery = $connection->newQuery("SELECT style_no, date_added FROM chastestdb.style");
        while ($row =  mysql_fetch_assoc($q_uery)) 
        {
            $key = $row["style_no"];
            $val = $row["date_added"];
            if(array_key_exists($key, $holderArr))
            {
                if(calcDays($val, $holderArr[$key] > 0))
                {
                    $holderArr[$key] = $val;
                }
            }
            else
            {
                $holderArr[$key] = $val;
            }
        }
    }

    $q_uery = $connection->newQuery(stringRunner($store, $division, $orderType, $status, $salesRep));
    while ($row =  mysql_fetch_assoc($q_uery)) 
    {
        $key = (string)$row["order_no"];
        $date_t = (string)$row["order_date"];
        if ((int)substr($date_t, 0,4) != $year)
        {
            continue;
        }
        array_push($order_noArr, $key); 
    }
    
    if (empty($order_noArr))
    {
        exit("No results with the given filters.");
    }

    foreach($order_noArr as $index_order => $orderNo)
    {
        $order_date = mysql_fetch_assoc($connection->newQuery("SELECT order_no, order_date  FROM chastestdb.xorder WHERE order_no = '$orderNo'"))["order_date"];
        $i_query = $connection->newQuery("SELECT order_no, style_no, color  FROM chastestdb.xorder_tech_pack WHERE order_no = '$orderNo'");
        while ($row = mysql_fetch_assoc($i_query))
        {
            //check for cancellations in colors double space no color then a cancellation has occured?
            $keyColor = (string)$row["color"];
            if(!strcmp($keyColor,""))
            {
                continue;
            }

            $keyStyleNo = (string)$row["style_no"];
            if (array_key_exists($keyStyleNo, $holderArr))
            {
                $_date = $holderArr[$keyStyleNo];
            }
            else
            {
                continue;
            }
            $dayVar = calcDays($_date, $order_date);

            if ($dayVar < 0)
            {
                continue;
            }
            if (array_key_exists($keyStyleNo, $style_noArr))
            {
                $style_noArr[$keyStyleNo]['styleCount']++;
                $style_noArr[$keyStyleNo]['days'] += $dayVar;
            }
            else
            {
                $temp = Array();
                $temp['styleCount'] = 1;
                $temp['days'] = $dayVar;
                $style_noArr[$keyStyleNo] = $temp;
            }
        } 
    }
    
    $min = 1000;
    foreach($style_noArr as $style => $styleInfo)
    {
        $style_noArr[$style] = round($style_noArr[$style]['days'] / $style_noArr[$style]['styleCount']);
        if ($style_noArr[$style] < $min)
        {
            $min = $style_noArr[$style];
            $minStyle = $style;
        }
    }
   return [$minStyle , $min, $connection->getCategoryInfo($minStyle)]; 
}

function routineSeasonly($year, $store, $division, $orderType, $status, $salesRep, $shelf_date)
{
    $connection = new sqlCONNECTION;
    $seasonsArr = new seasons();
    $returnArr = [
        "winterArr" => array(),
        "springArr" => array(),
        "summerArr" => array(),
        "fallArr" => array()
    ];

    $holderArr = array();
    if ($shelf_date)
    {
        $q_uery = $connection->newQuery("SELECT style_no, inout_date FROM chastestdb.warehouses_inventory_inout");
        while ($row =  mysql_fetch_assoc($q_uery)) 
        {
            $key = $row["style_no"];
            $val = $row["inout_date"]; 
            if(array_key_exists($key, $holderArr))
            {
                if(calcDays($val, $holderArr[$key] > 0))
                {
                    $holderArr[$key] = $val;
                }
            }
            else
            {
                $holderArr[$key] = $val;
            }
        }
    }
    else
    {
        $q_uery = $connection->newQuery("SELECT style_no, date_added FROM chastestdb.style");
        while ($row =  mysql_fetch_assoc($q_uery)) 
        {
            
            $key = $row["style_no"];
            $val = $row["date_added"]; 
            if(array_key_exists($key, $holderArr))
            {
                if(calcDays($val, $holderArr[$key] > 0))
                {
                    $holderArr[$key] = $val;
                }
            }
            else
            {
                $holderArr[$key] = $val;
            }
        }
    }

    $q_uery = $connection->newQuery(stringRunner($store, $division, $orderType, $status, $salesRep));
    while ($row =  mysql_fetch_assoc($q_uery)) 
    {
        $key = (string)$row["order_no"];
        $date_t = (string)$row["order_date"];
        if ((int)substr($date_t, 0,4) != $year)
        {
            continue;
        }
        $month_date = (int)substr($date_t,5,2);
        $seasonsArr->categorizeOrder($month_date,$key);
    }
    $boolTester = true;
    foreach($seasonsArr->seasonalArraysOrder as $seaArr => $orderArr)
    {
        if(!empty($seasonsArr->seasonalArraysOrder[$seaArr]))
        {
            $boolTester = false;
        }
    }
    if ($boolTester)
    {
        exit("No results with the given filters.");
    }

    foreach($seasonsArr->seasonalArraysOrder as $seaArr => $orderArr)
    {
        foreach($orderArr as $index_order => $orderNo)
        {
            $order_date = mysql_fetch_assoc($connection->newQuery("SELECT order_no, order_date  FROM chastestdb.xorder WHERE order_no = '$orderNo'"))["order_date"];
            $i_query = $connection->newQuery("SELECT order_no, style_no, color  FROM chastestdb.xorder_tech_pack WHERE order_no = '$orderNo'");
            while ($row = mysql_fetch_assoc($i_query))
            {
                $keyColor = (string)$row["color"];
                if(!strcmp($keyColor,""))
                {
                    continue;
                }
                $keyStyleNo = (string)$row["style_no"];
                if (array_key_exists($keyStyleNo, $holderArr))
                {
                    $_date = $holderArr[$keyStyleNo];
                }
                else
                {
                    continue;
                }
                $dayVar = calcDays($_date, $order_date);
                if ($dayVar < 0)
                {
                    continue;
                }
                if (array_key_exists($keyStyleNo, $seasonsArr->seasonalArraysStyle[$seaArr]))
                {
                    $seasonsArr->seasonalArraysStyle[$seaArr][$keyStyleNo]['styleCount']++;
                    $seasonsArr->seasonalArraysStyle[$seaArr][$keyStyleNo]['days']+= $dayVar;
                }
                else
                {
                    $temp = Array();
                    $temp['styleCount'] = 1;
                    $temp['days'] = $dayVar;
                    $seasonsArr->seasonalArraysStyle[$seaArr][$keyStyleNo] = $temp;
                }
            } 
        }
    }

    foreach ($seasonsArr->seasonalArraysStyle as $seaArr => $styleArr)
    {
        $min = 1000;
        foreach($styleArr as $style => $styleInfo)
        {
            $seasonsArr->seasonalArraysStyle[$seaArr][$style]
            = round($seasonsArr->seasonalArraysStyle[$seaArr][$style]['days']/$seasonsArr->seasonalArraysStyle[$seaArr][$style]['styleCount']);
            if ($seasonsArr->seasonalArraysStyle[$seaArr][$style] < $min)
            {
                $min = $seasonsArr->seasonalArraysStyle[$seaArr][$style];
                $minStyle = $style;
            }
        }
        $returnArr[$seaArr] = [$minStyle , $min, $connection->getCategoryInfo($minStyle)];
    }
    return $returnArr;
}

?>