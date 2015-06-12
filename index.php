<!DOCTYPE HTML>
<html>
<head>
  <title>Data Analyzer</title>
  <meta charset="UTF-8">
  <!-- load main.js after require.js -->
  <script data-main="javascript/main" src="libs/require/Require.js"></script>
  <link rel="stylesheet" href="libs/SlickGrid/slick.grid.css" type="text/css"/>
  <link rel="stylesheet" href="libs/SlickGrid/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
  <link rel="stylesheet" href="libs/SlickGrid/examples/examples.css" type="text/css"/>
  <link rel="stylesheet" href="libs/jquery/jquery-ui.css"/>
  <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  </head>
<body>
  <header>
    <span id="title"><a href="index.php">Data Analyzer</a></span>
    <div class="inputBtnSection no-print">
      <label class="fileUpload">
          <input id="files" type="file" class="upload" onfocus="focusElement('uploadBtn')" onblur = "blurElement('uploadBtn')"/>
          <span class="uploadBtn" onmouseover="focusElement('uploadBtn')" onmouseleave="blurElement('uploadBtn')">Load CSV</span>
      </label>
    </div>
    <div class="newBtnSection no-print">
      <label class="createNew">
        <input id="newTable" type="button" class="emptyTable" value="Create New Table" onfocus="focusElement('createBtn')" onblur="blurElement('createBtn')"/>
        <span class="createBtn" onmouseover="focusElement('createBtn')" onmouseleave="blurElement('createBtn')">Create New Table</span>
      </label>
    </div>
    <output id="list"></output>
    <span id="audioSpan" class="no-print" style="display: none">
      <label for="lineDropdown" tabindex="0"> Data Set</label>
      <select id="lineDropdown" class='drop-down' aria-label="Choose Data Set" title="selected"></select>
      with speed <input id="bpm" class='drop-down' name="Speed Multiplier" type="number" min="0" value="1" aria-label="set speed"/>
      <button id="playButton" onclick="playStopAudioButton()" aria-label="Play Pause Toggle">
        <i id="icon" class="fa fa-play" style="padding-left: 30%;"></i></button>
    </span>
  </header>
  <div id="content">
    <div id="start">
      <h3 tabindex="0">Welcome to Data Analyzer!</h3>
      <p tabindex="0">
        This web application was designed to help you analyze data through graphs, calculated values, and sound.<br>
        Here's a quick look at how to use our application:
      </p>
      <ul>
        <li tabindex="0">Audio Controls: Our audio controls allow you to choose which set of data to play and at what speed. </li>
        <li tabindex="0">Graph: You can choose from three separate options when making your graph: Line, Bar, and Scatter Plot. There is also an overlay on our graph that you can control to highlight only certain parts of the graph to be turned into sound. </li>
        <li tabindex="0">Data Table: There are two options with the data table. You can either load a pre-made CSV file (Comma Separated Value) and interact with that, or you can choose to create your own empty table to work with from scratch. You can also add or subtract rows and columns from your table at will. Want to save your changes? Not a problem, from our site you can download your new CSV file to keep for later.
        </li>
        <li tabindex="0">Line Data: In our line data section, you can choose what colors you want for your data points on the graph, as well as toggle their visibility. Line data is also where we display your minimums, maximums, and averages for each row of data, including the minimum, maximum, and average of the total data set.</li>
      </ul>
      <p tabindex="0" >To get started, select Load CSV or Create New Table at the top!</p>
    </div>
      <form id="rTypeSel" style="display:none;" class="no-print">
          <input id="lineRadioButton" type="radio" name="rGraphSel" value="Line" onclick="rType()">
          <label for="lineRadioButton">Line Graph</label>
          <input id="barRadioButton" type="radio" name="rGraphSel" value="Bar" onclick="rType()">
          <label for="barRadioButton">Bar Graph</label>
          <input id="scatterRadioButton" type="radio" name="rGraphSel" value="Scatter" onclick="rType()">
          <label for="scatterRadioButton">Scatter Plot</label>
      </form>
      <div id="slider-range" data-start="0" data-end="-1" data-size="0" title="Chart Slider" class="no-print"></div>
      <svg id="overlay" width="800" height="400" style='display:none;' class="no-print">
          <rect id="background" x="0" y="0" width="800" height="400" />
          <rect id="selection" x="0" y="0" width="800" height="400" />
      </svg>
      <canvas title="CHART YEAH" id="myChart" width="800" height="400"></canvas>
      <div id="dataPlot">
          <div id="plot-header" style="display:none;">Data Table</div>
          <div id="tblContainer" title="Data Table">
              <div id="slickTable" style="width:100%;"></div>
          </div> <!-- end id="tblContainer" -->
      <div id="tableControls" style="display: none" class="no-print">
        <div id="rowLabel">
          <label><button id="subtractRow" aria-label="Remove Row" onclick="subtractRow()"> - </button></label>
          <p style="display: inline">Rows</p>
          <label><button id="addNewRow" aria-label="Add New Row" onclick="addRow()"> + </button></label>
        </div>
        <div id="columnLabel">
          <label><button id="subtractColumn" aria-label="Remove Column" onclick="subtractColumn()"> - </button></label>
          <p style="display: inline"> Columns</p>
          <label><button id="addNewCol" aria-label="Add New Column" onclick="addColumn()"> + </button></label>
        </div>
        <button id="downloadCSV" onclick="download()">Download CSV</button>
        <button id="printButton" onclick="printPage()">Print</button>
      </div>
    </div>
    <?php
        include 'php/ajax.php';
    ?>
    <div id='color-expand' style="display:none;">
      <div id='color-header' onclick="openColorEditor()">Graph Data </div>
      <div id="color-editor" class="color-editor-input">
        <ol id="colors" ></ol>
      </div>
    </div>
  </div><!-- end div id="content"-->
<!-- div for LOAD CSV pop-up list -->
  <div id="dialogoverlay"></div>
    <div id="dialogbox">
        <div>
            <div id="dialogboxhead"></div>
            <div id="dialogboxbody" class="sChartType"></div>
            <div id="dialogboxfoot"></div>
        </div>
    </div>
<!-- END div for LOAD CSV pop-up list -->
<!-- div for CREATE NEW TABLE pop-up list -->
  <div id="newFileDialogOverlay"></div>
    <div id="newFileDialogBox">
      <div id="newFileDialogHead"></div>
      <div id="newFileDialogBody" class="sChartType"></div>
      <div id="newFileDialogFoot"></div>
    </div>
<!-- END div for CREATE NEW TABLE pop-up list -->
</body>
</html>
