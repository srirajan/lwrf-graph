<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'K-Watts');
<?
require "/home/sri/sriramrajan.com/ssl/sri/conf.php";
$q = "select sum(watts)/1000,DATE(time) from lwrf.energy group by DATE(time)";
$q_type= $_REQUEST["q_type"];

$c_type = "Line";
$c_type= $_REQUEST["c_type"];

if ($q_type == "day")
    $q = "select SUM(IF((e2.watts-ifnull(e1.watts,0))>0,e2.watts-ifnull(e1.watts,0),e2.watts)) as sumdelta, DATE(e1.time) from lwrf.energy e1 left outer join lwrf.energy e2 on e2.id=e1.id+1 GROUP BY DATE(e1.time)";
if ($q_type == "month")
    $q = "select SUM(IF((e2.watts-ifnull(e1.watts,0))>0,e2.watts-ifnull(e1.watts,0),e2.watts)) as sumdelta, MONTH(e1.time) from lwrf.energy e1 left outer join lwrf.energy e2 on e2.id=e1.id+1 GROUP BY MONTH(e1.time)";
if ($q_type == "dow")
    $q = "select SUM(IF((e2.watts-ifnull(e1.watts,0))>0,e2.watts-ifnull(e1.watts,0),e2.watts)) as sumdelta, DAYNAME(e1.time) from lwrf.energy e1 left outer join lwrf.energy e2 on e2.id=e1.id+1 GROUP BY DAYNAME(e1.time) ORDER BY DAY(e1.time)";
if ($q_type == "hour")
    $q = "select SUM(IF((e2.watts-ifnull(e1.watts,0))>0,e2.watts-ifnull(e1.watts,0),e2.watts)) as sumdelta, HOUR(e1.time) from lwrf.energy e1 left outer join lwrf.energy e2 on e2.id=e1.id+1 GROUP BY HOUR(e1.time)";
if ($q_type == "topten")
    $q = "select SUM(IF((e2.watts-ifnull(e1.watts,0))>0,e2.watts-ifnull(e1.watts,0),e2.watts)) as sumdelta, CONCAT(DATE(e1.time), \"-\" ,DAYNAME(e1.time)) AS DAY from lwrf.energy e1 left outer join lwrf.energy e2 on e2.id=e1.id+1 GROUP BY DATE(e1.time) ORDER BY sumdelta DESC LIMIT 10";


$r = mysql_query($q) or die (mysql_error());

$ctr = 0;
$arr= "[";
while ($row = mysql_fetch_array($r)) {
     if ($ctr == 0) {
         $arr .= "['" . $row[1] . "'," . $row[0] . "]";
         $ctr++;
     }
     else {
         $arr .= ",['" . $row[1] . "'," . $row[0] . "]";
         $ctr++;
     }
}
$arr .= "]";

echo "data.addRows(" . $arr .");"
?>

        // Set chart options
        var options = {'title':'LWRF',
                       'width':1200,
                       'height':600};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.<? echo $c_type; ?>Chart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
