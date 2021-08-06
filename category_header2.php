<!-- TWO FUNCTIONS: ONE THAT RETURNS BEST CATEGORIES AND SUB-CATEGORIES FOR THAT YEAR AND THE OTHER PER SEASONS FOR THAT YEAR -->

<?php

function routineYearly($year, $store, $division, $orderType, $status, $salesRep)
{
    $connection = new sqlCONNECTION;
    $q_uery = $connection->newQuery(stringRunner($store, $division, $orderType, $status, $salesRep));
    $order_noArr = array();
    $style_noArr = array();

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

    $holderArr = array();
    $returnArr = array();

    $q_uery = $connection->newQuery("SELECT category, subCat1, subCat2, subCat3, subCat4, subCat5, subCat6, subCat7, subCat8, 
    subCat9, subCat10, subCat11, subCat12, subCat13 FROM chastestdb.category_sub");
    while($row = mysql_fetch_assoc($q_uery))
    {
        $index = $row["category"];
        $holderArr[$index] = array();
        for ($i = 1; $i<=13; $i++)
        {
            $idx = "subCat" . "$i";
            $index2 = $row[$idx];
            if (!strcmp($index2,'0')|| $index2 === NULL)
            {
                continue;
            }
            $holderArr[$index][$index2] = 0;
        }
    }

    foreach($order_noArr as $index_order => $orderNo)
    {
        $q_uery = $connection->newQuery("SELECT order_no, style_no, color  FROM chastestdb.xorder_tech_pack WHERE order_no = '$orderNo'");
        while ($row = mysql_fetch_assoc($q_uery))
        {
            $keyColor = (string)$row["color"];
            if(!strcmp($keyColor,""))
            {
                continue;
            }
            $keyStyleNo = (string)$row["style_no"];

            if (array_key_exists($keyStyleNo, $style_noArr))
            {
                $style_noArr[$keyStyleNo]++;
            }
            else
            {
                $style_noArr[$keyStyleNo] = 1;
            }
        } 
    }

    foreach($style_noArr as $style => $styleCount)
    {
        $arr = $connection->getCategoryInfo($style);
        $category = $arr['category'];
        $sub_category = $arr['subCategory'];
        $holderArr[$category][$sub_category] += $styleCount;
    }

    $counter = array();
    foreach ($holderArr as $category => $subCatArr)
    {
        $count = 0;
        $array = array();
        $why = array_keys($subCatArr);
        while (count($why)!= 0)
        {
            $max = -1;
            foreach($why as $index => $subCat)
            {
                if($subCatArr[$subCat] > $max)
                {
                    $max = $subCatArr[$subCat];
                    $maxCat = $subCat; 
                    $idx = $index;
                }
            }
            $count += $max;
            array_push($array,$maxCat);
            unset($why[$idx]);
        }
        $holderArr[$category] = $array;
        $counter[$category] = $count;
    }
    $order = array();
    while (count($counter)!=0)
    {
        $max = -1;
        foreach($counter as $cat => $catCount)
        {
            if($catCount > $max)
            {
                $max = $catCount;
                $temp = $cat;
            }
        }
        array_push($order, $temp);
        unset($counter[$temp]);
    }
    foreach($order as $idx => $cat)
    {
        $returnArr[$cat] = $holderArr[$cat];
    }
    $holderArr = $order;
    return [$returnArr , $holderArr];
}

function routineSeasonly($year, $store, $division, $orderType, $status, $salesRep)
{
    $connection = new sqlCONNECTION;
    $seasonsArr = new seasons();
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

    $holderArr = [
        "winterArr" => array(),
        "springArr" => array(),
        "summerArr" => array(),
        "fallArr" => array()
    ];
    $returnArr = [
        "winterArr" => array(),
        "springArr" => array(),
        "summerArr" => array(),
        "fallArr" => array()
    ];

    foreach($seasonsArr->seasonalArraysOrder as $seaArr => $orderArr)
    {
        $q_uery = $connection->newQuery("SELECT category, subCat1, subCat2, subCat3, subCat4, subCat5, subCat6, subCat7, subCat8, 
        subCat9, subCat10, subCat11, subCat12, subCat13 FROM chastestdb.category_sub");
        while($row = mysql_fetch_assoc($q_uery))
        {
            $index = $row["category"];
            $holderArr[$seaArr][$index] = array();
            for ($i = 1; $i<=13; $i++)
            {
                $idx = "subCat" . "$i";
                $index2 = $row[$idx];
                if (!strcmp($index2,'0')|| $index2 === NULL)
                {
                    continue;
                }
                $holderArr[$seaArr][$index][$index2] = 0;
            }
        }
    }


    /* query: style_no and color from sales order teach pack into the seasonal arrays*/

    foreach($seasonsArr->seasonalArraysOrder as $seaArr => $orderArr)
    {
        foreach($orderArr as $index_order => $orderNo)
        {
            $q_uery = $connection->newQuery("SELECT order_no, style_no, color  FROM chastestdb.xorder_tech_pack WHERE order_no = '$orderNo'");
            while ($row = mysql_fetch_assoc($q_uery))
            {
                $keyColor = (string)$row["color"];
                if(!strcmp($keyColor,""))
                {
                    continue;
                }
                $keyStyleNo = (string)$row["style_no"];
                if (array_key_exists($keyStyleNo, $seasonsArr->seasonalArraysStyle[$seaArr]))
                {
                    $seasonsArr->seasonalArraysStyle[$seaArr][$keyStyleNo]++;
                }
                else
                {
                    $seasonsArr->seasonalArraysStyle[$seaArr][$keyStyleNo] = 1;
                }
            } 
        }
    }

    foreach ($seasonsArr->seasonalArraysStyle as $seaArr => $styleArr)
    {
        foreach($styleArr as $style => $styleCount)
        {
            $arr = $connection->getCategoryInfo($style);
            $category = $arr['category'];
            $sub_category = $arr['subCategory'];
            $holderArr[$seaArr][$category][$sub_category] += $styleCount;
        }
    }

    foreach ($holderArr as $seaArr => $catArr)
    {
        $counter = array();
        foreach ($catArr as $category => $subCatArr)
        {
            $count = 0;
            $array = array();
            $why = array_keys($subCatArr);
            while (count($why)!= 0)
            {
                $max = -1;
                foreach($why as $index => $subCat)
                {
                    if($subCatArr[$subCat] > $max)
                    {
                        $max = $subCatArr[$subCat];
                        $maxCat = $subCat; 
                        $idx = $index;
                    }
                }
                $count += $max;
                array_push($array,$maxCat);
                unset($why[$idx]);
            }
            $holderArr[$seaArr][$category] = $array;
            $counter[$category] = $count;
        }
        $order = array();
        while (count($counter)!=0)
        {
            $max = -1;
            foreach($counter as $cat => $catCount)
            {
                if($catCount > $max)
                {
                    $max = $catCount;
                    $temp = $cat;
                }
            }
            array_push($order, $temp);
            unset($counter[$temp]);
        }
        foreach($order as $idx => $cat)
        {
            $returnArr[$seaArr][$cat] = $holderArr[$seaArr][$cat];
        }
        $holderArr[$seaArr] = $order;
    }
    return [$returnArr , $holderArr];
}

?>