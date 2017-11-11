# TPE48 Vote Log

這裡記錄著 2017/11/09 ~ 2017/11/15 舉行的 TPE48 第三階段審查粉絲網路投票活動票數變化。

This repository records poll counts of the polling event held by TPE48, a Japanese style idol group based in Taipei, during 2017/11/09 ~ 2017/11/15.

## Usage

`data/[YYYYMMDD].csv` 可以取得當日票數紀錄，無標頭列，每列欄位順序為 `時間戳, 候選者編號, 累計票數`。
<br>
`data_table/[YYYYMMDD].csv` 可以取得當日票數紀錄，標頭列表欄位名稱，餘列欄位順序為 `時間戳, 第一位候選者累計票數, 第二位...`。

`data/[YYYYMMDD].csv` has no header, each row is consisted of `timestamp, candidate_id, accumulated_poll_count`.
<br>
`data_table/[YYYYMMDD].csv` has a header row, each following row is consisted of `timestamp, pollcount_for_candidate_1, pollcount_for_candidate_2, ...`。

## Demo

<div id="chartContainer" style="height: 700px; width: 100%;"></div>

以上示範參考自 [CanvasJS](https://canvasjs.com/javascript-charts/multi-series-chart/){:target="_blank"} 範例程式碼

Demo code is refereced from sample code of [CanvasJS](https://canvasjs.com/javascript-charts/multi-series-chart/){:target="_blank"}

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript">
var vote_data = [];
var chart;

fetch('https://zaka46.github.io/tpe48-votelog/data_table/20171111.csv')
  .then(function(response) {
    return response.text()
  }).then(function(body) {
    body.split("\n").forEach(function(row, index) {
      if(index == 0) {
        vote_data = row.split(',').map(function(element, index) {
          if(index == 0) return {};
          return {
            type: "line",
            axisYType: "secondary",
            xValueFormatString: "MM/DD HH:mm",
            name: element,
            showInLegend: true,
            markerSize: 0,
            dataPoints: []
          }
        });
      } else {
        row.split(',').forEach(function(element, index, array) {
          if(index == 0) return;
          if(typeof(vote_data[index].dataPoints) == 'undefined') return;
          var x = new Date(array[0] * 1000);
          vote_data[index].dataPoints.push({
            x: x,
            y: parseInt(element)
          })
        });
      }
    });
    createChart();
  });

function str_pad(n) {
  return String("0" + n).slice(-2);
}

function createChart() {
  chart = new CanvasJS.Chart("chartContainer", {
    title: {
      text: "20171111",
      fontSize: 20,
      fontFamily: "tahoma"
    },
    axisX: {
      valueFormatString: "MM/DD HH:mm",
      labelFontSize: 12
    },
    axisY2: {
      title: "票數",
      labelFontSize: 12,
      titleFontSize: 15,
/*      logarithmic: true,
      labelFormatter: function ( e ) {
        var suffixes = ["", "K", "M", "B"];
        var order = Math.max(Math.floor(Math.log(e.value) / Math.log(1000)), 0);
        if(order > suffixes.length - 1)
          order = suffixes.length - 1;
        var suffix = suffixes[order];
        return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
      }, */
      includeZero :false
    },
    toolTip: {
      shared: true,
      contentFormatter: function(e) {
				var content = "<table><tbody style='text-align:right'><tr><td colspan='3'>" + e.entries[0].dataPoint.x.getMonth() + '/' + e.entries[0].dataPoint.x.getDate() + ' ' + str_pad(e.entries[0].dataPoint.x.getHours()) + ':' + str_pad(e.entries[0].dataPoint.x.getMinutes()) + "</td></tr>";
        e.entries.forEach(function(element, index) {
          if (index % 10 == 0) content += "<tr>";
          content += "<td style='color:" + element.dataSeries.color + "'>" + element.dataSeries.name + "</td><td>" + element.dataPoint.y.toLocaleString('en-us') + "</td>";
          if ((index + 1) % 10 == 0) content += "</tr>";          
        })
        content += "</tbody></table>";
        return content;
			}
    },
    legend: {
      cursor: "pointer",
      verticalAlign: "top",
      horizontalAlign: "center",
      dockInsidePlotArea: true,
      fontSize: 12,
      itemclick: toogleDataSeries
    },
    data: vote_data
  });
  chart.render();
}

function toogleDataSeries(e) {
  if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  } else{
    e.dataSeries.visible = true;
  }
  chart.render();
}
</script>
